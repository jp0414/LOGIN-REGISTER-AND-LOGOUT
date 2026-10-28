<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "academic_tutoring_web_business";
$dsn = "mysql:host={$host};dbname={$dbname}";

$pdo = new PDO($dsn, $user, $password);
$pdo->exec("SET time_zone = '+08:00';");

// Start session safely for login/logout functionality
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

?>