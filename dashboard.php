<?php

require 'sessioncheck.php';
require 'db.php';

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (isset($_SESSION['LAST_ACTIVITY'])&&((time() - $_SESSION['LAST_ACTIVITY']) > 30)) {
    session_unset();
    session_destroy();
    header("Location: index.php?error=Inactivity!%20Login%20Again");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

?>
<?php include 'htmlhead.php'; ?>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
    <p>You are in!</p>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
