<?php
/**
 * Classe : Database
 * Gère la connexion unique (Singleton) à la base de données MySQL via PDO
 * en chargeant les paramètres de configuration sécurisés depuis les variables d'environnement (.env).
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Chargement des variables d'environnement depuis la racine du projet
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Database
{
    private static $conn = null;

    /**
     * Récupère l'instance unique de la connexion PDO (Singleton).
     * Établit la connexion avec le serveur MySQL si elle n'existe pas encore.
     * 
     * @return PDO L'instance de connexion PDO à la base de données.
     */
    public static function getConnection()
    {
        if (self::$conn === null) {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $db_name = $_ENV['DB_NAME'] ?? '';
            $username = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';

            try {
                self::$conn = new PDO(
                    "mysql:host=" . $host . ";dbname=" . $db_name . ";charset=utf8mb4",
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $exception) {
                // Arrêt de l'exécution et affichage de l'erreur en cas d'échec de connexion
                die("Erreur de connexion à la base de données : " . $exception->getMessage());
            }
        }
        
        return self::$conn;
    }
}