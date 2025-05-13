<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Дополнительные функции для проверки прав
function isAdmin() {
    return $_SESSION['is_admin'] ?? false;
}
?>