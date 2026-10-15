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

    public function newUser($nom, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
        $stmt = $this->db->prepare("
            INSERT INTO utilisateurs (nom, email, mot_de_passe) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$nom, $email, $hashed_password]);
    }
}