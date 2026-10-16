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

    private function checkAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }
    }

    public function adminLister() {
        $this->checkAdmin();
        $attractions = $this->model->getAttractions();
        require_once 'views/admin_attractions.php';
    }

    public function adminCreerForm() {
        $this->checkAdmin();
        require_once 'views/admin_attraction_form.php';
    }

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

            $this->model->addAttraction($nom, $image, $description, $gag, $caracteristiques, $attente, $taille_minimale, $statut, $univers_id);
            
            header('Location: index.php?action=admin_attractions');
            exit();
        }
    }

    public function adminModifierForm($id) {
        $this->checkAdmin();
        $attraction = $this->model->getAttractionById($id);
        
        if (!$attraction) {
            $this->erreur("Impossible de modifier : l'attraction est introuvable.");
            return;
        }

        require_once 'views/admin_attraction_form.php';
    }

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

            // 1. Récupérer l'ancien temps d'attente avant modification
            // (Selon votre architecture, vous pouvez passer par votre modèle ou un accès direct au PDO)
            $ancienTemps = null;
            if (method_exists($this->model, 'getAttractionById')) {
                $attractionActuelle = $this->model->getAttractionById($id);
                $ancienTemps = $attractionActuelle['attente'] ?? null;
            }

            // 2. Mettre à jour l'attraction
            $this->model->updateAttraction($id, $nom, $image, $description, $gag, $caracteristiques, $attente, $taille_minimale, $statut, $univers_id);

            // 3. Enregistrer dans la table de log uniquement si le temps a été manipulé/modifié par l'admin
            if (method_exists($this->model, 'logAdminAttenteModification')) {
                $this->model->logAdminAttenteModification($id, $ancienTemps, $attente, $_SESSION['user_id'] ?? null);
            }

            // --- NOTIFICATION WEBSOCKET EN TEMPS RÉEL ---
            $donneesActu = json_encode([
                'type' => 'UPDATE_ATTRACTION',
                'id' => $id,
                'nom' => $nom,
                'attente' => $attente,
                'statut' => $statut
            ]);

            try {
                $sp = @fsockopen("127.0.0.1", 8080, $errno, $errstr, 1);
                if ($sp) {
                    fwrite($sp, $donneesActu);
                    fclose($sp);
                }
            } catch (Exception $e) {
                // Silencieux si le serveur WebSocket n'est pas démarré
            }

            header('Location: index.php?action=admin_attractions');
            exit();
        }
    }

    public function adminSupprimer($id) {
        $this->checkAdmin();
        $this->model->deleteAttraction($id);
        
        header('Location: index.php?action=admin_attractions');
        exit();
    }

    public function pageLive() {
        require_once 'views/attractions_live.php';
    }

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
        // On récupère toutes les attractions depuis le modèle
        $attractions = $this->model->getAttractions(); 
        require_once 'views/carte_interactive.php';
    }

    public function planificateur() {
        // Récupération de la taille en mètres (ex: 1.20)
        $taille_visiteur_metres = isset($_GET['taille_visiteur']) ? (float)str_replace(',', '.', $_GET['taille_visiteur']) : 1.20;
        
        // On convertit la taille du visiteur en centimètres pour correspondre à la BDD (ex: 1.20m -> 120cm)
        $taille_visiteur_cm = $taille_visiteur_metres * 100;

        $toutesAttractions = $this->model->getAttractions();

        $parcours = array_filter($toutesAttractions, function($att) use ($taille_visiteur_cm) {
            // Vérification du statut (doit être 'Ouvert')
            $estOuvert = (strcasecmp(trim($att['statut'] ?? ''), 'Ouvert') === 0);
            
            // Taille minimale en BDD (ex: 120, 90, 130)
            $tailleMinDb = (float)($att['taille_minimale'] ?? 0);

            // Le visiteur en cm doit être >= à la taille minimale en cm
            $tailleOk = $taille_visiteur_cm >= $tailleMinDb;

            return $estOuvert && $tailleOk;
        });

        // Tri par temps d'attente croissant (du plus court au plus long)
        usort($parcours, function($a, $b) {
            return ((int)($a['attente'] ?? 0)) <=> ((int)($b['attente'] ?? 0));
        });

        require_once 'views/planificateur.php';
    }
}