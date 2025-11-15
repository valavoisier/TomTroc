<?php
// Autochargement des classes
require_once './Autoload.php';

class Books
{
    private $db;

    public function __construct()
    {
        // Initialisation de la connexion à la base de données
        // instanciation de la classe Database par méthode static getInstance()
        $this->db = Database::getInstance();
    }

    // Méthode pour enregistrer un livre en BDD
    public function registerBookBdd($user_id, $title, $author, $description, $image, $status, $created_at, $updated_at)
    {
        // Requête d'insertion
        $query = "INSERT INTO books (user_id, title, author, description, image, status, created_at, updated_at) 
                  VALUES (:user_id, :title, :author, :description, :image, :status, :created_at, :updated_at)";
        //stocke la connexion PDO               
        $dbConnection = $this->db->getConnection();
        // Préparation et exécution de la requête 
        $req = $dbConnection->prepare($query);
        // Liaison des paramètres / values
        $req->bindParam(':user_id', $user_id);
        $req->bindParam(':title', $title);
        $req->bindParam(':author', $author);
        $req->bindParam(':description', $description);
        $req->bindParam(':image', $image);
        $req->bindParam(':status', $status);
        $req->bindParam(':created_at', $created_at);
        $req->bindParam(':updated_at', $updated_at);
        $req->execute();
        // Retourne le nombre de lignes affectées, opération réussie si > 0 (renvoie true/false)
        return $req->rowCount() > 0;
    }

    
}
