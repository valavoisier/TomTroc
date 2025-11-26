<?php
require_once './Autoload.php';

/**
 * Classe AbstractManager
 *
 * Classe de base pour centraliser les opérations CRUD génériques
 * (Create, Read, Update, Delete) avec PDO.
 * Tous les managers spécifiques (BookManager, UserManager, eMessageManager)
 * héritent de cette classe.
 */
abstract class AbstractManager {
    protected $db;

    public function __construct() {
        // Connexion à la base via Singleton Database
        $this->db = Database::getInstance();
    }

    /**
     * Insère un enregistrement dans une table.
     */
    public function add(string $table, array $data): bool {
        $columns = implode(", ", array_keys($data));
        $params = ":" . implode(", :", array_keys($data));
        $query = "INSERT INTO $table ($columns) VALUES ($params)";
        $dbConnection = $this->db->getConnection();
        $req = $dbConnection->prepare($query);
        foreach ($data as $key => &$value) {
            $req->bindParam(":$key", $value);
        }
        return $req->execute();
    }

    /**
     * Récupère tous les enregistrements d'une table.
     */
    public function getAll(string $table): array {
        $query = "SELECT * FROM $table";
        $dbConnection = $this->db->getConnection();
        $req = $dbConnection->prepare($query);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un enregistrement par son ID.
     */
    public function getById(string $table, int $id): ?array {
        $query = "SELECT * FROM $table WHERE id = :id";
        $dbConnection = $this->db->getConnection();
        $req = $dbConnection->prepare($query);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Met à jour un enregistrement.
     */
    public function update(string $table, array $data, int $id): bool {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setString = implode(", ", $set);
        $query = "UPDATE $table SET $setString WHERE id = :id";
        $dbConnection = $this->db->getConnection();
        $req = $dbConnection->prepare($query);
        foreach ($data as $key => &$value) {
            $req->bindParam(":$key", $value);
        }
        $req->bindParam(":id", $id, PDO::PARAM_INT);
        return $req->execute();
    }

    /**
     * Supprime un enregistrement.
     */
    public function delete(string $table, int $id): bool {
        $query = "DELETE FROM $table WHERE id = :id";
        $dbConnection = $this->db->getConnection();
        $req = $dbConnection->prepare($query);
        $req->bindParam(":id", $id, PDO::PARAM_INT);
        return $req->execute();
    }
}
