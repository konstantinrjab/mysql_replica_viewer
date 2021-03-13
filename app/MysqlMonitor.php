<?php

namespace App;

class MysqlMonitor
{
    public static function getCountMaster(string $tableName): int
    {
        $pdoMaster = MysqlConnector::getMaster();

        return $pdoMaster->query("SELECT COUNT(*) FROM $tableName")->fetchColumn();
    }

    public static function getCountSlaves(string $tableName): array
    {
        $counts = [];
        $number = 1;
        while (!empty($_ENV['SLAVE_HOST_' . $number])) {
            $pdoSlave = MysqlConnector::getSlave($number);
            $counts[$number] = $pdoSlave->query("SELECT COUNT(*) FROM $tableName")->fetchColumn();
            $number++;
        }

        return $counts;
    }
}
