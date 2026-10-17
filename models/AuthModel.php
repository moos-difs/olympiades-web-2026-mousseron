<?php
/**
 * Modèle : AuthModel
 * Gère l'authentification des utilisateurs, l'inscription sécurisée (hachage Argon2ID),
 * la récupération du profil, l'historique des réservations et la procédure de réinitialisation de mot de passe.
 */

require_once __DIR__ . '/../config/Database.php';

class AuthModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Récupère un utilisateur en fonction de son adresse e-mail.
     */
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Récupère un utilisateur par son identifiant unique (pour la page profil).
     */
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Récupère l'historique complet des réservations d'un utilisateur, avec le total des billets associés.
     */
    public function getReservationsByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT r.*, 
                   SUM(rb.quantite) as total_billets
            FROM reservations r 
            LEFT JOIN reservation_billets rb ON rb.reservation_id = r.id
            WHERE r.utilisateur_id = ? 
            GROUP BY r.id
            ORDER BY r.id DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Enregistre un nouvel utilisateur dans la base de données avec hachage sécurisé du mot de passe.
     */
    public function newUser($nom, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("
            INSERT INTO utilisateurs (nom, email, mot_de_passe) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$nom, $email, $hashed_password]);
    }

    /**
     * Enregistre le token de réinitialisation de mot de passe et sa date d'expiration (+15 minutes).
     */
    public function saveResetToken($user_id, $token) {
        $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $stmt = $this->db->prepare("
            UPDATE utilisateurs 
            SET reset_token = ?, reset_expires_at = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$token, $expires_at, $user_id]);
    }

    /**
     * Trouve un utilisateur par son token de réinitialisation s'il est valide et non expiré.
     */
    public function findByValidResetToken($token) {
        $stmt = $this->db->prepare("
            SELECT * FROM utilisateurs 
            WHERE reset_token = ? 
            AND reset_expires_at > NOW()
        ");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    /**
     * Met à jour le mot de passe d'un utilisateur et efface les jetons de réinitialisation utilisés.
     */
    public function updatePasswordAndClearToken($user_id, $nouveau_password) {
        $hash = password_hash($nouveau_password, PASSWORD_ARGON2ID);

        $stmt = $this->db->prepare("
            UPDATE utilisateurs 
            SET mot_de_passe = ?, reset_token = NULL, reset_expires_at = NULL 
            WHERE id = ?
        ");
        return $stmt->execute([$hash, $user_id]);
    }
}