<?php 

class Database {
    private static $instance;
    private $connection;

    private function __construct($host, $username, $password, $database) {        
        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        //vérif si il n'y a pas instance de la classe Database stockée dans $instance
        if (!self::$instance) {            
            self::$instance = new Database(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);               
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

}