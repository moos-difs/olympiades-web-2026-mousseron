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

    // --- CRÉATION DE LA RÉSERVATION + GÉNÉRATION ET STOCKAGE DES E-BILLETS UNIQUES ---
    public function creerReservation($utilisateur_id, $date_visite, $prix_total, $billets_details) {
        try {
            $this->db->beginTransaction();

            // 1. Insertion de la réservation globale
            $stmt = $this->db->prepare("
                INSERT INTO reservations (utilisateur_id, date_visite, prix_total) 
                VALUES (?, ?, ?)
            ");
            $stmt->execute([$utilisateur_id, $date_visite, $prix_total]);
            $reservation_id = $this->db->lastInsertId();

            // 2. Préparation des requêtes pour les lignes et les e-billets
            $stmtLigne = $this->db->prepare("
                INSERT INTO reservation_billets (reservation_id, tarif_id, quantite, prix_unitaire) 
                VALUES (?, ?, ?, ?)
            ");

            $stmtEBillet = $this->db->prepare("
                INSERT INTO e_billets (reservation_id, tarif_id, code_unique) 
                VALUES (?, ?, ?)
            ");

            foreach ($billets_details as $b) {
                if ($b['quantite'] > 0) {
                    // Enregistrement de la ligne de commande
                    $stmtLigne->execute([
                        $reservation_id,
                        $b['tarif_id'],
                        $b['quantite'],
                        $b['prix_unitaire']
                    ]);

                    // Génération d'UN e-billet individuel avec son code unique pour CHAQUE place achetée
                    for ($i = 0; $i < $b['quantite']; $i++) {
                        $code_unique = strtoupper(bin2hex(random_bytes(4))); // Ex: '9A2F8E1C'
                        $stmtEBillet->execute([
                            $reservation_id,
                            $b['tarif_id'],
                            $code_unique
                        ]);
                    }
                }
            }

            $this->db->commit();
            return $reservation_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Récupérer tous les e-billets générés pour une réservation (pour la vue mail)
    public function getEBilletsByReservation($reservation_id) {
        $stmt = $this->db->prepare("
            SELECT eb.*, t.libelle 
            FROM e_billets eb
            JOIN tarifs t ON eb.tarif_id = t.id
            WHERE eb.reservation_id = ?
        ");
        $stmt->execute([$reservation_id]);
        return $stmt->fetchAll();
    }

    // Récupérer un e-billet par son code unique (pour le contrôle à l'entrée)
    public function getBilletByCode($code_unique) {
        $stmt = $this->db->prepare("
            SELECT eb.*, r.date_visite, t.libelle AS tarif_libelle, u.nom AS client_nom 
            FROM e_billets eb
            JOIN reservations r ON eb.reservation_id = r.id
            JOIN tarifs t ON eb.tarif_id = t.id
            JOIN utilisateurs u ON r.utilisateur_id = u.id
            WHERE eb.code_unique = ?
        ");
        $stmt->execute([strtoupper(trim($code_unique))]);
        return $stmt->fetch();
    }

    // Marquer un billet comme utilisé à l'entrée du parc
    public function validerBilletParCode($id) {
        $stmt = $this->db->prepare("
            UPDATE e_billets 
            SET est_utilise = 1, date_utilisation = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    public function getReservationsByUser($utilisateur_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM reservations 
            WHERE utilisateur_id = ? 
            ORDER BY date_visite DESC
        ");
        $stmt->execute([$utilisateur_id]);
        return $stmt->fetchAll();
    }

    public function getAllReservations() {
        $stmt = $this->db->query("
            SELECT r.*, u.nom AS utilisateur_nom, u.email AS utilisateur_email 
            FROM reservations r 
            JOIN utilisateurs u ON r.utilisateur_id = u.id 
            ORDER BY r.date_visite DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Calcule le prix dynamique basé sur les places restantes (Capacité max : 2000 places)
     */
    public function getPrixDynamiqueParPlacesRestantes($tarif_id, $prix_de_base, $date_visite) {
        $capaciteMax = 2000;
        $dejaVendus = $this->getNombreBilletsVendusPourDate($date_visite);
        $placesRestantes = max(0, $capaciteMax - $dejaVendus);

        // Règle d'augmentation selon la rareté :
        // - S'il reste plus de 500 places : Prix normal (multiplicateur 1.0)
        // - Entre 201 et 500 places restantes : +20% (multiplicateur 1.2)
        // - Entre 51 et 200 places restantes : +40% (multiplicateur 1.4)
        // - 50 places restantes ou moins (les dernières) : +75% (multiplicateur 1.75)
        
        $multiplicateur = 1.0;
        if ($placesRestantes <= 50) {
            $multiplicateur = 1.75; // Les 50 dernières places coûtent beaucoup plus cher
        } elseif ($placesRestantes <= 200) {
            $multiplicateur = 1.40;
        } elseif ($placesRestantes <= 500) {
            $multiplicateur = 1.20;
        }

        return round($prix_de_base * $multiplicateur, 2);
    }
}