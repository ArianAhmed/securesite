<?php
session_start();
if (isset($_SESSION['user_id']) && ($_SESSION['passed']??false) && basename($_SERVER['PHP_SELF']) !== 'dashboard.php') {
    header("Location: dashboard.php");
    exit;
}

if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) === 'dashboard.php') {
    header("Location: index.php?error=Login%20first!");
    exit;
}

if (isset($_SESSION['user_id']) && !($_SESSION['passed']??false) && basename($_SERVER['PHP_SELF']) !== '2fa.php') {
    header("Location: 2fa.php");
    exit;
}
