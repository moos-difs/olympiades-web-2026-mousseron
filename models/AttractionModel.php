<?php
require_once __DIR__ . '/../config/Database.php';

class AttractionModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Récupérer toutes les attractions ou filtrer par univers
    public function getAttractions($univers = null) {
        if ($univers) {
            $stmt = $this->db->prepare("
                SELECT a.*, u.nom AS univers_nom 
                FROM attractions a 
                JOIN univers u ON a.univers_id = u.id 
                WHERE u.nom = ?
            ");
            $stmt->execute([$univers]);
            return $stmt->fetchAll();
        } else {
            $stmt = $this->db->query("
                SELECT a.*, u.nom AS univers_nom 
                FROM attractions a 
                JOIN univers u ON a.univers_id = u.id
            ");
            return $stmt->fetchAll();
        }
    }

    // Récupérer une attraction par son ID avec son univers
    public function getAttractionById($id) {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nom AS univers_nom 
            FROM attractions a 
            JOIN univers u ON a.univers_id = u.id 
            WHERE a.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}