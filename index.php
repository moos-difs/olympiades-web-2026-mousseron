<?php
/**
 * Fichier de routage principal (Front Controller)
 * Gère l'aiguillage des requêtes HTTP vers les bons contrôleurs en fonction du paramètre 'action'.
 */

// Importation des différents contrôleurs de l'application
require_once 'controllers/HomeController.php';
require_once 'controllers/AttractionController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/BilletterieController.php';
require_once 'controllers/StatsController.php';
require_once 'controllers/ApiController.php';

// Récupération de l'action demandée dans l'URL (par défaut 'accueil')
$action = isset($_GET['action']) ? $_GET['action'] : 'accueil';

// Instanciation des contrôleurs
$homeController = new HomeController();
$attractionController = new AttractionController();
$authController = new AuthController();
$billetterieController = new BilletterieController();
$statsController = new StatsController();
$apiController = new ApiController();

// Aiguillage de l'action vers la méthode correspondante
switch ($action) {
    
    // ==========================================
    // 1. PAGES PUBLIQUES
    // ==========================================
    case 'accueil':
        $homeController->index();
        break;
        
    case 'attractions':
        $attractionController->lister();
        break;
        
    case 'detail':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $attractionController->detail($id);
        break;

    // ==========================================
    // 2. AUTHENTIFICATION & UTILISATEURS
    // ==========================================
    case 'login':
        $authController->afficherConnexion();
        break;
        
    case 'login_post':
        $authController->connecter();
        break;
        
    case 'logout':
        $authController->deconnecter();
        break;

    case 'create_account':
        $authController->creerCompteForm();
        break;
        
    case 'create_account_post':
        $authController->creerCompteTraitement();
        break; 

    case 'account':
        $authController->afficherProfil();
        break;

    case 'reset_pass':
        $authController->afficherFormulaireMdpOublie();
        break;
        
    case 'traiter_mdp_oublie':
        $authController->traiterMdpOublie();
        break;
        
    case 'reset_password':
        $authController->afficherFormulaireNouveauMotDePasse();
        break;
        
    case 'traiter_reset_password':
        $authController->traiterNouveauMotDePasse();
        break;

    // ==========================================
    // 3. BILLETTERIE & RÉSERVATIONS
    // ==========================================
    case 'billetterie':
        $billetterieController->afficherFormulaire();
        break;
        
    case 'billetterie_post':
        $billetterieController->preparerPaiement();
        break;
        
    case 'paiement':
        $billetterieController->afficherPaiement();
        break;
        
    case 'valider_paiement':
        $billetterieController->validerPaiement();
        break;
        
    case 'mes_reservations':
        $billetterieController->mesReservations();
        break;
        
    case 'voir_mail':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $billetterieController->voirMail($id);
        break;

    // ==========================================
    // 4. FONCTIONNALITÉS DU PARC & INTERACTIVITÉ
    // ==========================================
    case 'live':
        $attractionController->pageLive();
        break;
        
    case 'api_live':
        $attractionController->apiLive();
        break;

    case 'carte':
        $attractionController->afficherCarteInteractif();
        break;

    case 'planificateur':
        $attractionController->planificateur();
        break;

    // ==========================================
    // 5. ESPACE ADMINISTRATION (CRUD & CONTRÔLE)
    // ==========================================
    case 'admin_attractions':
        $attractionController->adminLister();
        break;

    case 'admin_create':
        $attractionController->adminCreerForm();
        break;

    case 'admin_create_post':
        $attractionController->adminCreerTraitement();
        break;

    case 'admin_edit':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $attractionController->adminModifierForm($id);
        break;

    case 'admin_update_post':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $attractionController->adminModifierTraitement($id);
        break;

    case 'admin_delete':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $attractionController->adminSupprimer($id);
        break;

    case 'admin_reservations':
        $billetterieController->adminReservations();
        break;

    case 'admin_controle':
        $billetterieController->afficherControleBillet();
        break;

    case 'admin_verifier_billet':
        $billetterieController->verifierBillet();
        break;

    case 'admin_stats':
        $statsController->index();
        break;

    case 'admin_logs':
        $attractionController->adminLogs();
        break;
    
    // ==========================================
    // 6. API EXTERNE & ERREURS
    // ==========================================
    case 'api':
        $apiController->apiGetData();
        break;

    default:
        $attractionController->erreur("La page demandée n'existe pas dans cette dimension !");
        break;
}