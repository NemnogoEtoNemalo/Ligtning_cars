<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';
require 'auth.php';

// Проверка прав администратора через прямое обращение к БД
$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || !$user['is_admin']) {
    $_SESSION['error'] = "Access denied - Admin privileges required";
    header("Location: profile.php");
    exit();
}

// Обработка изменения статуса бронирования
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $reservation_id = $_POST['reservation_id'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE reservations SET status = ? WHERE reservation_id = ?");
    $stmt->execute([$status, $reservation_id]);
    $_SESSION['success'] = "Reservation status updated!";
}

// Получение данных для админки
$reservations = $pdo->query("
    SELECT r.*, u.username, c.name as car_name 
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    JOIN cars c ON r.car_id = c.car_id
    ORDER BY r.created_at DESC
")->fetchAll();

$users = $pdo->query("SELECT * FROM users")->fetchAll();
$cars = $pdo->query("SELECT * FROM cars")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lightning Cars</title>
    <style>
        :root {
            --primary: #FFE100;
            --dark: #1a1a1a;
            --light: #f8f9fa;
        }
        body {
            font-family: 'KronaOne-Regular', sans-serif;
            background-color: #000;
            color: white;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: var(--dark);
            align-items: center;
        }
        .admin-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #333;
        }
        th {
            background-color: #222;
            color: var(--primary);
        }
        tr:hover {
            background-color: #1a1a1a;
        }
        .status-pending {
            color: orange;
        }
        .status-confirmed {
            color: green;
        }
        .status-cancelled {
            color: red;
        }
        .status-completed {
            color: blue;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: var(--primary);
            color: black;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 5px;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .form-group {
            margin-bottom: 15px;
        }
        select, input {
            padding: 8px;
            background-color: #333;
            color: white;
            border: 1px solid #555;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="./index.php"><img src="./img/Logo.png" class="logo" style="height: 50px;"></a>
        <div>
            <a href="profile.php" class="btn">User Profile</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </header>

    <div class="admin-container">
        <h1>Admin Dashboard</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div style="color: red; margin-bottom: 20px;"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div style="color: green; margin-bottom: 20px;"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <!-- Бронирования -->
        <h2>Reservations Management</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Car</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                <tr>
                    <td><?= $res['reservation_id'] ?></td>
                    <td><?= htmlspecialchars($res['username']) ?></td>
                    <td><?= htmlspecialchars($res['car_name']) ?></td>
                    <td><?= date('M j, Y', strtotime($res['start_date'])) ?></td>
                    <td class="status-<?= $res['status'] ?>"><?= ucfirst($res['status']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="reservation_id" value="<?= $res['reservation_id'] ?>">
                            <select name="status">
                                <option value="pending" <?= $res['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="confirmed" <?= $res['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                <option value="cancelled" <?= $res['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                <option value="completed" <?= $res['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                            <button type="submit" name="update_status" class="btn">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Пользователи -->
        <h2>Users Management</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['is_admin'] ? 'Admin' : 'User' ?></td>
                    <td><?= $user['is_active'] ? 'Active' : 'Blocked' ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn">Edit</a>
                        <?php if ($user['is_active']): ?>
                            <a href="block_user.php?id=<?= $user['id'] ?>" class="btn btn-danger">Block</a>
                        <?php else: ?>
                            <a href="unblock_user.php?id=<?= $user['id'] ?>" class="btn">Unblock</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Машины -->
        <h2>Cars Management</h2>
        <a href="add_car.php" class="btn">Add New Car</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                <tr>
                    <td><?= $car['car_id'] ?></td>
                    <td><?= htmlspecialchars($car['name']) ?></td>
                    <td><?= htmlspecialchars($car['description']) ?></td>
                    <td>$<?= number_format($car['price'], 2) ?></td>
                    <td><?= $car['is_available'] ? 'Available' : 'Unavailable' ?></td>
                    <td>
                        <a href="edit_car.php?id=<?= $car['car_id'] ?>" class="btn">Edit</a>
                        <a href="delete_car.php?id=<?= $car['car_id'] ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>