<?php
require_once './Autoload.php';
/*
MANAGER pour les opérations spécifiques aux utilisateurs
extends PrincipalManager pour hériter des méthodes génériques
*/ 
class UserManager extends PrincipalManager{

   /*Méthode pour trouver un utilisateur par son email*/
    public function findByEmail($email) {
        // Requête pour récupérer un utilisateur par son email
        $query = "SELECT * FROM users WHERE email = :email";
        //stocke la connexion PDO
        $dbConnection = $this->db->getConnection();
        // Préparation et exécution de la requête
        $req = $dbConnection->prepare($query);
        // Liaison du paramètre email
        $req->bindParam(':email', $email);
        // Exécution de la requête        
        $req->execute();
        // Récupération du résultat sous forme de tableau associatif
        return $req->fetch(PDO::FETCH_ASSOC);
    }

}
