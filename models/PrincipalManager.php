<?php 
/*
Gestionnaire principal pour les opérations génériques sur la base de données
*/
// Autochargement des classes
require_once './Autoload.php';
class PrincipalManager {
    protected $db;

    public function __construct() {
        // Initialisation de la connexion à la base de données
        // instanciation de la classe Database par méthode static getInstance()
        $this->db = Database::getInstance();
    }

    /* Méthode générique pour ajouter un enregistrement dans une table donnée
     * $table : nom de la table
     * $data : tableau associatif des colonnes et valeurs à insérer
     */
    protected function add($table, $data) {
        //implode crée chaîne de caractères contenant les clés du tableau $data séparés par des virgules
        $columns = implode(", ", array_keys($data));
        // Crée une chaîne de caractères des paramètres nommés pour la requête préparée 
        // ":" évite les injections SQL
        $params = ":" . implode(", :", array_keys($data));
        // Requête d'insertion dynamique
        $query = "INSERT INTO $table ($columns) VALUES ($params)";
        //stocke la connexion PDO
        $dbConnection = $this->db->getConnection();
        // Préparation et exécution de la requête
        $req = $dbConnection->prepare($query);
        // Liaison des paramètres / values
        // foreach pour lier chaque clé du tableau $data à son paramètre correspondant dans la requête préparée
        // &$value permet de modifier directement la valeur dans le tableau $data à chaque itérration de la boucle/ fait référence à chaque élément du tableau 
        foreach ($data as $key => &$value) {
            // bindParam lie une variable PHP à un paramètre nommé dans la requête SQL préparée
            $req->bindParam(":$key", $value);
        }
        // Exécution de la requête
        $req->execute();
        // Retourne le nombre de lignes affectées, opération réussie si > 0 (renvoie true/false)
        return $req->rowCount() > 0;
    }

    /** Méthode générique pour récupérer tous les enregistrements d'une table donnée
     * $table : nom de la table
     */
    protected function getAll($table) {
        // Requête pour récupérer tous les enregistrements d'une table donnée
        $query = "SELECT * FROM $table";
        //stocke la connexion PDO
        $dbConnection = $this->db->getConnection();
        // Préparation et exécution de la requête
        $req = $dbConnection->prepare($query);
        $req->execute();
        // Récupération des résultats sous forme de tableau associatif
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Méthode générique pour récupérer un enregistrement par son ID dans une table donnée
     * $table : nom de la table
     * $id : identifiant de l'enregistrement
     */
    protected function getById($table, $id) {
        // Requête pour récupérer un enregistrement par son ID
        $query = "SELECT * FROM $table WHERE id = :id";
        //stocke la connexion PDO
        $dbConnection = $this->db->getConnection();
        // Préparation et exécution de la requête
        $req = $dbConnection->prepare($query);
        // Binding du paramètre ID à la valeur fournie 
        $req->bindParam(':id', $id);
        // Exécution de la requête
        $req->execute();
        // Récupération du résultat sous forme de tableau associatif
        $result = $req->fetch(PDO::FETCH_ASSOC);
        return $result;
    } 
    /**
 * Méthode générique pour mettre à jour un enregistrement dans une table donnée
 * @param string $table : nom de la table
 * @param array $data : tableau associatif colonnes => valeurs
 * @param int $id : identifiant de l'enregistrement à mettre à jour
 * @return bool : true si la mise à jour a réussi, false sinon
 */
protected function update($table, $data, $id) {
    // Construction dynamique de la clause SET
    $set = [];
    foreach ($data as $key => $value) {
        $set[] = "$key = :$key";
    }
    $setString = implode(", ", $set);

    // Requête SQL
    $query = "UPDATE $table SET $setString WHERE id = :id";

    // Connexion PDO
    $dbConnection = $this->db->getConnection();
    $req = $dbConnection->prepare($query);

    // Liaison des paramètres
    foreach ($data as $key => &$value) {
        $req->bindParam(":$key", $value);
    }
    $req->bindParam(":id", $id, PDO::PARAM_INT);

    // Exécution
    return $req->execute();
}

/**
 * Méthode générique pour supprimer un enregistrement dans une table donnée
 * @param string $table : nom de la table
 * @param int $id : identifiant de l'enregistrement à supprimer
 * @return bool : true si la suppression a réussi, false sinon
 */
protected function delete($table, $id) {
    // Requête SQL
    $query = "DELETE FROM $table WHERE id = :id";

    // Connexion PDO
    $dbConnection = $this->db->getConnection();
    $req = $dbConnection->prepare($query);

    // Liaison du paramètre
    $req->bindParam(":id", $id, PDO::PARAM_INT);

    // Exécution
    return $req->execute();
}  
     
}