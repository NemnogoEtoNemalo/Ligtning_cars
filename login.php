<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

$error = '';

// Проверяем, что форма была отправлена методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем существование ключей в $_POST
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? ''; // Теперь безопасное получение пароля
    
    if (empty($username) || empty($password)) {
        $error = "Все поля обязательны для заполнения";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: profile.php");
                exit();
            } else {
                $error = "Неверное имя пользователя или пароль";
            }
        } catch (PDOException $e) {
            $error = "Ошибка при входе в систему";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            font-family: 'KronaOne-Regular', Tahoma, Verdana, sans-serif;
            background-color: #000000;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #ffffff;
            flex-direction: column;
        }
        
        .login-container {
            background-color: rgb(0, 0, 0);
            padding: 30px 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            transition: transform 0.3s ease;
        }
        
        .login-container:hover {
            transform: translateY(-5px);
        }
        
        h1 {
            text-align: center;
            color: #fdfeff;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #ffffff;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #FFE100;
            outline: none;
        }
        
        button {
            width: 100%;
            padding: 14px;
            background-color: #FFE100;
            color: black;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        
        button:hover {
            background-color: #8d7d00;
            transform: translateY(-2px);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .footer {
            text-align: center;
            margin-top: 25px;
            color: #ffffff;
            font-size: 14px;
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
                <a href="./register.php">Regisration</a>
            </li>
           </ul>
           <img src="./img/Group 1.png" class="reg-img">
        </div>
    </header>
    <div class="login-container">
        <h1>Login</h1>
        <form id="loginForm" method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Login:</label>
                <input type="text" id="username" name="username" required placeholder="Enter your login">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="footer">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>