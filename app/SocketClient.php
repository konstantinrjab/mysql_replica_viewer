<?php

namespace App;

use Bloatless\WebSocket\Application\Application;
use Bloatless\WebSocket\Connection;

class SocketClient extends Application
{
    private array $clients = [];

    /**
     * Handles new connections to the application.
     *
     * @param Connection $connection
     * @return void
     */
    public function onConnect(Connection $connection): void
    {
        $id = $connection->getClientId();
        $this->clients[$id] = $connection;
    }

    /**
     * Handles client disconnects.
     *
     * @param Connection $connection
     * @return void
     */
    public function onDisconnect(Connection $connection): void
    {
        $id = $connection->getClientId();
        unset($this->clients[$id]);
    }

    /**
     * Handles incomming data/requests.
     * If valid action is given the according method will be called.
     *
     * @param string $data
     * @param Connection $client
     * @return void
     */
    public function onData(string $data, Connection $client): void
    {
        try {
            $decodedData = $this->decodeData($data);

            // check if action is valid
            if ($decodedData['action'] !== 'echo') {
                return;
            }

            $message = $decodedData['data'] ?? '';
            if ($message === '') {
                return;
            }

            $message = uniqid();
            $this->actionEcho($message);
        } catch (\RuntimeException $e) {
            // @todo Handle/Log error
        }
    }

    /**
     * Handles data pushed into the websocket server using the push-client.
     *
     * @param array $data
     */
    public function onIPCData(array $data): void
    {
        $actionName = 'action' . ucfirst($data['action']);
        $message = 'System Message: ' . $data['data'] ?? '';
        if (method_exists($this, $actionName)) {
            call_user_func([$this, $actionName], $message);
        }
    }

    public function hasActiveConnections(): bool
    {
        return !empty($this->clients);
    }

    public function notify(array $data)
    {
        $encodedData = $this->encodeData('echo', $data);
        foreach ($this->clients as $receiver) {
            $receiver->send($encodedData);
        }
    }

    /**
     * Echoes data back to client(s).
     *
     * @param string $text
     * @return void
     */
    private function actionEcho(string $text): void
    {
        $encodedData = $this->encodeData('echo', $text);
        foreach ($this->clients as $sendto) {
            $sendto->send($encodedData);
        }
    }
}
