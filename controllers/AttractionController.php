<?php
/**
 * Contrôleur : AttractionController
 * Gère l'intégralité du catalogue des attractions du parc Studio Toon :
 * listes publiques, fiches détails, opérations CRUD d'administration, journalisation des modifications,
 * notifications WebSocket en temps réel, API live, carte interactive et planificateur de visite.
 */

require_once __DIR__ . '/../models/AttractionModel.php';

class AttractionController {
    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new AttractionModel();
    }

    /**
     * Affiche la liste publique des attractions (avec filtre optionnel par univers).
     */
    public function lister() {
        $current_univers = isset($_GET['univers']) ? $_GET['univers'] : null;
        $attractions = $this->model->getAttractions($current_univers);
        require_once __DIR__ . '/../views/attractions.php';
    }

    /**
     * Affiche la fiche détaillée d'une attraction spécifique.
     */
    public function detail($id) {
        $attraction = $this->model->getAttractionById($id);
        if (!$attraction) {
            $this->erreur("L'attraction demandée n'existe pas ou a été fermée définitivement.");
            return;
        }
        require_once __DIR__ . '/../views/attraction_detail.php';
    }

    /**
     * Affiche la page d'erreur personnalisée.
     */
    public function erreur($message) {
        $message_erreur = $message;
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . '/../views/error.php';
    }

    /**
     * Vérifie si l'utilisateur connecté possède le rôle administrateur.
     */
    private function checkAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit();
        }
    }

    /**
     * Affiche le tableau de bord administrateur des attractions (CRUD).
     */
    public function adminLister() {
        $this->checkAdmin();
        $attractions = $this->model->getAttractions();
        require_once __DIR__ . '/../views/admin_attractions.php';
    }

    /**
     * Affiche le formulaire de création d'une attraction (Admin).
     */
    public function adminCreerForm() {
        $this->checkAdmin();
        require_once __DIR__ . '/../views/admin_attraction_form.php';
    }

    /**
     * Traite l'ajout d'une nouvelle attraction en base de données (Admin).
     */
    public function adminCreerTraitement() {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $univers_id = (int)($_POST['univers_id'] ?? 1);
            $image = trim($_POST['image'] ?? '');
            $gag = trim($_POST['gag'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $caracteristiques = trim($_POST['caracteristiques'] ?? '');
            $attente = trim($_POST['attente'] ?? '0');
            $taille_minimale = (int)($_POST['taille_minimale'] ?? 120);
            $statut =$_POST['statut'] ?? 'Ouvert';

            $this->model->addAttraction($nom, $image,$description, $gag,$caracteristiques, $attente,$taille_minimale, $statut,$univers_id);
            
            // Journalisation de la création dans admin_logs
            if (method_exists($this->model, 'logAdminAction')) {
                $this->model->logAdminAction($_SESSION['user_id'] ?? 1, 'CREATION_ATTRACTION', "Création de l'attraction : « {$nom} »");
            }

            header('Location: index.php?action=admin_attractions');
            exit();
        }
    }

    /**
     * Affiche le formulaire de modification d'une attraction existante (Admin).
     */
    public function adminModifierForm($id) {
        $this->checkAdmin();$attraction = $this->model->getAttractionById($id);
        
        if (!$attraction) {$this->erreur("Impossible de modifier : l'attraction est introuvable.");
            return;
        }

        require_once __DIR__ . '/../views/admin_attraction_form.php';
    }

    /**
     * Traite la modification d'une attraction, enregistre les logs, notifie via WebSocket et redirige (Admin).
     */
    public function adminModifierTraitement($id) {$this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $univers_id = (int)($_POST['univers_id'] ?? 1);
            $image = trim($_POST['image'] ?? '');
            $gag = trim($_POST['gag'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $caracteristiques = trim($_POST['caracteristiques'] ?? '');
            $attente = trim($_POST['attente'] ?? '0');
            $taille_minimale = (int)($_POST['taille_minimale'] ?? 120);
            $statut =$_POST['statut'] ?? 'Ouvert';

            // 1. Récupération de l'ancien temps d'attente avant modification
            $ancienTemps = null;
            if (method_exists($this->model, 'getAttractionById')) {$attractionActuelle = $this->model->getAttractionById($id);
                $ancienTemps =$attractionActuelle['attente'] ?? null;
            }

            // 2. Mise à jour de l'attraction en base de données
            $this->model->updateAttraction($id,$nom, $image,$description, $gag,$caracteristiques, $attente,$taille_minimale, $statut,$univers_id);

            // 3. Journalisation centralisée de la modification dans admin_logs
            if (method_exists($this->model, 'logAdminAction')) {
                $detailsModif = "Attraction ID #{$id} (« {$nom} ») : temps modifié de " . ($ancienTemps ?? '0') . " min à {$attente} min";
                $this->model->logAdminAction($_SESSION['user_id'] ?? 1, 'MODIF_ATTENTE', $detailsModif);
            }

            // 4. Notification WebSocket en temps réel
            $donneesActu = json_encode([
                'type' => 'UPDATE_ATTRACTION',
                'id' => $id,
                'nom' => $nom,
                'attente' => $attente,
                'statut' => $statut
            ]);

            try {
                $sp = @fsockopen("127.0.0.1", 8080, $errno,$errstr, 1);
                if ($sp) {
                    fwrite($sp,$donneesActu);
                    fclose($sp);
                }
            } catch (Exception $e) {
                // Exécution silencieuse si le serveur WebSocket n'est pas actif
            }

            header('Location: index.php?action=admin_attractions');
            exit();
        }
    }

    /**
     * Supprime une attraction de la base de données (Admin).
     */
    public function adminSupprimer($id) {$this->checkAdmin();
        
        // Récupération du nom pour un log plus explicite
        $attraction =$this->model->getAttractionById($id);$nomAttraction = $attraction['nom'] ?? "ID #{$id}";

        $this->model->deleteAttraction($id);
        
        // Journalisation de la suppression dans admin_logs
        if (method_exists($this->model, 'logAdminAction')) {
            $this->model->logAdminAction($_SESSION['user_id'] ?? 1, 'SUPPRESSION_ATTRACTION', "Suppression de l'attraction : « {$nomAttraction} »");
        }

        header('Location: index.php?action=admin_attractions');
        exit();
    }

    /**
     * Affiche la vue des temps d'attente en direct (Live).
     */
    public function pageLive() {
        require_once __DIR__ . '/../views/attractions_live.php';
    }

    /**
     * Fournit les données au format JSON pour l'API Live des temps d'attente.
     */
    public function apiLive() {
        header('Content-Type: application/json');
        $attractions = $this->model->getTempsAttenteLive();
        echo json_encode([
            'derniere_maj' => date('H:i:s'),
            'attractions' => $attractions
        ]);
        exit();
    }

    /**
     * Affiche la carte interactive du parc.
     */
    public function afficherCarteInteractif() {
        $attractions = $this->model->getAttractions(); 
        require_once __DIR__ . '/../views/carte_interactive.php';
    }

    /**
     * Gère le planificateur de visite intelligent basé sur la taille du visiteur et le statut des attractions.
     */
    public function planificateur() {
        $taille_visiteur_metres = isset($_GET['taille_visiteur']) ? (float)str_replace(',', '.', $_GET['taille_visiteur']) : 1.20;
        $taille_visiteur_cm = $taille_visiteur_metres * 100;

        $toutesAttractions = $this->model->getAttractions();

        $parcours = array_filter($toutesAttractions, function($att) use ($taille_visiteur_cm) {
            $estOuvert = (strcasecmp(trim($att['statut'] ?? ''), 'Ouvert') === 0);
            $tailleMinDb = (float)($att['taille_minimale'] ?? 0);
            $tailleOk = $taille_visiteur_cm >= $tailleMinDb;

            return $estOuvert && $tailleOk;
        });

        // Tri par temps d'attente croissant (du plus court au plus long)
        usort($parcours, function($a, $b) {
            return ((int)($a['attente'] ?? 0)) <=> ((int)($b['attente'] ?? 0));
        });

        require_once __DIR__ . '/../views/planificateur.php';
    }
}