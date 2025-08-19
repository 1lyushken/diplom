<?php
$host = 'localhost'; // Хост базы данных
$db = 'GPT'; // Имя базы данных
$user = 'postgres'; // Имя пользователя (по умолчанию это 'postgres')
$pass = '0000'; // Пароль для подключения
$charset = 'utf8'; // Кодировка

// DSN для подключения к PostgreSQL
$dsn = "pgsql:host=$host;dbname=$db;options='--client_encoding=$charset'";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Включение выброса исключений при ошибках
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // По умолчанию будем получать ассоциативный массив
    PDO::ATTR_EMULATE_PREPARES   => false, // Использование реальных подготовленных выражений
];

try {
    // Создаем подключение к базе данных с использованием PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Обработка ошибок подключения
    echo "Ошибка подключения: " . $e->getMessage();
}
?>
