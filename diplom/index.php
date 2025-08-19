<?php
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
session_start();

// Если пользователь не авторизован, перенаправляем на страницу авторизации
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Перенаправляем на страницу логина
    exit(); // Останавливаем дальнейшее выполнение скрипта
}

// Логика выхода (logout)
if (isset($_GET['logout'])) {
    session_unset();  // Удаляет все переменные сессии
    session_destroy(); // Уничтожает сессию
    header('Location: login.php'); // Перенаправляем на страницу логина
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чат с моделью</title>
    <link rel="stylesheet" href="css.css">
</head>
<body>

    <div class="container">

        <h1>Чат с моделью</h1>

        <div id="form-container">
            <div class="form-input">
                <label for="job-title">Должность:</label>
                <input type="text" id="job-title" placeholder="Введите вашу должность">
            </div>
            <div class="form-input">
                <label for="equipment-name">Наименование оборудования:</label>
                <input type="text" id="equipment-name" placeholder="Введите наименование оборудования">
            </div>
            <div class="form-input">
                <label for="issue-description">Что случилось?</label>
                <input type="text" id="issue-description" placeholder="Опишите проблему">
            </div>
            <div class="form-input">
                <label for="shop-name" >Наименование цеха:</label>
                <input type="text" id="shop-name" placeholder="Введите наименование цеха">
            </div>
            <button id="send-btn">Отправить</button>
            <button id="save-root-cause-btn" disabled>Сохранить корневую причину</button> <!-- Кнопка для сохранения -->
            <button id="start-new-chat-btn">Начать новый чат</button> <!-- Новая кнопка -->
        </div>
        
        <div id="chat-box">
            <!-- Здесь будет выводиться чат с моделью -->
        </div>
        <input type="text" id="input-box" placeholder="Введите сообщение...">
        <button id="send-btn-chat">Отправить сообщение</button>

        <div class="top-buttons">
            <!-- Показывать кнопку "Панель администратора" только для пользователей с ролью "admin" -->
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin_panel.php" class="admin-button">Панель администратора</a>
            <?php endif; ?>
            <!-- Кнопка "Выход" доступна для всех пользователей -->
            <a href="?logout=true" class="logout-button">Выход</a>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>


