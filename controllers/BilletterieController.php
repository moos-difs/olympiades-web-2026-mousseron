<?php
require_once __DIR__ . '/../models/BilletterieModel.php';

class BilletterieController {
    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new BilletterieModel();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }

    public function afficherFormulaire() {
        $this->checkAuth();
        $tarifs = $this->model->getTarifs();
        $today = date('Y-m-d');
        require_once 'views/billetterie.php';
    }

    // 1. Étape intermédiaire : Validation des quantités et vérification de la jauge (2000 max)
    public function preparerPaiement() {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date_visite = $_POST['date_visite'] ?? '';
            $quantites = $_POST['quantites'] ?? [];
            $today = date('Y-m-d');

            if (empty($date_visite) || $date_visite < $today) {
                $erreur = "Veuillez sélectionner une date de visite valide.";
                $tarifs = $this->model->getTarifs();
                require_once 'views/billetterie.php';
                return;
            }

            // Calcul du nombre de billets demandés et du prix total
            $tarifsDB = $this->model->getTarifs();
            $prix_total = 0.00;
            $billets_details = [];
            $total_tickets_demandés = 0;

            foreach ($tarifsDB as $t) {
                $id = $t['id'];
                $qty = isset($quantites[$id]) ? (int)$quantites[$id] : 0;
                if ($qty > 0) {
                    $total_tickets_demandés += $qty;
                    $sous_total = $qty * $t['prix'];
                    $prix_total += $sous_total;
                    $billets_details[] = [
                        'tarif_id' => $id,
                        'libelle' => $t['libelle'],
                        'quantite' => $qty,
                        'prix_unitaire' => $t['prix'],
                        'sous_total' => $sous_total
                    ];
                }
            }

            if ($total_tickets_demandés <= 0) {
                $erreur = "Veuillez sélectionner au moins 1 billet.";
                $tarifs = $tarifsDB;
                require_once 'views/billetterie.php';
                return;
            }

            // --- VÉRIFICATION DE LA JAUGE (MAX 2000 PLACES PAR JOUR) ---
            $dejaVendus = $this->model->getNombreBilletsVendusPourDate($date_visite);
            $placesRestantes = 2000 - $dejaVendus;

            if ($total_tickets_demandés > $placesRestantes) {
                $erreur = "Désolé ! Il ne reste plus que $placesRestantes place(s) disponible(s) pour le " . date('d/m/Y', strtotime($date_visite)) . " (Maximum 2000 visiteurs par jour).";
                $tarifs = $tarifsDB;
                require_once 'views/billetterie.php';
                return;
            }

            // Stockage temporaire en session pour la page de paiement sécurisée
            $_SESSION['panier_resa'] = [
                'date_visite' => $date_visite,
                'prix_total' => $prix_total,
                'billets' => $billets_details
            ];

            // Redirection vers la fausse page de paiement
            header('Location: index.php?action=paiement');
            exit();
        }
    }

    // 2. Affichage de la fausse page de paiement
    public function afficherPaiement() {
        $this->checkAuth();
        if (!isset($_SESSION['panier_resa'])) {
            header('Location: index.php?action=billetterie');
            exit();
        }
        $panier = $_SESSION['panier_resa'];
        require_once 'views/paiement.php';
    }

    // 3. Validation finale du paiement et enregistrement en BDD
    public function validerPaiement() {
        $this->checkAuth();
        if (!isset($_SESSION['panier_resa'])) {
            header('Location: index.php?action=billetterie');
            exit();
        }

        $panier = $_SESSION['panier_resa'];

        // Enregistrement définitif dans la base de données
        $reservation_id = $this->model->creerReservation(
            $_SESSION['user_id'],
            $panier['date_visite'],
            $panier['prix_total'],
            $panier['billets']
        );

        if ($reservation_id) {
            $recap = $panier;
            $recap['reservation_id'] = $reservation_id;
            
            // Nettoyage du panier en session
            unset($_SESSION['panier_resa']);

            require_once 'views/billetterie_recap.php';
        } else {
            $erreur = "Erreur critique lors de la validation du paiement.";
            require_once 'views/paiement.php';
        }
    }

    // 4. Consultation des réservations de l'utilisateur connecté
    public function mesReservations() {
        $this->checkAuth();
        $reservations = $this->model->getReservationsByUser($_SESSION['user_id']);
        require_once 'views/mes_reservations.php';
    }

    // 5. Consultation admin de toutes les réservations du parc
    public function adminReservations() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }
        $reservations = $this->model->getAllReservations();
        require_once 'views/admin_reservations.php';
    }

    // --- NOUVEAU : Afficher le rendu du mail de confirmation pour une commande (Admin) ---
    public function adminVoirMail($reservation_id) {
        // 1. Vérification des droits Admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }

        // 2. Récupérer les infos de la réservation et du client
        $stmt = $this->db->prepare("
            SELECT r.*, u.nom AS client_nom, u.email AS client_email 
            FROM reservations r 
            JOIN utilisateurs u ON r.utilisateur_id = u.id 
            WHERE r.id = ?
        ");
        $stmt->execute([$reservation_id]);
        $reservation = $stmt->fetch();

        if (!$reservation) {
            die("Réservation introuvable.");
        }

        $client_nom = $reservation['client_nom'];
        $date_visite = $reservation['date_visite'];
        $prix_total = $reservation['prix_total'];

        // 3. Récupérer les lignes de billets associés à la commande
        $stmtBillets = $this->db->prepare("
            SELECT rb.*, t.libelle 
            FROM reservation_billets rb 
            JOIN tarifs t ON rb.tarif_id = t.id 
            WHERE rb.reservation_id = ?
        ");
        $stmtBillets->execute([$reservation_id]);
        $lignes = $stmtBillets->fetchAll();

        // 4. Préparer le tableau des e-billets unitaires avec un code unique imprévisible par billet
        $e_billets = [];
        $numero = 1;
        foreach ($lignes as $ligne) {
            for ($i = 0; $i < $ligne['quantite']; $i++) {
                $e_billets[] = [
                    'libelle' => $ligne['libelle'],
                    'numero_billet' => $reservation_id . '-' . $numero,
                    // Génération d'un code unique imprévisible pour chaque billet
                    'code_unique' => strtoupper(bin2hex(random_bytes(4))) 
                ];
                $numero++;
            }
        }

        // 5. Inclure directement le template HTML du mail pour l'afficher à l'écran
        require_once 'views/email_confirmation.php';
        exit();
    }
}