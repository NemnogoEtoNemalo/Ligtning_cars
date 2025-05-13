<?php
require 'db_connect.php'; // Перенесите подключение к БД в отдельный файл

$host = 'localhost';
$dbname = 'lightning_cars'; // Имя вашей базы данных
$username = 'root'; // Ваш пользователь MySQL
$password = ''; // Ваш пароль MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(3, 2); // 3 = ATTR_ERRMODE, 2 = ERRMODE_EXCEPTION
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>