<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

// Отладочный вывод
echo "<pre>POST data: ";
print_r($_POST);
echo "Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET');
echo "</pre>";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
    $user_id = $_SESSION['user_id'];
    $car_id = $_POST['car_id'];
    $start_date = $_POST['start_date'];
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO reservations (user_id, car_id, start_date)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$user_id, $car_id, $start_date]);
        
        $_SESSION['success'] = "Car booked successfully for " . date('M j, Y', strtotime($start_date));
    } catch (PDOException $e) {
        $_SESSION['error'] = "Booking failed: " . $e->getMessage();
    }
    
    header("Location: profile.php");
    exit();
}