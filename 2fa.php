<?php

require 'sessioncheck.php';

if (!isset($_SESSION['passed'])) {
    header("Location: index.php?error=2fa%20after%20login!");
    exit;
}

if ($_SESSION['passed'] === true) {
    header("Location: dashboard.php");
    exit;
}
$error = '';
if (!isset($_SESSION['tries'])) {
    $_SESSION['tries'] = 0;
}
echo "DEBUG: 2FA Code is : " . htmlspecialchars($_SESSION['code']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_code = $_POST['code'] ?? '';
    if (time() - $_SESSION['code_time'] > 180) {
        unset($_SESSION['code'], $_SESSION['code_time'],$_SESSION['tries'] ,$_SESSION['user_id']);
        header("Location: index.php?error=2FA%20expired%20or%20failed");
        exit;
    } elseif ((int)$input_code === $_SESSION['code']) {
        $_SESSION['passed'] = true;
        unset($_SESSION['code'], $_SESSION['code_time']);
        header("Location: dashboard.php");
        exit;
    } else {
	    $error = "Wrong code.";
	    $_SESSION['tries']++;
	    if($_SESSION['tries']==5){
		    unset($_SESSION['code'], $_SESSION['code_time'],$_SESSION['tries'] ,$_SESSION['user_id']);
        	header("Location: index.php?error=2FA%20expired%20or%20failed");
       	 	exit;
	    }
    }
}
?>

<form method="POST" action="2fa.php">
    <label>Enter 2FA Code:</label>
    <input type="text" name="code">
    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</form>
