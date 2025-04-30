<?php require 'sessioncheck.php' ?>
<?php include 'htmlhead.php'; ?>

<?php
if (isset($_GET['error'])) {
    $message = htmlspecialchars($_GET['error']);
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
?>

<div class="container">
    <h1>Login</h1>
    <?php if (isset($message)): ?>
        <div class="success"><?php echo $message; ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
	<input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf']);?>">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Log In</button>
    </form>
<p>New user?<a href="register.php"><button type="button">Register here</button></a></p>

</div>
</body>
</html>

