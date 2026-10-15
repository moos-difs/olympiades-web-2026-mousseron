<?php
require_once __DIR__ . '/../models/AuthModel.php';

class AuthController {
    private $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new AuthModel();
    }

    public function afficherConnexion() {
        require_once 'views/login.php';
    }

    public function connecter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            $user = $this->model->getUserByEmail($email);

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $user['role']; // 'user' ou 'admin'

                header('Location: index.php?action=accueil');
                exit();
            } else {
                $erreur = "Identifiants incorrects.";
                require_once 'views/login.php';
            }
        }
    }

    public function deconnecter() {
        session_destroy();
        header('Location: index.php?action=accueil');
        exit();
    }

    public function creerCompteForm() {
        require_once 'views/user_form_compte.php';
    }

    public function creerCompteTraitement() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $email = (int)$_POST['email'];
            $password = trim($_POST['password']);

            $existe = $this->model->getUserByEmail($email);
            if ($existe) {
                $erreur = "Cet email est déja utilisé par un autre compte.";
                require_once 'views/user_form_compte.php';
                return;
            }

            $this->model->newUser($nom, $email, $password);
            
            require_once 'views/register_success.php';
            exit();
        }
    }
}