<?php
/**
 * Configuration de la Base de Données
 * Benna Tounsiya - Restaurant API
 */

// ========== EN-TÊTES CORS ==========
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Gérer la requête OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// ===================================

// Configuration MySQL
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // XAMPP par défaut n'a pas de mot de passe
define('DB_NAME', 'benna_tounsiya');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

// Classe de Connexion à la Base de Données
class Database {
    private $connection;
    private static $instance = null;

    /**
     * Singleton - Évite plusieurs connexions
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Établir la connexion MySQL
     */
    private function __construct() {
        try {
            $this->connection = new mysqli(
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                DB_PORT
            );

            // Vérifier la connexion
            if ($this->connection->connect_error) {
                throw new Exception('Erreur de connexion: ' . $this->connection->connect_error);
            }

            // Définir le charset
            $this->connection->set_charset(DB_CHARSET);
            
        } catch (Exception $e) {
            $this->sendJsonError($e->getMessage());
        }
    }

    /**
     * Envoyer une erreur en JSON
     */
    private function sendJsonError($message) {
        echo json_encode([
            "success" => false, 
            "message" => $message
        ]);
        exit();
    }

    /**
     * Obtenir la connexion
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Préparer une requête
     */
    public function prepare($query) {
        return $this->connection->prepare($query);
    }

    /**
     * Exécuter une requête simple
     */
    public function query($query) {
        return $this->connection->query($query);
    }

    /**
     * Obtenir le dernier ID inséré
     */
    public function lastInsertId() {
        return $this->connection->insert_id;
    }

    /**
     * Obtenir le nombre de lignes affectées
     */
    public function affectedRows() {
        return $this->connection->affected_rows;
    }

    /**
     * Échapper une chaîne pour éviter les injections SQL
     */
    public function escape($string) {
        return $this->connection->real_escape_string($string);
    }

    /**
     * Démarrer une transaction
     */
    public function beginTransaction() {
        return $this->connection->begin_transaction();
    }

    /**
     * Valider une transaction
     */
    public function commit() {
        return $this->connection->commit();
    }

    /**
     * Annuler une transaction
     */
    public function rollback() {
        return $this->connection->rollback();
    }

    /**
     * Obtenir l'erreur
     */
    public function error() {
        return $this->connection->error;
    }

    /**
     * Fermer la connexion
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    /**
     * Empêcher le clonage
     */
    private function __clone() {}
    
    /**
     * Empêcher la désérialisation
     */
    public function __wakeup() {}
}

// Créer une instance globale
$db = Database::getInstance();
?>