<?php
require_once './Autoload.php';
/*
MANAGER pour les opérations spécifiques aux livres
*/ 
class BookManager extends PrincipalManager
{
    /**
     * Méthode getAllBooksWithUser() pour récupérer tous les livres avec le pseudo de l'utilisateur associé.
     *
     * Cette méthode :
     * - Exécute une requête SQL avec une jointure entre les tables `books` et `users` pour obtenir le pseudo de l'utilisateur associé à chaque livre.
     * - Retourne chaque livre avec toutes ses colonnes (`books.*`) et le pseudo de l'utilisateur.
     * - Permet de remplacer la méthode générique getAll() qui ne renvoyait que l'user_id.
     * - (Peut être adaptée en LEFT JOIN pour inclure les livres sans utilisateur associé dans le cadre du test d'ajout en dehors de la session avec user_id null en bdd).
     *
     * @return array Liste des livres avec les informations utilisateur (tableau associatif).
     */
    public function getAllBooksWithUser()
    {
        $dbConnection = $this->db->getConnection();
        // Requête pour récupérer tous les livres avec le pseudo de l'utilisateur
        // jointure entre les tables books et users sur user_id
        //La condition ON books.user_id = users.id relie chaque livre à l’utilisateur qui l’a ajouté. Résultat : pour chaque livre, on récupère aussi le pseudo de l’utilisateur associé.
        $query = "SELECT books.*, users.pseudo 
                  FROM books 
                  JOIN users ON books.user_id = users.id";
                  //utiliser LEFT JOIN pour inclure tous les livres même s'ils n'ont pas d'utilisateur associé dans le cadre du test d'ajout en dehors de la session avec user_id null en bdd
        $req = $dbConnection->prepare($query);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode getBookByTitle() pour rechercher un livre par son titre.
     *     
     * Cette méthode :
     * - Exécute une requête SQL pour rechercher un livre dont le titre correspond partiellement au paramètre fourni.
     * - Retourne uniquement les données de base pour identifier le livre.  
     * 
     * @param string $title Le titre (ou partie du titre) du livre à rechercher.
     * @return array|false Les données du livre trouvé sous forme de tableau associatif, ou false si aucun livre trouvé.
     */
    public function getBookByTitle($title)
    {
        $dbConnection = $this->db->getConnection();
        // Requête pour rechercher un livre par son titre (utilisation de LIKE pour correspondance partielle)
        $query = "SELECT * FROM books WHERE title LIKE :title LIMIT 1";
        $req = $dbConnection->prepare($query);
        $req->execute([':title' => "%$title%"]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    
     /**
     * Méthode getBookById() pour récupérer les détails d'un livre spécifique par son ID,
     * avec les informations de l'utilisateur associé (pseudo, avatar).
     *
     * Cette méthode :
     * - Exécute une requête SQL avec une jointure entre les tables `books` et `users`.
     * - Retourne toutes les colonnes du livre (`books.*`) ainsi que :
     *   - Le pseudo de l'utilisateur.
     *   - L'avatar de l'utilisateur.
     *   - L'identifiant de l'utilisateur (alias `user_id`).
     * - Permet d'obtenir une vue enrichie du livre incluant son auteur/utilisateur.
     * 
     * @param int $id Identifiant unique du livre à récupérer.
     * @return array|null Tableau associatif contenant les détails du livre et de l'utilisateur,
     *                    ou null si aucun livre n'est trouvé.
     */
     public function getBookById($id)
    {
        $dbConnection = $this->db->getConnection();
        // Requête pour récupérer un livre spécifique avec le pseudo et avatar de l'utilisateur (séléctionne toutes les colonnes de la table books et ajoute les colonnes pseudo et avatar)
        // jointure entre les tables books et users sur user_id
        // séléctionne l'ID de l'utilisateur (user.id et le renomme user_id pour éviter les conflits avec books.id)
        // La condition ON books.user_id = users.id relie chaque livre à l’utilisateur qui l’a ajouté. Résultat : pour chaque livre, on récupère aussi les infos de l’utilisateur associé.
        // WHERE books.id = :id (Filtre pour ne récupérer qu’un livre spécifique, identifié par son ID).:id est un paramètre nommé qui sera lié par execute([':id' => $id]).
        $query = "SELECT books.*, users.pseudo, users.avatar, users.id AS user_id
              FROM books
              JOIN users ON books.user_id = users.id
              WHERE books.id = :id";
        $req = $dbConnection->prepare($query);
        $req->execute([':id' => $id]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Méthode getLastBooks() pour récupérer les 4 derniers livres ajoutés avec les informations de l'utilisateur associé.
     *
     * Cette méthode :
     * - Exécute une requête SQL avec une jointure entre les tables `books` et `users`.
     * - Retourne toutes les colonnes du livre (`books.*`) ainsi que :
     *   - Le pseudo de l'utilisateur.
     *   - L'avatar de l'utilisateur.
     *   - L'identifiant de l'utilisateur (alias `user_id`).
     * - Trie les résultats par ID de livre décroissant (livres les plus récents en premier).
     * - Limite le nombre de résultats retournés (par défaut 4).
     * 
     * @param int $limit Nombre maximum de livres à récupérer (par défaut 4).
     * @return array Liste des livres avec les informations utilisateur (tableau associatif).
     */
     public function getLastBooks($limit = 4){
        $dbConnection = $this->db->getConnection();
        // Requête pour récupérer les derniers livres avec le pseudo et avatar de l'utilisateur
        // jointure entre les tables books et users sur user_id
        // tri par id décroissant et limitation du nombre de résultats
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

    /**
    * Méthode pour compter le nombre de livres ajoutés par un utilisateur spécifique
    *
    * Cette méthode :
    * - Exécute une requête SQL sur la table `books` filtrée par l'identifiant utilisateur.
    * - Utilise la fonction d'agrégation COUNT(*) pour obtenir le nombre total de livres.
    * - Retourne ce nombre sous forme d'entier.
    * 
    * @param int $userId Identifiant unique de l'utilisateur.
    * @return int Nombre total de livres ajoutés par cet utilisateur.
    */
    public function countBooksByUser($userId) {
        // Requête SQL qui compte le nombre de lignes dans la table "books"
        // filtrées par l'identifiant de l'utilisateur (user_id)
        $query = "SELECT COUNT(*) as total FROM books WHERE user_id = :id";
        // Récupération de la connexion à la base de données via l'objet $this->db
        $dbConnection = $this->db->getConnection();
         // Préparation de la requête SQL pour éviter les injections
        $req = $dbConnection->prepare($query);
        // Liaison du paramètre :id avec la valeur $userId
        // PDO::PARAM_INT garantit que la valeur est traitée comme un entier
        $req->bindParam(':id', $userId, PDO::PARAM_INT);
        // Exécution de la requête
        $req->execute();
        // Récupération du résultat sous forme de tableau associatif
        // ['total' => nombre_de_livres]
        // On retourne directement la valeur du champ 'total'
        return $req->fetch(PDO::FETCH_ASSOC)['total'];
    }

   /**
     * Méthode getBooksByUserId() pour compter le nombre de livres ajoutés par un utilisateur spécifique.
     *
     * Cette méthode :
     * - Exécute une requête SQL sur la table `books` filtrée par l'identifiant utilisateur.
     * - Utilise une clause COUNT(*) pour obtenir le nombre total de livres associés.
     * - Retourne ce nombre sous forme d'entier.
     * 
     * @param int $userId Identifiant unique de l'utilisateur dont on veut compter les livres.
     * @return int Nombre total de livres ajoutés par l'utilisateur.
     */
    public function getBooksByUserId($userId) {
        $dbConnection = $this->db->getConnection();
        // Requête SQL pour récupérer tous les livres d'un utilisateur spécifique, triés par date de création décroissante
        $query = "SELECT * FROM books WHERE user_id = :id ORDER BY created_at DESC";
        $req = $dbConnection->prepare($query);
        $req->bindParam(':id', $userId, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
   
}