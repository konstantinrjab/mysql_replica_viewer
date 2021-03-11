<?php

declare(strict_types=1);

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$server = new \Bloatless\WebSocket\Server('0.0.0.0', 8081, '/tmp/phpwss.sock');

$server->setCheckOrigin(false);
//$server->setAllowedOrigin('example.com');

$client = \App\SocketClient::getInstance();
$server->registerApplication('client', $client);

$server->addTimer(2000, function () use ($client) {
    if ($client->hasActiveConnections()) {
        $client->notify([
            'master_count' => \App\MysqlMonitor::getCountMaster('table_1'),
            'slave_count' => \App\MysqlMonitor::getCountSlave('table_1'),
        ]);
    }
});

$server->run();
