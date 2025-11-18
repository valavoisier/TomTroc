<?php 
/* 
Modèle pour les opérations sur les livres
 */
// Autochargement des classes
require_once './Autoload.php';

class Books extends BookManager {
    protected $table; // Nom de la table associée au modèle ici 'books'

    public function __construct($table) {
        parent::__construct(); // Appel du constructeur du modèle principal pour initialiser la connexion DB
        $this->table = $table; // Initialisation du nom de la table
    
    }

    // Méthode pour enregistrer un livre en BDD
    public function registerBookBdd($data){
        // Appel de la méthode add() du modèle principal pour insérer les données dans la table
        return $this->add($this->table, $data);
    }
      
    // Méthode pour mettre à jour un livre en BDD
    public function updateBookBdd($id, $data) {
        // Appel de la méthode update() du PrincipalManager
        return $this->update($this->table, $data, $id);
    }
      
}
