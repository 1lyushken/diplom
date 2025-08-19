<?php
// Получаем id пользователя из запроса
if (isset($_GET['id'])) {
    require 'db_connection.php';

    // Получаем данные пользователя из базы данных
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Отправляем данные пользователю в формате JSON
    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'Пользователь не найден']);
    }
}
?>
