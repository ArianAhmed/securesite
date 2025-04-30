<?php
session_start();
require 'db.php';
require 'sessioncheck.php';

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $email = $_POST['email'] ?? '';
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = 'All fields needed';
    } 
    elseif(!ctype_alnum($username)) {
    	$error = 'Username needs to be alphabets or numbers';
    }
    elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    	$error = 'Invalid email format.';
    }elseif (empty($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
    	$error = 'Invalid CSRF.';
    }
    else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
	$stmt2 = $pdo->prepare("SELECT * FROM users WHERE email = ?");
	$stmt2->execute([$email]);
	if ($stmt->fetch()) {
            $error = 'Username is taken';
        }
	elseif ($stmt2->fetch()) {
    	    $error = 'Email is taken';
        }
	else{
            $hashed_password=password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
	    $stmt->execute([$username, $hashed_password, $email]);
            header("Location: index.php?error=Registration%20success!%20Please%20login!%20");
            exit;
        }
    }
}
?>

<div class="container">
    <h1>Register</h1>
    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif;?>
    <form method="POST">
	<input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
	<input type="text" name="username" placeholder="Username" required>
	<input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>
    <p>Go back? <a href="index.php">Login here</a></p>
</div>
