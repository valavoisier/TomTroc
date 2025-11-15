<?php
/**
 * Classe Database
 *
 * Cette classe gère la connexion à la base de données en utilisant le pattern Singleton.
 */
class Database {
    private static $instance;
    private $connection;

    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct($host, $username, $password, $database) {        
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Méthode statique pour obtenir l'instance unique de la classe Database
    public static function getInstance() {
        //vérif si il n'y a pas instance de la classe Database stockée dans $instance
        if (!self::$instance) {
            // Création de l'instance si elle n'existe pas
            // constantes définies dans config.php
            self::$instance = new Database(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);               
        }
        return self::$instance;
    }
    
    // Méthode pour obtenir la connexion PDO
    public function getConnection() {
        return $this->connection;
    }

}