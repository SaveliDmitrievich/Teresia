<?php
$host = 'localhost';
$dbname = 'hellokittyshop';
$username = 'root';
$password = 'mysql';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e) {
    error_log("Ошибка подключения к базе данных: " . $e->getMessage(), 3, 'log/errors.log');
    die("Ошибка подключения к базе данных.");
}

?>
