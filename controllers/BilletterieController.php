<?php
/**
 * Contrôleur : BilletterieController
 * Gère l'intégralité du tunnel de réservation du parc Studio Toon : affichage des tarifs dynamiques,
 * vérification des jauges journalières (max 2000 places), gestion du panier, validation du paiement,
 * consultation de l'historique utilisateur, prévisualisation des e-mails et contrôle d'accès à l'entrée.
 */

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

    /**
     * Vérifie si l'utilisateur est connecté, sinon le redirige vers la page de connexion.
     */
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }
    }

    /**
     * Affiche le formulaire de billetterie avec calcul des prix dynamiques selon la date choisie.
     */
    public function afficherFormulaire() {
        $this->checkAuth();
        $tarifsDB = $this->model->getTarifs();
        $today = date('Y-m-d');
        
        // Récupère la date choisie dans l'URL ou utilise la date du jour par défaut
        $date_visite = isset($_GET['date_visite']) ? $_GET['date_visite'] : $today;
        if ($date_visite < $today) {
            $date_visite = $today;
        }

        // Calcule les prix dynamiques pour chaque tarif en fonction de la date et de la rareté
        $tarifs = [];
        foreach ($tarifsDB as $t) {
            $prixDynamique = $this->model->getPrixDynamiqueParPlacesRestantes($t['id'], $t['prix'], $date_visite);
            
            $tarifs[] = [
                'id' => $t['id'],
                'libelle' => $t['libelle'],
                'description' => $t['description'],
                'prix' => $prixDynamique
            ];
        }

        require_once __DIR__ . '/../views/billetterie.php';
    }

    /**
     * Étape 1 : Valide les quantités commandées et vérifie la jauge journalière (2000 places max).
     */
    public function preparerPaiement() {
        $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date_visite = $_POST['date_visite'] ?? '';
            $quantites = $_POST['quantites'] ?? [];
            $today = date('Y-m-d');

            if (empty($date_visite) || $date_visite < $today) {
                $erreur = "Veuillez sélectionner une date de visite valide.";
                $tarifs = $this->model->getTarifs();
                require_once __DIR__ . '/../views/billetterie.php';
                return;
            }

            $tarifsDB = $this->model->getTarifs();
            $prix_total = 0.00;
            $billets_details = [];
            $total_tickets_demandes = 0;

            // Vérification de la jauge globale (maximum 2000 places par jour)
            $dejaVendus = $this->model->getNombreBilletsVendusPourDate($date_visite);
            $placesRestantes = 2000 - $dejaVendus;

            foreach ($tarifsDB as $t) {
                $id = $t['id'];
                $qty = isset($quantites[$id]) ? (int)$quantites[$id] : 0;
                if ($qty > 0) {
                    $total_tickets_demandes += $qty;
                }
            }

            if ($total_tickets_demandes <= 0) {
                $erreur = "Veuillez sélectionner au moins 1 billet.";
                $tarifs = $tarifsDB;
                require_once __DIR__ . '/../views/billetterie.php';
                return;
            }

            if ($total_tickets_demandes > $placesRestantes) {
                $erreur = "Désolé ! Il ne reste plus que $placesRestantes place(s) disponible(s) pour le " . date('d/m/Y', strtotime($date_visite)) . " (Maximum 2000 visiteurs par jour).";
                $tarifs = $tarifsDB;
                require_once __DIR__ . '/../views/billetterie.php';
                return;
            }

            // Calcul du prix dynamique pour chaque ligne sélectionnée
            foreach ($tarifsDB as $t) {
                $id = $t['id'];
                $qty = isset($quantites[$id]) ? (int)$quantites[$id] : 0;
                
                if ($qty > 0) {
                    $prix_unitaire_dynamique = $this->model->getPrixDynamiqueParPlacesRestantes($id, $t['prix'], $date_visite);
                    $sous_total = $qty * $prix_unitaire_dynamique;
                    
                    $prix_total += $sous_total;
                    
                    $billets_details[] = [
                        'tarif_id' => $id,
                        'libelle' => $t['libelle'],
                        'quantite' => $qty,
                        'prix_unitaire' => $prix_unitaire_dynamique,
                        'sous_total' => $sous_total
                    ];
                }
            }

            // Stockage temporaire du panier en session pour l'étape de paiement
            $_SESSION['panier_resa'] = [
                'date_visite' => $date_visite,
                'prix_total' => $prix_total,
                'billets' => $billets_details
            ];

            header('Location: index.php?action=paiement');
            exit();
        }
    }

    /**
     * Étape 2 : Affiche la page de simulation de paiement.
     */
    public function afficherPaiement() {
        $this->checkAuth();
        if (!isset($_SESSION['panier_resa'])) {
            header('Location: index.php?action=billetterie');
            exit();
        }
        $panier = $_SESSION['panier_resa'];
        require_once __DIR__ . '/../views/paiement.php';
    }

    /**
     * Étape 3 : Valide le paiement et enregistre définitivement la réservation en base de données.
     */
    public function validerPaiement() {
        $this->checkAuth();
        if (!isset($_SESSION['panier_resa'])) {
            header('Location: index.php?action=billetterie');
            exit();
        }

        $panier = $_SESSION['panier_resa'];

        $reservation_id = $this->model->creerReservation(
            $_SESSION['user_id'],
            $panier['date_visite'],
            $panier['prix_total'],
            $panier['billets']
        );

        if ($reservation_id) {
            $recap = $panier;
            $recap['reservation_id'] = $reservation_id;
            
            // Nettoyage du panier en session après succès
            unset($_SESSION['panier_resa']);

            require_once __DIR__ . '/../views/billetterie_recap.php';
        } else {
            $erreur = "Erreur critique lors de la validation du paiement.";
            require_once __DIR__ . '/../views/paiement.php';
        }
    }

    /**
     * Affiche l'historique des réservations de l'utilisateur connecté.
     */
    public function mesReservations() {
        $this->checkAuth();
        $reservations = $this->model->getReservationsByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../views/mes_reservations.php';
    }

    /**
     * Affiche la liste de toutes les réservations du parc (réservé aux administrateurs).
     */
    public function adminReservations() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }
        $reservations = $this->model->getAllReservations();
        require_once __DIR__ . '/../views/admin_reservations.php';
    }

    /**
     * Affiche le rendu HTML de l'e-mail de confirmation pour une commande spécifique.
     */
    public function voirMail($reservation_id) {
        if (!isset($_SESSION['user_role'])) {
            header('Location: index.php?action=login');
            exit();
        }

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

        $e_billets_db = $this->model->getEBilletsByReservation($reservation_id);

        $e_billets = [];
        $index = 1;
        foreach ($e_billets_db as $eb) {
            $e_billets[] = [
                'libelle' => $eb['libelle'],
                'numero_billet' => $reservation_id . '-' . $index,
                'code_unique' => $eb['code_unique']
            ];
            $index++;
        }

        require_once __DIR__ . '/../mails/email_confirmation.php';
        exit();
    }

    /**
     * Affiche l'interface administrateur pour le contrôle des billets à l'entrée.
     */
    public function afficherControleBillet() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }
        require_once __DIR__ . '/../views/admin_controle_billet.php';
    }

    /**
     * Traite la vérification du code unique d'un billet scanné ou saisi à l'entrée.
     */
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
                // Billet valide : enregistrement de l'utilisation en base de données
                $this->model->validerBilletParCode($billet['id']);
                $message_resultat = "✅ VALIDÉ ! Entrée autorisée pour " . htmlspecialchars($billet['client_nom']) . " (" . htmlspecialchars($billet['tarif_libelle']) . ").";
                $statut_classe = "succes";
            }

            require_once __DIR__ . '/../views/admin_controle_billet.php';
            exit();
        }
    }
}