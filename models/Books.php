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

    /**
     * Méthode registerBookBdd() pour enregistrer un nouveau livre en base de données.
     *
     * Cette méthode :
     * - Utilise la méthode générique add() du PrincipalManager.
     * - Insère les données fournies dans la table définie par $this->table (ici `books`).
     *
     * @param array $data Données du livre à insérer (titre, auteur, description, etc.).
     * @return bool Résultat de l'opération (true si l'insertion réussit, false sinon).
     */
    public function registerBookBdd($data){
        // Appel de la méthode add() du modèle principal pour insérer les données dans la table
        return $this->add($this->table, $data);
    }

    /**
     * Méthode updateBookBdd() pour mettre à jour un livre existant en base de données.
     *
     * Cette méthode :
     * - Utilise la méthode générique update() du PrincipalManager.
     * - Met à jour les colonnes de la table `books` avec les nouvelles données.
     * - Cible le livre grâce à son identifiant unique.
     *
     * @param int   $id   Identifiant du livre à mettre à jour.
     * @param array $data Données à modifier (titre, auteur, description, etc.).
     * @return bool Résultat de l'opération (true si la mise à jour réussit, false sinon).
     */
    public function updateBookBdd($id, $data) {
        // Appel de la méthode update() du PrincipalManager
        return $this->update($this->table, $data, $id);
    }

    /**
     * Méthode deleteBookBdd() pour supprimer un livre en base de données.
     *
     * Cette méthode :
     * - Utilise la méthode générique delete() du PrincipalManager.
     * - Supprime la ligne correspondante dans la table `books`.
     *
     * @param int $id Identifiant du livre à supprimer.
     * @return bool Résultat de l'opération (true si la suppression réussit, false sinon).
     */
    public function deleteBookBdd($id) {
        // Appel de la méthode delete() du PrincipalManager
        return $this->delete($this->table, $id);
    }
      
}
