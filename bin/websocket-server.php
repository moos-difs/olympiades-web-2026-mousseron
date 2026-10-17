<?php
/**
 * Script de Lancement : Serveur WebSocket (Ratchet)
 * Démarre le serveur en temps réel sur le port 8080 pour diffuser
 * instantanément les mises à jour des temps d'attente du parc Studio Toon.
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/LiveServer.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\LiveServer;

// Création et configuration du serveur WebSocket avec Ratchet
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new LiveServer()
        )
    ),
    8080
);

echo "🚀 Serveur WebSocket démarré avec succès sur le port 8080...\n";
echo "En attente de connexions en temps réel...\n";

// Lancement de la boucle d'événements du serveur
$server->run();