<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
session_start();
require 'db_connection.php';

// Проверка успешного подключения к базе данных
$connection_status = null;
if ($pdo) {
    $connection_status = "Подключение к базе данных успешно!";
} else {
    $connection_status = "Ошибка подключения к базе данных.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // Роль пользователя

        // Перенаправляем в чат
        header('Location: index.php');
        exit();
    } else {
        $login_error = "Неверные данные для входа.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="cssLogin.css">
</head>
<body>
    <div class="container">
        <h1>Вход в систему</h1>

        <!-- Сообщение о подключении к базе данных -->
        <?php if ($connection_status): ?>
            <div class="connection-status"><?php echo $connection_status; ?></div>
        <?php endif; ?>

        <?php if (isset($login_error)): ?>
            <div class="error-message"><?php echo $login_error; ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>
