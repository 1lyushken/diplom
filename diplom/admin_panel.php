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

// Проверка успешного подключения к базе данных
$connection_status = "Ошибка подключения к базе данных."; // Инициализация переменной
if ($pdo) {
    $connection_status = "Подключение к базе данных успешно!";
}

// Функция для скачивания таблицы в формате CSV
function downloadCSV($pdo) {
    $stmt = $pdo->query("SELECT * FROM root_causes");
    $root_causes = $stmt->fetchAll();

    // Устанавливаем заголовки для скачивания файла CSV
    header("Content-Type: text/csv; charset=UTF-8"); // Устанавливаем кодировку UTF-8
    header("Content-Disposition: attachment; filename=root_causes.csv");

    // Устанавливаем BOM для UTF-8, чтобы Excel корректно отображал русские символы
    echo "\xEF\xBB\xBF"; // Добавляем BOM для UTF-8

    // Создаем таблицу CSV
    // Открываем поток вывода
    $output = fopen('php://output', 'w');

    // Заголовок таблицы (можно оставить как есть или изменить)
    fputcsv($output, ['ID', 'Job', 'Equipment', 'Issue', 'Workshop', 'Root Cause']);

    // Данные таблицы
    foreach ($root_causes as $row) {
        // Выводим строку данных
        fputcsv($output, [
            $row['id'], 
            cleanExcelData($row['job']), 
            cleanExcelData($row['equipment']), 
            cleanExcelData($row['issue']), 
            cleanExcelData($row['workshop']), 
            cleanExcelData($row['root_cause'])
        ]);
    }

    // Закрываем поток
    fclose($output);
    exit();
}

// Функция для очистки данных перед экспортом в CSV (удаляем или заменяем табуляцию)
function cleanExcelData($data) {
    // Убираем символы табуляции, новые строки и возвращаем пробелы вместо них
    return str_replace(["\t", "\r", "\n"], " ", $data);
}

// Проверка параметра для скачивания CSV
if (isset($_GET['download_csv']) && $_GET['download_csv'] == 'true') {
    downloadCSV($pdo);
}

// Добавление нового пользователя (без хеширования пароля)
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Пароль сохраняем в открытом виде
    $role = $_POST['role'];

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);
}

// Изменение данных пользователя
if (isset($_POST['edit_user'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
    $stmt->execute([$username, $password, $role, $id]);
}

// Удаление пользователя
if (isset($_GET['delete_user'])) {
    $id = $_GET['delete_user'];

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

// Получаем список всех пользователей
$stmt = $pdo->query("SELECT id, username, password, role FROM users");
$users = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора</title>
    <link rel="stylesheet" href="cssAdminPanel.css">
</head>
<body>
    <div class="container">
        <h1>Панель администратора</h1>

        <!-- Сообщение о подключении к базе данных -->
        <?php if ($connection_status): ?>
            <div class="connection-status"><?php echo $connection_status; ?></div>
        <?php endif; ?>
        
        <!-- Кнопка для скачивания таблицы root_causes в CSV -->
        <a href="?download_csv=true" class="button">Скачать таблицу "Корневые причины простоя" в CSV</a>

        <br><br>

        <!-- Таблица с пользователями -->
        <h2>Выберите пользователя</h2>
        <table>
            <thead>
                <tr>
                    <th>Имя пользователя</th>
                    <th>Пароль</th>
                    <th>Роль</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="user_select" id="user_select" onchange="loadUserData()">
                            <option value="">Выберите пользователя</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><span id="password"></span></td>
                    <td><span id="role"></span></td>
                    <td>
                        <span class="action-divider"></span>
                        <a href="" id="delete_user" class="delete-button" onclick="return confirm('Вы уверены, что хотите удалить этого пользователя?')">Удалить</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <br><br>

        <!-- Добавление нового пользователя -->
        <h2>Добавить нового пользователя</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <select name="role">
                <option value="user">Пользователь</option>
                <option value="admin">Администратор</option>
            </select>
            <button type="submit" name="add_user">Добавить пользователя</button>
        </form>

        <br><br>

        <!-- Кнопка возврата -->
        <a href="index.php">Вернуться на главную</a>
    </div>

    <script>
        function loadUserData() {
            var userId = document.getElementById('user_select').value;

            if (userId) {
                // Найдем выбранного пользователя и обновим данные
                <?php foreach ($users as $user): ?>
                    if (userId == <?php echo $user['id']; ?>) {
                        document.getElementById('password').textContent = "<?php echo $user['password']; ?>";
                        document.getElementById('role').textContent = "<?php echo $user['role']; ?>";
                        document.getElementById('delete_user').href = "?delete_user=<?php echo $user['id']; ?>";
                    }
                <?php endforeach; ?>
            }
        }
    </script>
</body>
</html>
