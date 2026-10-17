<?php
/**
 * Modèle : ApiModel
 * Gère les données globales destinées à l'API ou aux services externes du parc Studio Toon
 * (exportation structurée des informations du parc et de ses attractions).
 */

require_once __DIR__ . '/../config/Database.php';

class ApiModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Récupère l'ensemble des données structurées du parc (informations générales et liste des attractions).
     * Vérifie que l'identifiant du parc demandé correspond bien au parc principal (ID 1).
     */
    public function getParcComplet($id) {
        // Validation de l'ID du parc (fixé à 1 pour Studio Toon)
        if ($id != 1) {
            return false;
        }

        // 1. Définition des informations générales du parc
        $parc = [
            'id_parc' => 1,
            'nom' => 'Studio Toon',
            'slogan' => "Ici, c'est toi le dessin animé.",
            'statut_general' => 'Ouvert'
        ];

        // 2. Récupération de toutes les attractions avec leur univers associé
        $stmt = $this->db->query("
            SELECT a.*, u.nom AS univers_nom 
            FROM attractions a 
            JOIN univers u ON a.univers_id = u.id
        ");
        $attractions = $stmt->fetchAll();

        // 3. Vérification de l'existence des données d'attractions
        if (empty($attractions)) {
            return false;
        }

        // Intégration de la liste des attractions dans le tableau global du parc
        $parc['attractions'] = $attractions;

        return $parc;
    }
}