<?php
// Подключение к базе данных
include('db_connection.php');

// Получение данных из POST-запроса
$job = $_POST['job'];
$equipment = $_POST['equipment'];
$issue = $_POST['issue'];
$workshop = $_POST['workshop'];
$root_cause = $_POST['root_cause'];

// Проверка, что все поля заполнены
if (empty($job) || empty($equipment) || empty($issue) || empty($workshop) || empty($root_cause)) {
    echo json_encode(['success' => false, 'message' => 'Все поля должны быть заполнены.']);
    exit;
}

// Прямое создание SQL-запроса без привязки параметров
$sql = "INSERT INTO public.root_causes (job, equipment, issue, workshop, root_cause) 
        VALUES ('$job', '$equipment', '$issue', '$workshop', '$root_cause')";

// Выполнение запроса
if ($pdo->exec($sql)) {
    echo json_encode(['success' => true, 'message' => 'Корневая причина успешно сохранена!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при сохранении.']);
}
?>

