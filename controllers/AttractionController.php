<?php
require_once __DIR__ . '/../models/AttractionModel.php';

class AttractionController {
    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new AttractionModel();
    }

    // --- PARTIE PUBLIQUE ---

    public function lister() {
        $current_univers = isset($_GET['univers']) ? $_GET['univers'] : null;
        $attractions = $this->model->getAttractions($current_univers);
        require_once 'views/attractions.php';
    }

    public function detail($id) {
        $attraction = $this->model->getAttractionById($id);
        if (!$attraction) {
            $this->erreur("L'attraction demandée n'existe pas ou a été fermée définitivement.");
            return;
        }
        require_once 'views/attraction_detail.php';
    }

    public function erreur($message) {
        $message_erreur = $message;
        header("HTTP/1.0 404 Not Found");
        require_once 'views/error.php';
    }

    // --- SÉCURITÉ ADMIN ---
    private function checkAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }
    }

    // --- PARTIE CRUD ADMIN ---

    // 1. Lister les attractions pour l'admin
    public function adminLister() {
        $this->checkAdmin();
        $attractions = $this->model->getAttractions();
        require_once 'views/admin_attractions.php';
    }

    // 2. Afficher le formulaire d'ajout
    public function adminCreerForm() {
        $this->checkAdmin();
        require_once 'views/admin_attraction_form.php';
    }

    // 3. Traiter l'ajout en BDD
    public function adminCreerTraitement() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $univers_id = (int)$_POST['univers_id'];
            $image = trim($_POST['image']);
            $gag = trim($_POST['gag']);
            $description = trim($_POST['description']);
            $caracteristiques = trim($_POST['caracteristiques']);
            $attente = trim($_POST['attente']);
            $taille_minimale = (int)$_POST['taille_minimale'];
            $statut = $_POST['statut'];

            $this->model->addAttraction($nom, $image, $description, $gag, $caracteristiques, $attente,$taille_minimale, $statut, $univers_id);
            
            header('Location: index.php?action=admin_attractions');
            exit();
        }
    }

    // 4. Afficher le formulaire de modification avec les données existantes
    public function adminModifierForm($id) {
        $this->checkAdmin();
        $attraction = $this->model->getAttractionById($id);
        
        if (!$attraction) {
            $this->erreur("Impossible de modifier : l'attraction est introuvable.");
            return;
        }

        require_once 'views/admin_attraction_form.php';
    }

    // 5. Traiter la modification en BDD
    public function adminModifierTraitement($id) {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $univers_id = (int)$_POST['univers_id'];
            $image = trim($_POST['image']);
            $gag = trim($_POST['gag']);
            $description = trim($_POST['description']);
            $caracteristiques = trim($_POST['caracteristiques']);
            $attente = trim($_POST['attente']);
            $taille_minimale = (int)$_POST['taille_minimale'];
            $statut = $_POST['statut'];

            $this->model->updateAttraction($id, $nom, $image, $description, $gag, $caracteristiques, $attente, $taille_minimale, $statut, $univers_id);

            header('Location: index.php?action=admin_attractions');
            exit();
        }
    }

    // 6. Supprimer une attraction
    public function adminSupprimer($id) {
        $this->checkAdmin();
        $this->model->deleteAttraction($id);
        
        header('Location: index.php?action=admin_attractions');
        exit();
    }

    // Afficher la page en direct des temps d'attente
    public function pageLive() {
        require_once 'views/attractions_live.php';
    }

    // API JSON pour récupérer les temps en live (appelée par JavaScript)
    public function apiLive() {
        header('Content-Type: application/json');
        $attractions = $this->model->getTempsAttenteLive();
        echo json_encode([
            'derniere_maj' => date('H:i:s'),
            'attractions' => $attractions
        ]);
        exit();
    }

    public function afficherCarteInteractif() {
        // Récupère toutes les attractions depuis le modèle
        $attractions = $this->model->getAttractions(); 
        require_once 'views/carte_interactive.php';
    }
}