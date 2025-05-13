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

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = htmlspecialchars($_POST['name']);
        $price = (float)$_POST['price'];
        $year = (int)$_POST['year'];
        $description = htmlspecialchars($_POST['description']);
        $is_available = isset($_POST['is_available']) ? 1 : 0;

        // Валидация данных (без проверки model)
        if (empty($name) || $price <= 0 || $year < 1900 || $year > date('Y') + 1) {
            throw new Exception("Invalid input data");
        }

        $stmt = $pdo->prepare("
            INSERT INTO cars 
            (name, price, year, description, is_available) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $price, $year, $description, $is_available]);
        
        $_SESSION['success'] = "Car added successfully!";
        header("Location: admin_dashboard.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error adding car: " . $e->getMessage();
        header("Location: add_car.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car - Lightning Cars</title>
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
        textarea {
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
        }
        .btn-block {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 20px;
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
    </style>
</head>
<body>
    <a href="admin_dashboard.php" class="btn">← Back to Dashboard</a>
    
    <div class="form-container">
        <h1>Add New Car</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Car Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="year">Year</label>
                <input type="number" id="year" name="year" min="1900" max="<?= date('Y') + 1 ?>" required>
            </div>
            
            <div class="form-group">
                <label for="price">Price($)</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="is_available" name="is_available" checked>
                <label for="is_available">Available for booking</label>
            </div>
            
            <button type="submit" class="btn btn-block">Add Car</button>
        </form>
    </div>
</body>
</html>