<?php
require_once __DIR__ . '/../models/AuthModel.php';

class AuthController {
    private $model;
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->model = new AuthModel();
        $this->db = Database::getConnection();
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

    // --- AFFICHAGE DU PROFIL UTILISATEUR ---
    public function afficherProfil() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        
        // Informations du compte
        $utilisateur = $this->model->getUserById($userId);
        
        // Liste des réservations pour accéder aux e-billets
        $reservations = $this->model->getReservationsByUser($userId);

        require_once 'views/user_account.php';
    }

    public function creerCompteForm() {
        require_once 'views/user_form_compte.php';
    }

    public function creerCompteTraitement() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $existe = $this->model->getUserByEmail($email);
            if ($existe) {
                $erreur = "Cet email est déjà utilisé par un autre compte.";
                require_once 'views/user_form_compte.php';
                return;
            }

            $this->model->newUser($nom, $email, $password);
            
            require_once 'views/register_success.php';
            exit();
        }
    }

    public function afficherFormulaireMdpOublie() {
        require_once 'views/forgot_password.php';
    }

    public function traiterMdpOublie() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $user = $this->model->getUserByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $this->model->saveResetToken($user['id'], $token);

                $protocole = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
                $hote = $_SERVER['HTTP_HOST'];
                $dossier_projet = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

                $reset_link = $protocole . "://" . $hote . $dossier_projet . "/index.php?action=reset_password&token=" . $token;

                $_SESSION['mail_preview'] = [
                    'user_prenom' => $user['nom'],
                    'reset_link' => $reset_link
                ];

                $message_succes = "Un e-mail de réinitialisation vient d'être généré avec succès !";
            } else {
                $message_erreur = "Aucun compte trouvé avec cette adresse e-mail.";
            }

            require_once 'views/forgot_password.php';
        }
    }

    public function afficherFormulaireNouveauMotDePasse() {
        $token = $_GET['token'] ?? '';
        
        $user = $this->model->findByValidResetToken($token);
        
        if (!$user) {
            $message_erreur = "Ce lien de réinitialisation est invalide ou a expiré.";
        }

        require_once 'views/reset_password_form.php';
    }

    public function traiterNouveauMotDePasse() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $nouveau_mdp = $_POST['nouveau_password'] ?? '';
            $confirmation = $_POST['confirmation_password'] ?? '';

            $user = $this->model->findByValidResetToken($token);

            if (!$user) {
                $message_erreur = "Session expirée ou lien invalide.";
                require_once 'views/reset_password_form.php';
                return;
            }

            if ($nouveau_mdp !== $confirmation) {
                $message_erreur = "Les mots de passe ne correspondent pas.";
                require_once 'views/reset_password_form.php';
                return;
            }

            $this->model->updatePasswordAndClearToken($user['id'], $nouveau_mdp);

            header('Location: index.php?action=login&reset=success');
            exit();
        }
    }
}