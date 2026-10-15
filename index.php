<?php
require_once 'controllers/HomeController.php';
require_once 'controllers/AttractionController.php';
require_once 'controllers/AuthController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'accueil';

$homeController = new HomeController();
$attractionController = new AttractionController();
$authController = new AuthController();

switch ($action) {
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
case 'login':
$authController->afficherConnexion();
break;
case 'login_post':
$authController->connecter();
break;
case 'logout':
$authController->deconnecter();
break;
case 'admin_create':
$attractionController->ajouter();
break;
case 'admin_edit':
$attractionController->modifier($id);
break;
case 'admin_delete':
$attractionController->supprimer($id);
break;
default:
$attractionController->erreur("La page demandée n'existe pas !");
break;
}