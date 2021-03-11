<?php

declare(strict_types=1);

require '../vendor/autoload.php';

$loop = React\EventLoop\Factory::create();

$timer = $loop->addPeriodicTimer(100, function () use ($loop) {
});

$httpServer = new React\Http\Server($loop, function (Psr\Http\Message\ServerRequestInterface $request) use ($loop, &$timer) {
    if ($request->getMethod() == 'GET') {
        return new React\Http\Message\Response(
            200,
            ['Content-Type' => 'text/html', 'Access-Control-Allow-Origin' => '*'],
            file_get_contents('../frontend/index.html')
        );
    }

    $body = $request->getParsedBody();
    if (!isset($body['load_value'])) {
        return new React\Http\Message\Response(
            400,
            ['Content-Type' => 'text/plain', 'Access-Control-Allow-Origin' => '*'],
            "Size not set\n"
        );
    }

    $loadValue = $body['load_value'];
    $loop->cancelTimer($timer);

    if ($loadValue) {
        $timer = $loop->addPeriodicTimer(0.5, function () use ($loop, $loadValue) {
            $process = new \React\ChildProcess\Process('php worker.php ' . $loadValue);
            $process->start($loop);
        });
    }

    return new React\Http\Message\Response(
        200,
        ['Content-Type' => 'text/plain', 'Access-Control-Allow-Origin' => '*'],
        "Set size to $loadValue\n"
    );
});

$httpSocket = new React\Socket\Server('0.0.0.0:8080', $loop);

$httpServer->listen($httpSocket);

echo "Server running\n";

$loop->run();
