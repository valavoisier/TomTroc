<?php
/**
 * Classe Database
 *
 * Cette classe implémente le design pattern Singleton pour gérer la connexion à la base de données.
 * Elle garantit qu'une seule instance de la connexion PDO est créée et réutilisée
 * pendant tout le cycle de vie de l'application.
 *
 * Responsabilités principales :
 * - Créer et configurer une connexion PDO avec les paramètres définis (hôte, utilisateur, mot de passe, base).
 * - Fournir un accès global à cette connexion via la méthode statique getInstance().
 * - Empêcher l'instanciation multiple grâce à un constructeur privé.
 * - Exposer la connexion PDO aux autres classes via la méthode getConnection().
 *
 * @property PDO $connection Instance PDO représentant la connexion active à la base de données.
 * @property Database|null $instance Instance unique de la classe Database (Singleton).
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