<?php

namespace App;

use PDO;
use PDOException;

class MysqlConnector
{
    private function __construct()
    {

    }

    public static function getMaster(): PDO
    {
        return self::getConnection($_ENV['MASTER_HOST'], $_ENV['MASTER_ROOT_PASS']);
    }

    public static function getSlave(): PDO
    {
        return self::getConnection($_ENV['SLAVE_HOST'], $_ENV['MASTER_SLAVE_PASS']);
    }

    private static function getConnection(string $host, string $password): PDO
    {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=db", 'root', $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage();
            die();
        }

        return $pdo;
    }
}
