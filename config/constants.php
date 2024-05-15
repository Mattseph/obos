<?php
session_start();
date_default_timezone_set('Asia/Manila');
define('SITEURL', 'https://071b-2001-4456-c88-d300-869b-f4cc-2bd6-73f8.ngrok-free.app/obos/');

$localhost = 'localhost';
$db_name = 'inspection';
$username = 'root';
$password = '';


$dsn = "mysql:host=$localhost;dbname=$db_name;charset=UTF8;";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}