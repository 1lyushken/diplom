<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $prompt = $data['prompt'];

    $url = 'http://localhost:5000/generate';
    $data = json_encode(['prompt' => $prompt]);

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => $data,
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    echo $response;
}
?>
