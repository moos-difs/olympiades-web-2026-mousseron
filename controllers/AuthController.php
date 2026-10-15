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
                $erreur = "Cet email est déja utilisé par un autre compte.";
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
                // 1. Génération du token et enregistrement en BDD
                $token = bin2hex(random_bytes(32));
                $this->model->saveResetToken($user['id'], $token);

                // 2. Construction propre et dynamique du lien (évite le dashboard XAMPP)
                $protocole = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
                $hote = $_SERVER['HTTP_HOST'];
                
                // Récupère le dossier du projet (ex: /studio-toon)
                $dossier_projet = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

                // Lien complet pointant vers ton routeur index.php avec le token
                $reset_link = $protocole . "://" . $hote . $dossier_projet . "/index.php?action=reset_password&token=" . $token;

                // 3. Stockage en session pour que la prévisualisation du mail récupère les bonnes infos
                $_SESSION['mail_preview'] = [
                    'user_prenom' => $user['nom'], // ou $user['prenom'] selon ta base de données
                    'reset_link' => $reset_link
                ];

                $message_succes = "Un e-mail de réinitialisation vient d'être généré avec succès !";
            } else {
                $message_erreur = "Aucun compte trouvé avec cette adresse e-mail.";
            }

            require_once 'views/forgot_password.php';
        }
    }


    // Afficher le formulaire de saisie du nouveau mot de passe
    public function afficherFormulaireNouveauMotDePasse() {
        $token = $_GET['token'] ?? '';
        
        // Vérifier si le token est valide
        $user = $this->model->findByValidResetToken($token);
        
        if (!$user) {
            $message_erreur = "Ce lien de réinitialisation est invalide ou a expiré.";
        }

        require_once 'views/reset_password_form.php';
    }

    // Traiter la mise à jour du mot de passe
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

            // Mise à jour du mot de passe et suppression du token
            $this->model->updatePasswordAndClearToken($user['id'], $nouveau_mdp);

            // Redirection vers la page de connexion avec un message de succès
            header('Location: index.php?action=login&reset=success');
            exit();
        }
    }
}