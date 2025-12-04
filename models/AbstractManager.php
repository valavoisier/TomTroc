<?php
/**
 * Classe AbstractManager
 *
 * Classe de base pour centraliser les opérations CRUD génériques
 * (Create, Read, Update, Delete) avec PDO.
 * Tous les managers spécifiques (BookManager, UserManager, eMessageManager)
 * héritent de cette classe.
 * Responsabilités principales :
 * - Gérer la connexion à la base de données via le pattern singleton Database.
 * - Fournir des méthodes CRUD réutilisables pour les entités.
 * - Réduire la duplication de code dans les managers spécifiques.
 * Méthodes principales :
 * - add() → Insérer un enregistrement.
 * - getAll() → Récupérer tous les enregistrements.
 * - getById() → Récupérer un enregistrement par ID.
 * - update() → Mettre à jour un enregistrement.
 * - delete() → Supprimer un enregistrement.
 */
abstract class AbstractManager {
    protected $db;

    public function __construct() {
        // Connexion à la base via le pattern Singleton Database
        $this->db = Database::getInstance();
    }

    /**
     * Insère un enregistrement dans une table.
     *  
     * Cette méthode add() :
     * - Prépare une requête d'insertion SQL avec des paramètres nommés.
     * - Lie les valeurs fournies aux paramètres.
     * - Exécute la requête pour insérer l'enregistrement.
     * @param string $table Nom de la table où insérer l'enregistrement.
     * @param array  $data  Données à insérer sous forme de tableau associatif (colonne => valeur).
     * @return bool         Retourne true si l'insertion a réussi, false sinon.
     *  @uses Database::getConnection() Pour obtenir la connexion PDO.
     *
     */
    public function add(string $table, array $data): bool {
        // Préparer les colonnes et les paramètres pour la requête
        $columns = implode(", ", array_keys($data));//nom des colonnes séparées par des virgules
        $params = ":" . implode(", :", array_keys($data));//paramètres nommés pour la requête
        $query = "INSERT INTO $table ($columns) VALUES ($params)";  // requête d'insertion
        $dbConnection = $this->db->getConnection();  //obtenir la connexion PDO   
        $req = $dbConnection->prepare($query); //préparer la requête
        // Lier les valeurs aux paramètres nommés
        foreach ($data as $key => $value) {
            $req->bindValue(":$key", $value);//lier chaque valeur au paramètre correspondant
        }
        return $req->execute();
    }

    /**
     * getAll() Récupère tous les enregistrements d'une table.
     * 
     * @return array Tableau associatif contenant tous les enregistrements.
     * @uses Database::getConnection() Pour obtenir la connexion PDO.
     *      
    
     */
    public function getAll(string $table): array {
        $query = "SELECT * FROM $table";// requête pour récupérer tous les enregistrements
        $dbConnection = $this->db->getConnection();
        $req = $dbConnection->prepare($query);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getById()Récupère un enregistrement par son ID.
     *
     * @param string $table Nom de la table.
     * @param int    $id    ID de l'enregistrement à récupérer.
     * @return array|null    Tableau associatif de l'enregistrement ou null si non trouvé.
     *  @uses Database::getConnection() Pour obtenir la connexion PDO.
     */
    public function getById(string $table, int $id): ?array {
        // Préparer et exécuter la requête        
        $query = "SELECT * FROM $table WHERE id = :id";// requête pour récupérer l'enregistrement par ID
        $dbConnection = $this->db->getConnection();
        $req = $dbConnection->prepare($query);//préparer la requête évite injection SQL
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     *update() Met à jour un enregistrement.

        * @param string $table Nom de la table.
        * @param array  $data  Données à mettre à jour sous forme de tableau associatif (colonne => valeur).
        * @param int    $id    ID de l'enregistrement à mettre à jour.
        * @return bool         Retourne true si la mise à jour a réussi, false sinon
        *  @uses Database::getConnection() Pour obtenir la connexion PDO.
     */
    public function update(string $table, array $data, int $id): bool {
        // Préparer la partie SET de la requête
        $set = [];
        // Construire la chaîne SET avec les colonnes et les paramètres nommés
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setString = implode(", ", $set);//colonnes à mettre à jour séparées par des virgules
        $query = "UPDATE $table SET $setString WHERE id = :id";// requête pour mettre à jour un enregistrement
        $dbConnection = $this->db->getConnection();
        $req = $dbConnection->prepare($query);
        foreach ($data as $key => $value) {
            $req->bindValue(":$key", $value);
        }
        $req->bindValue(":id", $id, PDO::PARAM_INT);
        return $req->execute();
    }

    /**
     * delete() Supprime un enregistrement.
     * * @param string $table Nom de la table.
     * @param int    $id    ID de l'enregistrement à supprimer.
     * @return bool         Retourne true si la suppression a réussi, false sinon.
     * @uses Database::getConnection() Pour obtenir la connexion PDO.
     */
    public function delete(string $table, int $id): bool {
        // Préparer et exécuter la requête de suppression
        $query = "DELETE FROM $table WHERE id = :id";
        $dbConnection = $this->db->getConnection();
        $req = $dbConnection->prepare($query);
        $req->bindParam(":id", $id, PDO::PARAM_INT);
        return $req->execute();
    }
}
