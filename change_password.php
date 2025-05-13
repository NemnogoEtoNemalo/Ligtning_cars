<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';
require 'auth.php';

$user_id = $_GET['id'] ?? $_SESSION['user_id'];

// Проверка, что пользователь меняет свой пароль
if ($user_id != $_SESSION['user_id']) {
    $_SESSION['error'] = "You can only change your own password";
    header("Location: profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Проверка совпадения новых паролей
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New passwords don't match";
        header("Location: change_password.php");
        exit();
    }
    
    // Проверка текущего пароля
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    
    if (!password_verify($current_password, $user['password'])) {
        $_SESSION['error'] = "Current password is incorrect";
        header("Location: change_password.php");
        exit();
    }
    
    // Обновление пароля
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update_stmt->execute([$hashed_password, $user_id]);
    
    $_SESSION['success'] = "Password changed successfully!";
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
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
            margin: 50px auto;
            padding: 30px;
            background-color: var(--dark);
            border-radius: 10px;
        }
        h1 {
            color: var(--primary);
            margin-top: 0;
            text-align: center;
            font-size: 2rem;
        }
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 1.1rem;
            color: var(--primary);
        }
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            background-color: #333;
            border: 2px solid #555;
            border-radius: 6px;
            color: white;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input[type="password"]:focus {
            border-color: var(--primary);
            outline: none;
        }
        .password-strength {
            height: 5px;
            background: #333;
            margin-top: 5px;
            border-radius: 3px;
            overflow: hidden;
        }
        .strength-meter {
            height: 100%;
            width: 0;
            transition: width 0.3s, background 0.3s;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--primary);
            color: black;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .btn:hover {
            background-color: #d4c000;
            transform: translateY(-2px);
        }
        .btn-block {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 30px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 6px;
            text-align: center;
        }
        .alert-success {
            background-color: #00C851;
            color: white;
        }
        .alert-error {
            background-color: #ff4444;
            color: white;
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 40px;
            cursor: pointer;
            color: #777;
        }
        .password-toggle:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <a href="profile.php" class="btn">← Back to Profile</a>
    
    <div class="form-container">
        <h1>Change Password</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required minlength="6">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
            </div>
            
            <button type="submit" class="btn btn-block">Change Password</button>
        </form>
    </div>
</body>
</html>