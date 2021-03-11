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
        return self::getConnection($_ENV['MASTER_HOST']);
    }

    public static function getSlave(): PDO
    {
        return self::getConnection($_ENV['SLAVE_HOST']);
    }

    private static function getConnection(string $host): PDO
    {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=db", 'user', 'password', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage();
            die();
        }

        return $pdo;
    }
}
