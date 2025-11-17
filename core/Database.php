<?php
/**
 * Classe Database
 *
 * Cette classe gère la connexion à la base de données en utilisant le pattern Singleton.
 * Elle assure qu'une seule instance de la connexion à la base de données est créée et utilisée dans toute l'application.
 */
class Database {
    private static $instance;//appartient à la classe elle-même,stocke l'unique instance de la classe database et est accessible sans créer d'objet (Database::getInstance())    
    private $connection;//instance de PDO, appartient à l'objet unique Database accséssible via getConnection()

    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct($host, $username, $password, $database) {        
        try {
            // Création de la connexion PDO
            $this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            //définir les attributs pour la gestion des erreurs
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Méthode statique pour obtenir l'instance unique de la classe Database (une seule connexion à la BDD utilisée pendant toute le cycle de vie de l'application)
    public static function getInstance() {
        //vérif si il n'y a pas instance de la classe Database stockée dans $instance
        if (!self::$instance) {
            // Création de l'instance si elle n'existe pas
            // constantes définies dans config.php
            self::$instance = new Database(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);               
        }
        return self::$instance;
    }
    
    // Méthode pour obtenir la connexion PDO qui permettra aux autres classes et leurs méthodes d'exécuter des requêtes SQL
    public function getConnection() {
        return $this->connection;
    }

}