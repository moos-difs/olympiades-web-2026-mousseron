<?php
/**
 * Modèle : AttractionModel
 * Gère l'ensemble des opérations CRUD et de suivi pour les attractions du parc Studio Toon
 * (récupération par univers ou ID, gestion des statuts, temps d'attente en direct et logs d'administration).
 */

require_once __DIR__ . '/../config/Database.php';

class AttractionModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Récupère toutes les attractions ou filtre la liste selon un univers spécifique.
     */
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

    /**
     * Récupère une attraction spécifique par son identifiant avec les informations de son univers.
     */
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

    /**
     * Ajoute une nouvelle attraction dans la base de données.
     */
    public function addAttraction($nom, $image, $description, $gag, $caracteristiques, $attente, $taille_minimale, $statut, $univers_id) {
        $stmt = $this->db->prepare("
            INSERT INTO attractions (nom, image, description, gag, caracteristiques, taille_minimale, statut, univers_id, attente) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$nom, $image, $description, $gag, $caracteristiques, $taille_minimale, $statut, $univers_id, $attente]);
    }

    /**
     * Met à jour les informations d'une attraction existante.
     */
    public function updateAttraction($id, $nom, $image, $description, $gag, $caracteristiques, $attente, $taille_minimale, $statut, $univers_id) {
        $stmt = $this->db->prepare("
            UPDATE attractions 
            SET nom = ?, image = ?, description = ?, gag = ?, caracteristiques = ?, attente = ?, taille_minimale = ?, statut = ?, univers_id = ?
            WHERE id = ?
        ");
        return $stmt->execute([$nom, $image, $description, $gag, $caracteristiques, $attente, $taille_minimale, $statut, $univers_id, $id]);
    }

    /**
     * Supprime une attraction de la base de données par son ID.
     */
    public function deleteAttraction($id) {
        $stmt = $this->db->prepare("DELETE FROM attractions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Récupère les informations en direct (ID, nom, temps d'attente et statut) pour l'API Live.
     */
    public function getTempsAttenteLive() {
        $stmt = $this->db->query("SELECT id, nom, attente, statut FROM attractions");
        return $stmt->fetchAll();
    }

    /**
     * Récupère l'intégralité des attractions avec le libellé de l'univers (utilisé pour les vues admin).
     */
    public function getAllAttractions() {
        $stmt = $this->db->query("
            SELECT a.*, u.libelle AS univers_nom 
            FROM attractions a 
            LEFT JOIN univers u ON a.univers_id = u.id
        ");
        return $stmt->fetchAll();
    }

    /**
     * Enregistre n'importe quelle action administrative dans le journal centralisé.
     */
    public function logAdminAction($adminId, $action, $details = null) {
        $stmt = $this->db->prepare("
            INSERT INTO admin_logs (admin_id, action, details, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        return $stmt->execute([$adminId, $action, $details]);
    }

    /**
     * Récupère la liste des 100 derniers logs d'audit administrateur.
     * 
     * @return array Liste des logs triée du plus récent au plus ancien
     */
    public function getTousLesLogs(): array {
        $stmt = $this->db->query("
            SELECT l.*, u.nom AS admin_nom, u.email AS admin_email 
            FROM admin_logs l 
            LEFT JOIN utilisateurs u ON l.admin_id = u.id 
            ORDER BY l.created_at DESC 
            LIMIT 100
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}