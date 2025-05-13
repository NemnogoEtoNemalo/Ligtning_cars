<?php
require 'config.php';
require 'auth.php';

// Проверка прав администратора
$admin_check = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$admin_check->execute([$_SESSION['user_id']]);
$admin = $admin_check->fetch();

if (!$admin || !$admin['is_admin']) {
    $_SESSION['error'] = "Access denied - Admin privileges required";
    header("Location: profile.php");
    exit();
}

$car_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM cars WHERE car_id = ?");
$stmt->execute([$car_id]);

$_SESSION['success'] = "Car deleted successfully!";
header("Location: admin_dashboard.php");
exit();
?>