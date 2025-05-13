<?php
// Подключение к базе данных
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);
    $email = trim($_POST['email'] ?? '');
    
    // Валидация данных
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Все поля обязательны для заполнения";
    } elseif ($password !== $confirm_password) {
        $error = "Пароли не совпадают";
    } elseif (strlen($password) < 6) {
        $error = "Пароль должен содержать минимум 6 символов";
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $error = "Пароль должен содержать хотя бы одну заглавную букву и одну цифру";
    } elseif (strlen($username) < 3) {
        $error = "Логин должен содержать минимум 3 символа";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Пожалуйста, введите корректный email";
    } 
    // После получения данных формы
    $username = trim($_POST['username']);

    // Проверка существования пользователя
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $userExists = $stmt->fetchColumn();

    if ($userExists > 0) {
        // Пользователь уже существует
        $_SESSION['error'] = "Пользователь с именем '$username' уже зарегистрирован";
        header("Location: register.php");
        exit();
    }

    // Если пользователя нет - продолжаем регистрацию
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $_POST['email']]);
        
        $_SESSION['success'] = "Регистрация успешна!";
        header("Location: login.php");
        exit();
        
    } catch (PDOException $e) {
        // Дополнительная обработка других возможных ошибок
        $_SESSION['error'] = "Ошибка регистрации: " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
    
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New user registration</title>
    <style>
        @font-face {
            font-family: 'KronaOne-Regular';
            src: url('./fonts/KronaOne-Regular.ttf');
        }
        
        a {
            position: relative;
            color: #ffffff;
            cursor: pointer;
            line-height: 1;
            text-decoration: none;
        }

        a:after {
            display: block;
            position: absolute;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #ffffff;
            content: "";
            transition: width 0.3s ease-out;
        }

        a:hover:after,
        a:focus:after {
            width: 100%;
        }

        .header {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 90px;
        }
        .logo{
            height: 70px;
            width: 365px;
            padding-top: 10px;
        }
        main {
            max-width: 1400px;
            margin: 0 auto;
            
        }

        .main-menu {
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            gap: 8px;
            scroll-behavior: smooth !important;
            width: 650px;
            height: 30px;
            margin-top: 35px;
        }

        .main-menu li {
            list-style: none;
        }

        .regisration {
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            width: 270px;
            height: 60px;
        }
        .reg-img {
            width: 45px;
            height: 50px;
        }

        .reg-menu{
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 220px;
            height: 80px;
        }

        .reg-menu li{
            list-style: none;
        }

        .rmenu-item{
            display: flex;
            justify-content: center;
            height: 40px;
        }

        .reg-img{
            display: flex;
            justify-content: center;
            height: 40px;
            margin-top: 20px;
        }


        body {
            font-family: 'KronaOne-Regular', Arial, sans-serif;
            color: #ffffff;
            background-color: #000000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        
        .login-container {
            background-color: rgb(0, 0, 0);
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
        }
        
        h1 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #ffffff;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #000000;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="email"]:focus {
            border-color: #FFE100;
            outline: none;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background-color: #FFE100;
            color: rgb(0, 0, 0);
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #8d7d00;
        }
         
                .error-message {
            color: #ff3333;
            margin-bottom: 15px;
            text-align: center;
        }
        .success-message {
            color: #33ff33;
            margin-bottom: 15px;
            text-align: center;
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
                <a href="./login.php">Log in</a>
            </li>
            <li class="rmenu-item">
                <a href="./register.php">Registration</a>
            </li>
           </ul>
           <img src="./img/Group 1.png" class="reg-img">
        </div>
    </header>
    <div class="login-container">
        <h1>Registration</h1>
        
        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <form id="registrationForm" method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Login:</label>
                <input type="text" id="username" name="username" required 
                       placeholder="Enter your login" 
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Enter your password">
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm your password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required 
                       placeholder="Repeat your password">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required 
                    placeholder="Enter your email"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
            <button type="submit">Register</button>
        </form>
        <div class="footer">
            Already have an account? <a href="./login.php">Login</a>
        </div>
    </div>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>
</body>
</html>