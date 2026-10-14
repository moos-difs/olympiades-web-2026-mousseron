<?php
require_once __DIR__ . '/../../config/Database.php';

class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function getFirstEmail() {
        $query = 'SELECT email FROM ' . $this->table . ' LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['email'] : 'No email found';
    }
}