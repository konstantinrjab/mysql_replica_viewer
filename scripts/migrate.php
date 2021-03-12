<?php

declare(strict_types=1);

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$pdo = \App\MysqlConnector::getMaster();

$pdo->exec("CREATE TABLE table_1 (
     id INT AUTO_INCREMENT PRIMARY KEY,
     string VARCHAR(255) NOT NULL, 
     another_string VARCHAR(255) NOT NULL, 
     text TEXT NOT NULL,
     ref_id INT NOT NULL, 
     some_int INT NOT NULL, 
     another_int INT NOT NULL,
     INDEX (string),
     INDEX (another_string),
     INDEX (ref_id),
     INDEX (some_int),
     INDEX (another_int)
);");
