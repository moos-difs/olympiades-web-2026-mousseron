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
}