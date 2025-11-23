<?php
require_once './Autoload.php';
/**
 * Classe UserManager
 *
 * Cette classe gère les opérations spécifiques aux utilisateurs.
 * Elle hérite de PrincipalManager afin de réutiliser les méthodes génériques
 * (CRUD : Create, Read, Update, Delete) et ajoute des fonctionnalités propres
 * au modèle `users`.
 *
 * Responsabilités principales :
 * - Fournir des méthodes spécialisées pour interagir avec la table `users`.
 * - Étendre les méthodes génériques du PrincipalManager avec des requêtes ciblées.
 */
class UserManager extends PrincipalManager{

   /**
     * Méthode findByEmail() pour rechercher un utilisateur par son adresse email.
     *
     * Cette méthode :
     * - Construit une requête SQL `SELECT *` filtrée par l'email.
     * - Prépare et exécute la requête via PDO pour sécuriser l'accès aux données.
     * - Lie le paramètre `:email` à la valeur fournie afin d'éviter les injections SQL.
     * - Retourne l'utilisateur correspondant sous forme de tableau associatif.
     * - Retourne `false` si aucun utilisateur n'est trouvé.
     *
     * @param string $email Adresse email de l'utilisateur à rechercher.
     * @return array|false  Tableau associatif représentant l'utilisateur trouvé,
     *                      ou false si aucun résultat.
     */
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
