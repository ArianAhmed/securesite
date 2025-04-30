<?php
session_start();
require 'db.php';

$time= time();

if (!isset($_SESSION['wrongt'])) {
    $_SESSION['wrongt'] = 0;
}
if (!isset($_SESSION['lastt'])) {
    $_SESSION['lastt'] = 0;
}


if($_SERVER['REQUEST_METHOD']==='POST'){
    $username=$_POST['username'] ?? ''; $password=$_POST['password'] ?? '';
    if (empty($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
	    header("Location: index.php?error=Invalid%20CSRF");
	    exit;
    }
    if (empty($username)||empty($password)){
        die('User and pass needed');
    }
    if ($_SESSION['wrongt'] >= 3 && ($time - $_SESSION['lastt']) < pow(2,$_SESSION['wrongt'])) {
        $left_time = ($_SESSION['lastt'] + pow(2,$_SESSION['wrongt'])) - $time;
        header("Location: index.php?error=Too%20many%20tries.%20Try%20again%20in%20{$left_time}%20seconds.");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");$stmt->execute([$username]);$user = $stmt->fetch(); 
    if($user && password_verify($password,$user['password'])){
	session_regenerate_id(true);    
	$_SESSION['user_id'] = $user['id'];
	$_SESSION['wrongt'] =0;
	$_SESSION['lastt'] =0;
	$_SESSION['email'] = $user['email'];
	$_SESSION['code'] = random_int(11233124, 90184910);
	$_SESSION['code_time'] = time();
	$_SESSION['passed'] = false;
	header("Location: 2fa.php");
        exit;
    }else{
	$_SESSION['wrongt']++;
	$_SESSION['lastt'] =$time;
	if ($_SESSION['wrongt'] >= 3 && ($time - $_SESSION['lastt']) < pow(2,$_SESSION['wrongt'])) {
        	$left_time = ($_SESSION['lastt'] + pow(2,$_SESSION['wrongt'])) - $time;
        	header("Location: index.php?error=Too%20many%20tries.%20Try%20again%20in%20{$left_time}%20seconds.");
        	exit;
    	}
        header("Location: index.php?error=User%20or%20Password%20is%20wrong");
    }
}else{
    header("Location: index.php?error=Error");
}
?>
