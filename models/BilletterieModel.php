<?php
require_once __DIR__ . '/../config/Database.php';

class BilletterieModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getTarifs() {
        $stmt = $this->db->query("SELECT * FROM tarifs ORDER BY id ASC");
        return $stmt->fetchAll();
    }

    // Compter le nombre total de billets déjà vendus pour une date spécifique
    public function getNombreBilletsVendusPourDate($date_visite) {
        $stmt = $this->db->prepare("
            SELECT SUM(rb.quantite) as total 
            FROM reservation_billets rb 
            JOIN reservations r ON rb.reservation_id = r.id 
            WHERE r.date_visite = ?
        ");
        $stmt->execute([$date_visite]);
        $result = $stmt->fetch();
        return $result['total'] ? (int)$result['total'] : 0;
    }

    public function creerReservation($utilisateur_id, $date_visite, $prix_total, $billets_details) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO reservations (utilisateur_id, date_visite, prix_total) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$utilisateur_id, $date_visite, $prix_total]);
            $reservation_id = $this->db->lastInsertId();

            $stmtLigne = $this->db->prepare("
                INSERT INTO reservation_billets (reservation_id, tarif_id, quantite, prix_unitaire) 
                VALUES (?, ?, ?, ?)
            ");

            foreach ($billets_details as $b) {
                if ($b['quantite'] > 0) {
                    $stmtLigne->execute([
                        $reservation_id,
                        $b['tarif_id'],
                        $b['quantite'],
                        $b['prix_unitaire']
                    ]);
                }
            }

            $this->db->commit();
            return $reservation_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Récupérer les réservations d'un utilisateur
    public function getReservationsByUser($utilisateur_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM reservations 
            WHERE utilisateur_id = ? 
            ORDER BY date_visite DESC
        ");
        $stmt->execute([$utilisateur_id]);
        return $stmt->fetchAll();
    }

    // Récupérer TOUTES les réservations (pour l'admin) avec le nom de l'utilisateur
    public function getAllReservations() {
        $stmt = $this->db->query("
            SELECT r.*, u.nom AS utilisateur_nom, u.email AS utilisateur_email 
            FROM reservations r 
            JOIN utilisateurs u ON r.utilisateur_id = u.id 
            ORDER BY r.date_visite DESC
        ");
        return $stmt->fetchAll();
    }
}