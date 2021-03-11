<?php

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$size = print_r($_SERVER['argv'][1], true);
$pdo = \App\MysqlConnector::getMaster();

for ($i = 0; $i < $size; $i++) {
    $data = [
        'string' => uniqid(),
        'another_string' => uniqid(),
        'text' => file_get_contents('text.txt'),
        'ref_id' => rand(0, 999999),
        'some_int' => rand(0, 999999),
        'another_int' => rand(0, 999999),
    ];

    $sql = "INSERT INTO table_1 (string, another_string, text, ref_id, some_int, another_int) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($data));
}
