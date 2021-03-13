<?php

namespace App;

use PDO;
use PDOException;

class MysqlConnector
{
    private static array $connections = [];

    private function __construct()
    {

    }

    public static function getMaster(): PDO
    {
        return self::getConnection($_ENV['MASTER_HOST'], $_ENV['MASTER_ROOT_PASS']);
    }

    public static function getSlave(int $number): PDO
    {
        return self::getConnection($_ENV['SLAVE_HOST_' . $number], $_ENV['MASTER_SLAVE_PASS_' . $number]);
    }

    private static function getConnection(string $host, string $password): PDO
    {
        if (!empty(self::$connections[$host])) {
            return self::$connections[$host];
        }

        try {
            $pdo = new PDO("mysql:host=$host;dbname=db", 'root', $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            echo 'PDO error: ' . $e->getMessage() . PHP_EOL;
            die();
        }
        self::$connections[$host] = $pdo;

        return self::$connections[$host];
    }
}
