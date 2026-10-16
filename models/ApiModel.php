<?php
require_once __DIR__ . '/../config/Database.php';

class ApiModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getParcComplet($id) {
        // Par exemple, on fixe l'ID du parc à 1. 
        // S'il n'y a pas d'ID 1 ou si vous voulez restreindre, vous pouvez ajuster la condition.
        if ($id != 1) {
            return false; // Déclenchera l'erreur si l'ID du parc n'existe pas
        }

        // 1. Récupération des informations générales du parc
        $parc = [
            'id_parc' => 1,
            'nom' => 'Studio Toon',
            'slogan' => "Ici, c'est toi le dessin animé.",
            'statut_general' => 'Ouvert'
        ];

        // 2. Récupération de toutes les attractions avec leur univers (en suivant votre schéma BDD)
        $stmt = $this->db->query("
            SELECT a.*, u.nom AS univers_nom 
            FROM attractions a 
            JOIN univers u ON a.univers_id = u.id
        ");
        $attractions = $stmt->fetchAll();

        // 3. S'il n'y a aucune attraction ou aucun contenu, on peut considérer que les données n'existent pas
        if (empty($attractions)) {
            return false;
        }

        // On combine le tout dans un seul tableau de données
        $parc['attractions'] = $attractions;

        return $parc;
    }
}