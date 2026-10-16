<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/LiveServer.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\LiveServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new LiveServer()
        )
    ),
    8080
);

echo "Serveur WebSocket démarré sur le port 8080...\n";
$server->run();