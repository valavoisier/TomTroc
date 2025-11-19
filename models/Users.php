<?php 
/* 
MODELE pour les opérations spécifiques aux utilisateurs
* Extends UserManager qui hérite de PrincipalManager pour les opérations génériques
 */
// Autochargement des classes
require_once './Autoload.php';
class Users extends UserManager {
    protected $table; // Nom de la table associée au modèle ici 'users'

    public function __construct($table) {
        parent::__construct(); // Appel du constructeur du modèle principal pour initialiser la connexion DB
        $this->table = $table; // Initialisation du nom de la table
    
    }

    /*
    * Méthode pour enregistrer un utilisateur en BDD
    */
    public function registerUserBdd($data){
        // Appel de la méthode add() du modèle principal pour insérer les données dans la table
        return $this->add($this->table, $data);
    }

    /*
    * Méthode pour mettre à jour les informations de l'utilisateur dans la BDD
    */
    public function updateUserInfo($id, $data) {
    return $this->update($this->table, $data, $id);
    }

    /*
    * Méthode pour récupérer un utilisateur par son ID
    */
    public function getUserById($id) {
        return $this->getById($this->table, $id);
    }


      
}