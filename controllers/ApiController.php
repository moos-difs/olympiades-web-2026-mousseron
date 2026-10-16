<?php
require_once __DIR__ . '/../models/ApiModel.php';

class ApiController {
    private $apiModel;

    public function __construct() {
        $this->apiModel = new ApiModel();
    }

    public function apiGetData() {
        // Forcer l'en-tête en JSON avec encodage UTF-8
        header('Content-Type: application/json; charset=utf-8');

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
        
        // Récupération des données globales et des attractions
        $donneesParc = $this->apiModel->getParcComplet($id);

        // Si les données n'existent pas
        if (!$donneesParc) {
            http_response_code(404); // Code HTTP Not Found
            echo json_encode([
                'success' => false,
                'error_code' => 'PARC_NOT_FOUND',
                'message' => 'Les données du parc et des attractions sont introuvables.'
            ]);
            exit();
        }

        // Si tout est OK
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'data' => $donneesParc
        ]);
        exit();
    }
}