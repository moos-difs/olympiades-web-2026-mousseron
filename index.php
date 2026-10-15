<?php
require_once 'controllers/HomeController.php';
require_once 'controllers/AttractionController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/BilletterieController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'accueil';

$homeController = new HomeController();
$attractionController = new AttractionController();
$authController = new AuthController();
$billetterieController = new BilletterieController();

switch ($action) {
    // Pages publiques
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

    // Authentification
    case 'login':
        $authController->afficherConnexion();
        break;
    case 'login_post':
        $authController->connecter();
        break;
    case 'logout':
        $authController->deconnecter();
        break;

    // --- CRUD ADMIN ---
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

    case 'create_account':
        $authController->creerCompteForm();
        break;

    case 'create_account_post':
        $authController->creerCompteTraitement();

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
    case 'admin_reservations':
        $billetterieController->adminReservations();
        break;

    default:
        $attractionController->erreur("La page demandée n'existe pas dans cette dimension !");
        break;
}