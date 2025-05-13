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

// Получение данных редактируемого пользователя
$user_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['error'] = "User not found";
    header("Location: admin_dashboard.php");
    exit();
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    
    try {
        $update_stmt = $pdo->prepare("
            UPDATE users SET 
                username = ?, 
                email = ?, 
                is_active = ?, 
                is_admin = ? 
            WHERE id = ?
        ");
        $update_stmt->execute([$username, $email, $is_active, $is_admin, $user_id]);
        
        $_SESSION['success'] = "User updated successfully!";
        header("Location: admin_dashboard.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating user: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Lightning Cars</title>
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
            max-width: 600px;
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
        input[type="email"] {
            width: 100%;
            padding: 12px;
            background-color: #333;
            border: 1px solid #555;
            border-radius: 5px;
            color: white;
            font-size: 16px;
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
        <h1>Edit User: <?= htmlspecialchars($user['username']) ?></h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" 
                       value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="is_active" name="is_active" 
                       <?= $user['is_active'] ? 'checked' : '' ?>>
                <label for="is_active">Active account</label>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="is_admin" name="is_admin" 
                       <?= $user['is_admin'] ? 'checked' : '' ?>>
                <label for="is_admin">Administrator privileges</label>
            </div>
            
            <button type="submit" class="btn btn-block">Save Changes</button>
        </form>
    </div>
</body>
</html>