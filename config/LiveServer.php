<?php
/**
 * Classe : LiveServer
 * Gère le serveur WebSocket en temps réel (basé sur la bibliothèque Ratchet)
 * pour diffuser instantanément les mises à jour des temps d'attente des attractions aux clients connectés.
 */

namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use \SplObjectStorage;
use \Exception;

class LiveServer implements MessageComponentInterface {
    protected $clients;

    /**
     * Initialise le stockage objet pour l'ensemble des connexions clients actives.
     */
    public function __construct() {
        $this->clients = new SplObjectStorage;
    }

    /**
     * Déclenché lors de l'établissement d'une nouvelle connexion WebSocket.
     */
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nouvelle connexion client ! ({$conn->resourceId})\n";
    }

    /**
     * Déclenché lors de la réception d'un message (rediffuse l'information à tous les clients connectés).
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            // Optionnel : si on souhaite ne pas renvoyer l'écho à l'expéditeur, on peut ajouter une condition ($client !== $from)
            $client->send($msg);
        }
    }

    /**
     * Déclenché lors de la fermeture d'une connexion client.
     */
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connexion {$conn->resourceId} fermée\n";
    }

    /**
     * Déclenché en cas d'erreur sur un socket : affiche l'erreur et ferme la connexion.
     */
    public function onError(ConnectionInterface $conn, Exception $e) {
        echo "Erreur survenue : {$e->getMessage()}\n";
        $conn->close();
    }
}