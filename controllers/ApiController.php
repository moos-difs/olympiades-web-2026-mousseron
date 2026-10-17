<?php
/**
 * Contrôleur : ApiController
 * Gère les points de terminaison de l'API REST du parc Studio Toon
 * (exportation sécurisée des données globales du parc et de ses attractions au format JSON).
 */

require_once __DIR__ . '/../models/ApiModel.php';

class ApiController {
    private $apiModel;

    public function __construct() {
        $this->apiModel = new ApiModel();
    }

    /**
     * Fournit les données structurées du parc au format JSON.
     * Récupère l'identifiant dans les paramètres GET (par défaut : 1).
     */
    public function apiGetData() {
        // Forcer l'en-tête de réponse en JSON avec un encodage UTF-8 explicite
        header('Content-Type: application/json; charset=utf-8');

        // Récupération sécurisée de l'ID du parc (par défaut 1)
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
        
        // Récupération des données globales et de la liste des attractions via le modèle
        $donneesParc = $this->apiModel->getParcComplet($id);

        // Si le parc ou les attractions n'existent pas : retour d'une erreur 404
        if (!$donneesParc) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error_code' => 'PARC_NOT_FOUND',
                'message' => 'Les données du parc et des attractions sont introuvables.'
            ]);
            exit();
        }

        // Si tout est correct : retour des données avec un code HTTP 200
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $donneesParc
        ]);
        exit();
    }
}