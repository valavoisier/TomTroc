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
     
}