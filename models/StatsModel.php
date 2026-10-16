<?php
require_once __DIR__ . '/../config/Database.php';

class StatsModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getGlobalKPIs() {
        $stmt = $this->db->query("
            SELECT 
                (SELECT SUM(quantite) FROM reservation_billets) as total_billets,
                (SELECT SUM(prix_total) FROM reservations) as chiffre_affaires,
                (SELECT COUNT(*) FROM reservations) as total_reservations,
                (SELECT COUNT(*) FROM utilisateurs WHERE role = 'client') as total_clients
        ");
        return $stmt->fetch();
    }

    // --- Billets vendus et CA par jour ---
    public function getVentesEtCaParJour() {
        $stmt = $this->db->query("
            SELECT r.date_visite, 
                   SUM(rb.quantite) as total_billets_jour, 
                   SUM(rb.quantite * rb.prix_unitaire) as ca_jour
            FROM reservations r
            JOIN reservation_billets rb ON r.id = rb.reservation_id
            GROUP BY r.date_visite
            ORDER BY r.date_visite ASC
            LIMIT 10
        ");
        return $stmt->fetchAll();
    }

    // --- Temps d'attente moyen par attraction ---
    public function getTempsAttenteMoyenParAttraction() {
        $stmt = $this->db->query("
            SELECT nom, attente as temps_attente_actuel 
            FROM attractions 
            ORDER BY attente DESC
        ");
        return $stmt->fetchAll();
    }

    // --- Temps d'attente moyen par heure (si tu as une table d'historique des temps d'attente, ou simulation basée sur les plages horaires) ---
    public function getTempsAttenteParHeure() {
        // Exemple basé sur une table de log d'attente ou regroupement par tranche horaire
        $stmt = $this->db->query("
            SELECT HOUR(date_maj) as heure, AVG(temps_attente) as moyenne_attente
            FROM historique_attentes
            GROUP BY HOUR(date_maj)
            ORDER BY heure ASC
        ");
        return $stmt->fetchAll();
    }

    // (Conserve les anciennes méthodes getVentesParTarif, getStatsBilletsScannes, etc. si besoin)
    public function getVentesParTarif() {
        $stmt = $this->db->query("
            SELECT t.libelle, SUM(rb.quantite) as qte_vendue, SUM(rb.quantite * rb.prix_unitaire) as ca_tarif
            FROM reservation_billets rb
            JOIN tarifs t ON rb.tarif_id = t.id
            GROUP BY t.id, t.libelle
            ORDER BY qte_vendue DESC
        ");
        return $stmt->fetchAll();
    }

    public function getBilletsRestantsParJour() {
        $stmt = $this->db->query("
            SELECT r.date_visite, 
                   SUM(rb.quantite) as total_vendus,
                   (2000 - SUM(rb.quantite)) as total_restants
            FROM reservations r
            JOIN reservation_billets rb ON r.id = rb.reservation_id
            GROUP BY r.date_visite
            ORDER BY r.date_visite ASC
        ");
        return $stmt->fetchAll();
    }

    public function getComparatifVendusVsValidesParDate($date_selectionnee = null) {
        $sql = "
            SELECT r.date_visite, 
                   COUNT(eb.id) as total_vendus,
                   SUM(CASE WHEN eb.est_utilise = 1 THEN 1 ELSE 0 END) as total_valides
            FROM reservations r
            JOIN e_billets eb ON r.id = eb.reservation_id
        ";
        
        if ($date_selectionnee) {
            $sql .= " WHERE r.date_visite = ?";
        }
        
        $sql .= " GROUP BY r.date_visite ORDER BY r.date_visite ASC";

        $stmt = $this->db->prepare($sql);
        if ($date_selectionnee) {
            $stmt->execute([$date_selectionnee]);
        } else {
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    public function getDatesVisitesUniques() {
        $stmt = $this->db->query("SELECT DISTINCT date_visite FROM reservations ORDER BY date_visite ASC");
        return $stmt->fetchAll();
    }
}