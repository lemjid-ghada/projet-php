<?php
/**
 * Configuration de la Base de Données
 * Benna Tounsiya - Restaurant API
 */

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
            die('Erreur Database: ' . $e->getMessage());
        }
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
     * Échapper une chaîne
     */
    public function escape($string) {
        return $this->connection->real_escape_string($string);
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
