<?php
/**
 * Contrôleur : AuthController
 * Gère l'authentification des utilisateurs du parc Studio Toon : connexion sécurisée,
 * déconnexion, gestion du profil, inscription, et procédure de réinitialisation de mot de passe par jeton.
 */

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

    /**
     * Affiche le formulaire de connexion.
     */
    public function afficherConnexion() {
        require_once __DIR__ . '/../views/login.php';
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function connecter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->model->getUserByEmail($email);

            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $_SESSION['user_role'] = $user['role']; // 'client' ou 'admin'

                header('Location: index.php?action=accueil');
                exit();
            } else {
                $erreur = "Identifiants incorrects.";
                require_once __DIR__ . '/../views/login.php';
            }
        }
    }

    /**
     * Déconnecte l'utilisateur en détruisant la session.
     */
    public function deconnecter() {
        session_destroy();
        header('Location: index.php?action=accueil');
        exit();
    }

    /**
     * Affiche le profil de l'utilisateur connecté avec l'historique de ses réservations.
     */
    public function afficherProfil() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        
        // Récupération des informations du compte et des réservations associées
        $utilisateur = $this->model->getUserById($userId);
        $reservations = $this->model->getReservationsByUser($userId);

        require_once __DIR__ . '/../views/user_account.php';
    }

    /**
     * Affiche le formulaire d'inscription d'un nouveau compte.
     */
    public function creerCompteForm() {
        require_once __DIR__ . '/../views/user_form_compte.php';
    }

    /**
     * Traite l'inscription d'un nouvel utilisateur après vérification de l'unicité de l'e-mail.
     */
    public function creerCompteTraitement() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $existe = $this->model->getUserByEmail($email);
            if ($existe) {
                $erreur = "Cet email est déjà utilisé par un autre compte.";
                require_once __DIR__ . '/../views/user_form_compte.php';
                return;
            }

            $this->model->newUser($nom, $email, $password);
            
            require_once __DIR__ . '/../views/register_success.php';
            exit();
        }
    }

    /**
     * Affiche le formulaire de demande de réinitialisation de mot de passe (mot de passe oublié).
     */
    public function afficherFormulaireMdpOublie() {
        require_once __DIR__ . '/../views/forgot_password.php';
    }

    /**
     * Traite la demande de réinitialisation et génère un jeton sécurisé temporaire.
     */
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

            require_once __DIR__ . '/../views/forgot_password.php';
        }
    }

    /**
     * Affiche le formulaire de saisie d'un nouveau mot de passe après validation du jeton.
     */
    public function afficherFormulaireNouveauMotDePasse() {
        $token = $_GET['token'] ?? '';
        
        $user = $this->model->findByValidResetToken($token);
        
        if (!$user) {
            $message_erreur = "Ce lien de réinitialisation est invalide ou a expiré.";
        }

        require_once __DIR__ . '/../views/reset_password_form.php';
    }

    /**
     * Traite la mise à jour effective du mot de passe après vérification du jeton et confirmation.
     */
    public function traiterNouveauMotDePasse() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $nouveau_mdp = $_POST['nouveau_password'] ?? '';
            $confirmation = $_POST['confirmation_password'] ?? '';

            $user = $this->model->findByValidResetToken($token);

            if (!$user) {
                $message_erreur = "Session expirée ou lien invalide.";
                require_once __DIR__ . '/../views/reset_password_form.php';
                return;
            }

            if ($nouveau_mdp !== $confirmation) {
                $message_erreur = "Les mots de passe ne correspondent pas.";
                require_once __DIR__ . '/../views/reset_password_form.php';
                return;
            }

            $this->model->updatePasswordAndClearToken($user['id'], $nouveau_mdp);

            header('Location: index.php?action=login&reset=success');
            exit();
        }
    }
}