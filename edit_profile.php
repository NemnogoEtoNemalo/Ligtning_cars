<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';
require 'auth.php';

$user_id = $_GET['id'] ?? $_SESSION['user_id'];

// Проверка, что пользователь редактирует свой профиль
if ($user_id != $_SESSION['user_id']) {
    $_SESSION['error'] = "You can only edit your own profile";
    header("Location: profile.php");
    exit();
}

// Получение данных пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $user_id]);
        
        $_SESSION['success'] = "Profile updated successfully!";
        header("Location: profile.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error updating profile: " . $e->getMessage();
    }
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
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 1.1rem;
            color: var(--primary);
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            background-color: #333;
            border: 2px solid #555;
            border-radius: 6px;
            color: white;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: var(--primary);
            outline: none;
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
    </style>
</head>
<body>
    <a href="profile.php" class="btn">← Back to Profile</a>
    
    <div class="form-container">
        <h1>Edit Profile</h1>
        
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
            
            <button type="submit" class="btn btn-block">Save Changes</button>
        </form>
    </div>
</body>
</html>