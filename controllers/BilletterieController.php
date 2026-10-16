<?php
require_once __DIR__ . '/../models/BilletterieModel.php';

class BilletterieController {
    private $model;
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new BilletterieModel();
        $this->db = Database::getConnection();
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }

    public function afficherFormulaire() {
        $this->checkAuth();
        $tarifsDB = $this->model->getTarifs();
        $today = date('Y-m-d');
        
        // Récupérer la date choisie dans l'URL (si l'utilisateur a changé la date)
        $date_visite = isset($_GET['date_visite']) ? $_GET['date_visite'] : $today;
        if ($date_visite < $today) {
            $date_visite = $today;
        }

        // Calculer les prix dynamiques pour chaque tarif en fonction de cette date
        $tarifs = [];
        foreach ($tarifsDB as $t) {
            // Utilisation de votre méthode de prix dynamique basée sur les places restantes
            $prixDynamique = $this->model->getPrixDynamiqueParPlacesRestantes($t['id'], $t['prix'], $date_visite);
            
            $tarifs[] = [
                'id' => $t['id'],
                'libelle' => $t['libelle'],
                'description' => $t['description'],
                'prix' => $prixDynamique // On injecte le prix dynamique calculé !
            ];
        }

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

            $tarifsDB = $this->model->getTarifs();
            $prix_total = 0.00;
            $billets_details = [];
            $total_tickets_demandés = 0;

            // 1. Vérification de la jauge globale (max 2000)
            $dejaVendus = $this->model->getNombreBilletsVendusPourDate($date_visite);
            $placesRestantes = 2000 - $dejaVendus;

            foreach ($tarifsDB as $t) {
                $id = $t['id'];
                $qty = isset($quantites[$id]) ? (int)$quantites[$id] : 0;
                if ($qty > 0) {
                    $total_tickets_demandés += $qty;
                }
            }

            if ($total_tickets_demandés <= 0) {
                $erreur = "Veuillez sélectionner au moins 1 billet.";
                $tarifs = $tarifsDB;
                require_once 'views/billetterie.php';
                return;
            }

            if ($total_tickets_demandés > $placesRestantes) {
                $erreur = "Désolé ! Il ne reste plus que $placesRestantes place(s) disponible(s) pour le " . date('d/m/Y', strtotime($date_visite)) . " (Maximum 2000 visiteurs par jour).";
                $tarifs = $tarifsDB;
                require_once 'views/billetterie.php';
                return;
            }

            // 2. Calcul du prix dynamique basé sur les places restantes pour chaque ligne
            foreach ($tarifsDB as $t) {
                $id = $t['id'];
                $qty = isset($quantites[$id]) ? (int)$quantites[$id] : 0;
                
                if ($qty > 0) {
                    // Appel de la règle dynamique selon les places restantes
                    $prix_unitaire_dynamique = $this->model->getPrixDynamiqueParPlacesRestantes($id, $t['prix'], $date_visite);
                    $sous_total = $qty * $prix_unitaire_dynamique;
                    
                    $prix_total += $sous_total;
                    
                    $billets_details[] = [
                        'tarif_id' => $id,
                        'libelle' => $t['libelle'],
                        'quantite' => $qty,
                        'prix_unitaire' => $prix_unitaire_dynamique, // Prix unitaire ajusté selon la rareté
                        'sous_total' => $sous_total
                    ];
                }
            }

            // Stockage temporaire en session pour la page de paiement
            $_SESSION['panier_resa'] = [
                'date_visite' => $date_visite,
                'prix_total' => $prix_total,
                'billets' => $billets_details
            ];

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

    // --- NOUVEAU : Afficher le rendu du mail de confirmation pour une commande  ---
    public function voirMail($reservation_id) {
        if (!isset($_SESSION['user_role'])) {
            header('Location: index.php?action=login');
            exit();
        }

        // 1. Récupération des détails de la réservation
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

        // 2. Récupération des e-billets réellement enregistrés en BDD avec leurs vrais codes uniques
        $e_billets_db = $this->model->getEBilletsByReservation($reservation_id);

        $e_billets = [];
        $index = 1;
        foreach ($e_billets_db as $eb) {
            $e_billets[] = [
                'libelle' => $eb['libelle'],
                'numero_billet' => $reservation_id . '-' . $index,
                'code_unique' => $eb['code_unique'] // Le VRAI code stocké en BDD !
            ];
            $index++;
        }

        // 3. Affichage du rendu HTML du mail
        require_once 'mails/email_confirmation.php';
        exit();
    }

    // Afficher la page de contrôle de billet (Admin)
    public function afficherControleBillet() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }
        require_once 'views/admin_controle_billet.php';
    }

    // Traiter la vérification du code unique du billet
    public function verifierBillet() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code_unique'] ?? '');
            $billet = $this->model->getBilletByCode($code);
            $today = date('Y-m-d');

            if (!$billet) {
                $message_resultat = "❌ ERREUR : Ce code unique n'existe pas dans la base de données.";
                $statut_classe = "erreur";
            } elseif ($billet['est_utilise'] == 1) {
                $message_resultat = "⚠️ ATTENTION : Ce billet a DÉJÀ ÉTÉ UTILISÉ le " . date('d/m/Y à H:i', strtotime($billet['date_utilisation'])) . " !";
                $statut_classe = "avertissement";
            } elseif ($billet['date_visite'] !== $today) {
                $date_formatee = date('d/m/Y', strtotime($billet['date_visite']));
                $message_resultat = "❌ BILLET INVALIDE POUR AUJOURD'HUI : Ce billet est prévu pour le **$date_formatee** (Aujourd'hui nous sommes le " . date('d/m/Y') . ").";
                $statut_classe = "erreur";
            } else {
                // Tout est valide ! On valide le billet en base (passage à est_utilise = 1)
                $this->model->validerBilletParCode($billet['id']);
                $message_resultat = "✅ VALIDÉ ! Entrée autorisée pour " . htmlspecialchars($billet['client_nom']) . " (" . htmlspecialchars($billet['tarif_libelle']) . ").";
                $statut_classe = "succes";
            }

            require_once 'views/admin_controle_billet.php';
            exit();
        }
    }
}