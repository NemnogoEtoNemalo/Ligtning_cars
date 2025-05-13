<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

// Проверка прав администратора
$admin_stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$admin_stmt->execute([$_SESSION['user_id']]);
$admin = $admin_stmt->fetch();

if (!$admin || !$admin['is_admin']) {
    $_SESSION['error'] = "Access denied - Admin privileges required";
    header("Location: profile.php");
    exit();
}

// Получение данных автомобиля
$car_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM cars WHERE car_id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch();

if (!$car) {
    $_SESSION['error'] = "Car not found";
    header("Location: admin_dashboard.php");
    exit();
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = (float)$_POST['price'];
    $year = (int)$_POST['year'];
    $description = $_POST['description'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;
    
    try {
        $update_stmt = $pdo->prepare("
            UPDATE cars SET 
                name = ?,
                description = ?,
                price = ?,
                year = ?,
                description = ?,
                is_available = ?
            WHERE car_id = ?
        ");
        $update_stmt->execute([$name, $model, $price, $year, $description, $is_available, $car_id]);
        
        $_SESSION['success'] = "Car updated successfully!";
        header("Location: admin_dashboard.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating car: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car - Lightning Cars</title>
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
            padding: 20px;
        }
        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background-color: var(--dark);
            border-radius: 10px;
        }
        h1 {
            color: var(--primary);
            margin-top: 0;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            background-color: #333;
            border: 1px solid #555;
            border-radius: 5px;
            color: white;
            font-size: 16px;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .checkbox-group input {
            margin-right: 10px;
            width: auto;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--primary);
            color: black;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            margin-right: 10px;
        }
        .btn-block {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 20px;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-error {
            background-color: #ff4444;
            color: white;
        }
        .alert-success {
            background-color: #00C851;
            color: white;
        }
        .car-image-preview {
            max-width: 300px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <a href="admin_dashboard.php" class="btn">← Back to Dashboard</a>
    
    <div class="form-container">
        <h1>Edit Car: <?= htmlspecialchars($car['name']) ?></h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Car Name</label>
                <input type="text" id="name" name="name" 
                       value="<?= htmlspecialchars($car['name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" 
                       value="<?= htmlspecialchars($car['description']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="year">Year</label>
                <input type="number" id="year" name="year" min="1900" max="<?= date('Y') + 1 ?>"
                       value="<?= htmlspecialchars($car['year']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="price">Price($)</label>
                <input type="number" id="price" name="price" step="0.01" min="0"
                       value="<?= htmlspecialchars($car['price']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?= htmlspecialchars($car['description']) ?></textarea>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="is_available" name="is_available" 
                       <?= $car['is_available'] ? 'checked' : '' ?>>
                <label for="is_available">Available for booking</label>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-block">Save Changes</button>
            </div>
        </form>
        
        <form method="POST" action="delete_car.php" onsubmit="return confirm('Are you sure you want to delete this car?');">
            <input type="hidden" name="id" value="<?= $car['car_id'] ?>">
            <button type="submit" class="btn btn-danger btn-block">Delete Car</button>
        </form>
    </div>
</body>
</html>