<?php
require_once __DIR__ . '/../config/Database.php';

class AuthModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Récupérer un utilisateur par son ID (pour la page profil)
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Récupérer l'historique des réservations d'un utilisateur
    // Récupérer l'historique des réservations d'un utilisateur
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

    public function newUser($nom, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("
            INSERT INTO utilisateurs (nom, email, mot_de_passe) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$nom, $email, $hashed_password]);
    }

    // Enregistrer le token de réinitialisation et son expiration pour un utilisateur
    public function saveResetToken($user_id, $token) {
        // Le token expirera dans 15 minutes
        $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $stmt = $this->db->prepare("
            UPDATE utilisateurs 
            SET reset_token = ?, reset_expires_at = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$token, $expires_at, $user_id]);
    }

    // Trouver un utilisateur par son token valide et non expiré
    public function findByValidResetToken($token) {
        $stmt = $this->db->prepare("
            SELECT * FROM utilisateurs 
            WHERE reset_token = ? 
            AND reset_expires_at > NOW()
        ");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    // Mettre à jour le mot de passe et réinitialiser les champs de token
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