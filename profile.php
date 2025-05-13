<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';
require 'auth.php';

// Получаем данные пользователя
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    die("Ошибка при получении данных пользователя: " . $e->getMessage());
}

// Обработка сообщений
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Lightning Cars</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background-color: #1a1a1a;
            border-radius: 10px;
            color: white;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .profile-card {
            background-color: #2a2a2a;
            padding: 20px;
            border-radius: 8px;
        }
        .profile-card h3 {
            color: #FFE100;
            margin-top: 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #FFE100;
            color: black;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 5px;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background-color: #d4c000;
            transform: translateY(-2px);
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #4CAF50;
            color: white;
        }
        .alert-error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="./index.php"><img src="./img/Logo.png" class="logo"></a>
        <div class="header-box">
            <ul class="main-menu">
                <li class="menu-item">
                    <a href="./index.php#home">Home</a>
                </li>
                <li class="menu-item">
                    <a href="./index.php#catalog">Catalog</a>
                </li>
                <li class="menu-item">
                    <a href="./index.php#about-us">About us</a>
                </li>
                <li class="menu-item">
                    <a href="./index.php#contacts">Contacts</a>
                </li>
            </ul>
        </div>
        <div class="regisration">
           <ul class="reg-menu">
            <li class="rmenu-item">
                <a href="./profile.php">My Profile</a>
            </li>
            <li class="rmenu-item">
                <a href="./logout.php">Logout</a>
            </li>
           </ul>
           <img src="./img/Group 1.png" class="reg-img">
        </div>
    </header>

    <main class="main">
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="profile-container">
            <div class="profile-header">
                <h1>User Profile</h1>
                <p>Welcome back, <?= htmlspecialchars($user['username']) ?>!</p>
            </div>
            
            <div class="profile-info">
                <div class="profile-card">
                    <h3>Account Information</h3>
                    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'Not specified') ?></p>
                    <p><strong>Member since:</strong> <?= date('F j, Y', strtotime($user['created_at'])) ?></p>
                    <p><strong>Status:</strong> <?= $user['is_active'] ? 'Active' : 'Inactive' ?></p>
                </div>
                
                <div class="profile-card">
                    <h3>Actions</h3>
                    <a href="edit_profile.php?id=<?= $user['id'] ?>" class="btn">Edit Profile</a>
                    <a href="change_password.php?id=<?= $user['id'] ?>" class="btn">Change Password</a>
                    <?php if ($user['is_admin']): ?>
                        <a href="admin_dashboard.php" class="btn">Admin Dashboard</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- После блока с информацией о пользователе -->
        <div class="reservation-form">
            <h3>Book a Car</h3>
            <form method="POST" action="make_reservation.php">
                <div class="form-group">
                    <label for="car_id">Select Car:</label>
                    <?php
                    $cars = $pdo->query("
                        SELECT car_id, name, year 
                        FROM cars 
                        WHERE is_available = 1
                        ORDER BY name
                    ")->fetchAll();
                    
                    if (empty($cars)) {
                        echo '<div class="alert">No cars available for booking</div>';
                    } else {
                        echo '<select name="car_id" id="car_id" class="form-control" required>';
                        foreach ($cars as $car) {
                            echo sprintf(
                                '<option value="%d">%s (%d)</option>',
                                $car['car_id'],
                                htmlspecialchars($car['name']),
                                $car['year']
                            );
                        }
                        echo '</select>';
                    }
                    ?>
                </div>
                
                <div class="form-group">
                    <label for="start_date">Date:</label>
                    <input type="date" name="start_date" id="start_date" required min="<?= date('Y-m-d') ?>">
                </div>
                
                <button type="submit" name="submit_reservation">Book Now</button>
            </form>
        </div>

        <!-- Добавим список текущих бронирований -->
        <div class="user-reservations">
            <h3>Your Reservations</h3>
            <?php
            $reservations = $pdo->prepare("
                SELECT r.*, c.name as car_name 
                FROM reservations r
                JOIN cars c ON r.car_id = c.car_id
                WHERE r.user_id = ?
                ORDER BY r.start_date DESC
            ");
            $reservations->execute([$_SESSION['user_id']]);
            
            if ($reservations->rowCount() > 0) {
                foreach ($reservations as $res) {
                    echo "<div class='reservation-card'>
                        <h4>{$res['car_name']} (".date('M j, Y', strtotime($res['start_date'])).")</h4>
                        <p>Status: <span class='status-{$res['status']}'>{$res['status']}</span></p>
                        <p>Booked on: ".date('M j, Y', strtotime($res['created_at']))."</p>
                    </div>";
                }
            } else {
                echo "<p>You have no reservations yet.</p>";
            }
            ?>
        </div>
    </main>
</body>
</html>