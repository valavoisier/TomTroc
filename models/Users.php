<?php 
// Autochargement des classes
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
 * - Rechercher un utilisateur par email ou pseudo.
 * - Créer un nouvel utilisateur.
 * - Mettre à jour ou supprimer un utilisateur.
 *
 * @property Database $db Instance de la classe Database utilisée pour exécuter les requêtes SQL.
 */
class Users extends UserManager {
    protected $table; // Nom de la table associée au modèle ici 'users'

    public function __construct($table) {
        parent::__construct(); // Appel du constructeur du modèle principal pour initialiser la connexion DB
        $this->table = $table; // Initialisation du nom de la table
    
    }

    /**
     * Méthode registerUserBdd() pour enregistrer un nouvel utilisateur en base de données.
     *
     * Cette méthode :
     * - Appelle la méthode générique add() du PrincipalManager.
     * - Insère les données fournies dans la table `users`.
     * - Retourne true si l'insertion réussit, false sinon.
     *
     * @param array $data Tableau associatif [colonne => valeur] représentant les informations de l'utilisateur.
     * @return bool       True si l'enregistrement réussit, False sinon.
     */
    public function registerUserBdd($data){
        // Appel de la méthode add() du modèle principal pour insérer les données dans la table
        return $this->add($this->table, $data);
    }

    /**
     * Méthode updateUserInfo() pour mettre à jour les informations d'un utilisateur en BDD.
     *
     * Cette méthode :
     * - Appelle la méthode générique update() du PrincipalManager.
     * - Met à jour les colonnes spécifiées dans $data pour l'utilisateur identifié par $id.
     * - Retourne true si la mise à jour réussit, false sinon.
     *
     * @param int   $id   Identifiant unique de l'utilisateur à mettre à jour.
     * @param array $data Tableau associatif [colonne => valeur] représentant les nouvelles informations.
     * @return bool       True si la mise à jour réussit, False sinon.
     */
    public function updateUserInfo($id, $data) {
        return $this->update($this->table, $data, $id);
    }

    /**
     * Méthode getUserById() pour récupérer un utilisateur par son identifiant.
     *
     * Cette méthode :
     * - Appelle la méthode générique getById() du PrincipalManager.
     * - Exécute une requête SQL `SELECT *` filtrée par l'ID.
     * - Retourne l'utilisateur sous forme de tableau associatif ou null si aucun résultat.
     *
     * @param int $id Identifiant unique de l'utilisateur à récupérer.
     * @return array|null Tableau associatif représentant l'utilisateur trouvé,
     *                    ou null si aucun enregistrement ne correspond.
     */
    public function getUserById($id) {
        return $this->getById($this->table, $id);
    }
      
}