<?php 
// Autochargement des classes
require_once './Autoload.php';

/**
 * Classe PrincipalManager
 *
 * Cette classe fournit des méthodes génériques pour interagir avec la base de données
 * en utilisant PDO. Elle centralise les opérations CRUD (Create, Read, Update, Delete)
 * afin d'éviter la duplication de code dans les managers spécifiques (BookManager, UserManager, etc.).
 *
 * Principales responsabilités :
 * - Ajouter un enregistrement dans une table donnée.
 * - Récupérer tous les enregistrements ou un enregistrement par ID.
 * - Mettre à jour un enregistrement existant.
 * - Supprimer un enregistrement.
 *
 * @property PDO Database $db Instance de la classe Database (Singleton).
 */
class PrincipalManager {
    protected $db;

    public function __construct() {
        // Initialisation de la connexion à la base de données
        // instanciation de la classe Database par méthode static getInstance()
        $this->db = Database::getInstance();
    }

    /**
     * Méthode add() pour insérer dynamiquement un enregistrement dans une table donnée.
     *
     * Cette méthode :
     * - Construit une requête SQL d'insertion en fonction des clés du tableau associatif $data.
     *   - Les clés du tableau deviennent les noms des colonnes.
     *   - Les valeurs sont liées à des paramètres nommés (sécurisés contre les injections SQL).
     * - Prépare et exécute la requête via PDO.
     * - Lie chaque valeur du tableau $data à son paramètre correspondant grâce à bindParam().
     * - Retourne true si au moins une ligne est insérée, false sinon.
     *
     * @param string $table Nom de la table cible dans laquelle insérer les données.
     * @param array  $data  Tableau associatif [colonne => valeur] représentant les données à insérer.
     * @return bool True si l'insertion réussit (au moins une ligne affectée), False sinon.
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

    /**
     * Méthode getAll() pour récupérer tous les enregistrements d'une table donnée.
     *
     * Cette méthode :
     * - Construit une requête SQL simple `SELECT *` sur la table spécifiée.
     * - Prépare et exécute la requête via PDO pour sécuriser l'accès aux données.
     * - Récupère l'ensemble des résultats sous forme de tableau associatif.
     * - Retourne toutes les lignes de la table, chaque ligne étant représentée par un tableau clé/valeur.
     *
     * @param string $table Nom de la table cible dont on veut récupérer les enregistrements.
     * @return array Liste des enregistrements de la table (tableau associatif).
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

    /**
     * Méthode getById() pour récupérer un enregistrement spécifique par son identifiant.
     *
     * Cette méthode :
     * - Construit une requête SQL `SELECT *` filtrée par l'ID de l'enregistrement.
     * - Prépare et exécute la requête via PDO pour sécuriser l'accès aux données.
     * - Lie le paramètre `:id` à la valeur fournie afin d'éviter les injections SQL.
     * - Récupère le résultat sous forme de tableau associatif.
     * - Retourne l'enregistrement correspondant ou null si aucun résultat n'est trouvé.
     *
     * @param string $table Nom de la table cible dans laquelle chercher l'enregistrement.
     * @param int    $id    Identifiant unique de l'enregistrement à récupérer.
     * @return array|null   Tableau associatif représentant l'enregistrement trouvé,
     *                      ou null si aucun enregistrement ne correspond.
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
     * Méthode update() pour mettre à jour dynamiquement un enregistrement dans une table donnée.
     *
     * Cette méthode :
     * - Construit automatiquement la clause `SET` de la requête SQL en fonction des clés du tableau $data.
     *   - Chaque clé du tableau devient une colonne à mettre à jour.
     *   - Chaque valeur est liée à un paramètre nommé (sécurisé contre les injections SQL).
     * - Prépare et exécute la requête via PDO.
     * - Lie chaque valeur du tableau $data à son paramètre correspondant grâce à bindParam().
     * - Lie également l'identifiant de l'enregistrement (`id`) pour cibler la ligne à modifier.
     * - Retourne true si la mise à jour réussit, false sinon.
     *
     * @param string $table Nom de la table cible dans laquelle mettre à jour l'enregistrement.
     * @param array  $data  Tableau associatif [colonne => valeur] représentant les données à modifier.
     * @param int    $id    Identifiant unique de l'enregistrement à mettre à jour.
     * @return bool  True si la mise à jour réussit, False sinon.
     */
    protected function update($table, $data, $id) {
        // Construction dynamique de la clause SET
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setString = implode(", ", $set);
        // Requête SQL de mise à jour
        // Construction dynamique de la clause SET avec les colonnes et paramètres
        // WHERE id = :id pour cibler l'enregistrement spécifique
        // Cette requête met à jour une ligne précise dans la table $table, en modifiant les colonnes définies dans $data, uniquement pour l’enregistrement dont l’ID correspond à :id.
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
     * Méthode delete() pour supprimer dynamiquement un enregistrement dans une table donnée.
     *
     * Cette méthode :
     * - Construit une requête SQL `DELETE` ciblant la table spécifiée.
     * - Utilise une condition `WHERE id = :id` pour supprimer uniquement l'enregistrement correspondant.
     * - Prépare et exécute la requête via PDO afin de sécuriser l'opération.
     * - Lie le paramètre `:id` à la valeur fournie pour éviter les injections SQL.
     * - Retourne true si la suppression réussit, false sinon.
     *
     * @param string $table Nom de la table cible dans laquelle supprimer l'enregistrement.
     * @param int    $id  Identifiant unique de l'enregistrement à supprimer.
     * @return bool  True si la suppression réussit, False sinon.
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