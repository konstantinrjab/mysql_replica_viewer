<?php

namespace App;

class MysqlMonitor
{
    public static function getCountMaster(string $tableName): int
    {
        $pdoMaster = MysqlConnector::getMaster();

        return $pdoMaster->query("SELECT COUNT(*) FROM $tableName")->fetchColumn();
    }

    public static function getCountSlave(string $tableName): int
    {
        $pdoSlave = MysqlConnector::getSlave();

        return $pdoSlave->query("SELECT COUNT(*) FROM $tableName")->fetchColumn();
    }
}
