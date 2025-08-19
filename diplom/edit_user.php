<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
session_start();

// Проверка роли администратора
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Подключаемся к базе данных
require 'db_connection.php';

// Получаем id пользователя для редактирования
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    // Если пользователь не найден, перенаправляем обратно
    if (!$user) {
        header('Location: admin_panel.php');
        exit();
    }
}

// Обработка формы редактирования
if (isset($_POST['edit_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Проверка, существует ли уже пользователь с таким именем, исключая текущего пользователя
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
    $stmt->execute([$username, $id]);
    $existingUser = $stmt->fetchColumn();

    if ($existingUser > 0) {
        echo "Ошибка: имя пользователя '$username' уже занято!";
    } else {
        // Подготовка запроса для обновления данных пользователя
        if (!empty($password)) {
            // Если пароль не пустой, обновляем и пароль
            $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $password, $role, $id]);
        } else {
            // Если пароль пустой, обновляем только имя пользователя и роль
            $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $role, $id]);
        }

        echo "Данные пользователя обновлены!";
        header('Location: admin_panel.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование пользователя</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #4CAF50;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Редактирование пользователя</h1>

        <form method="POST">
            <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
            <input type="password" name="password" placeholder="Новый пароль (не изменится, если пусто)">
            <select name="role">
                <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>Пользователь</option>
                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Администратор</option>
            </select>
            <button type="submit" name="edit_user">Обновить пользователя</button>
        </form>

        <br>
        <a href="admin_panel.php">Вернуться в панель администратора</a>
    </div>
</body>
</html>
