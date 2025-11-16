<?php
require_once './Autoload.php';
/*
MANAGER pour les opérations spécifiques aux livres
*/ 
class BookManager extends PrincipalManager
{
/* Méthode pour récupérer tous les livres avec le pseudo de l'utilisateur
la méthode utilise une jointure entre les tables books et users pour obtenir le pseudo de l'utilisateur associé à chaque livre
remplace la méthode getAll() générique du PrincipalManager qui ne récupérait que les données de la table books donc user-id et non le pseudo
*/
    public function getAllBooksWithUser()
    {
        $dbConnection = $this->db->getConnection();
        // Requête pour récupérer tous les livres avec le pseudo de l'utilisateur
        // jointure entre les tables books et users sur user_id
        $query = "SELECT books.*, users.pseudo 
                  FROM books 
                  JOIN users ON books.user_id = users.id";
                  //utiliser LEFT JOIN pour inclure tous les livres même s'ils n'ont pas d'utilisateur associé dans le cadre du test d'ajout en dehors de la session avec user_id null en bdd
        $req = $dbConnection->prepare($query);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
    
     // Méthode pour récupérer les détails d'un livre spécifique par son ID avec les informations de l'utilisateur (pseudo, avatar)
     public function getBookById($id)
    {
        $dbConnection = $this->db->getConnection();
        $query = "SELECT books.*, users.pseudo, users.avatar, users.id AS user_id
              FROM books
              JOIN users ON books.user_id = users.id
              WHERE books.id = :id";
        $req = $dbConnection->prepare($query);
        $req->execute([':id' => $id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer les 4 derniers livres ajoutés avec les informations de l'utilisateur (pseudo, avatar)
     public function getLastBooks($limit = 4){
    $dbConnection = $this->db->getConnection();
    $query = "SELECT books.*, users.pseudo, users.avatar, users.id AS user_id
              FROM books
              JOIN users ON books.user_id = users.id
              ORDER BY books.id DESC
              LIMIT :limit";
    $req = $dbConnection->prepare($query);
    $req->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

    
    

   
}