<?php
$pdo= new PDO("mysql:host=localhost;dbname=securesite;charset=utf8mb4", "root", "rootpassword");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

