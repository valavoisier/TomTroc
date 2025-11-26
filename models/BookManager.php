<?php
require_once './Autoload.php';
/*
MANAGER pour les opérations spécifiques aux livres
*/
class BookManager extends AbstractManager
{
    /** Récupère tous les livres (avec pseudo et avatar) 
     * @return array Liste des objets Books avec les informations utilisateur (pseudo, avatar).
     */
    public function findAll(): array
    {
        $dbConnection = $this->db->getConnection();
        $query = "SELECT books.*, users.pseudo, users.avatar
                  FROM books
                  JOIN users ON books.user_id = users.id
                  ORDER BY books.id DESC";
        $req = $dbConnection->prepare($query);
        $req->execute();
        $books = [];
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $book = new Books(
                $data['id'],
                $data['title'],
                $data['author'],
                $data['description'],
                $data['image'],
                $data['user_id'],
                $data['created_at'],
                $data['updated_at'],
                $data['status']
            );
            $book->setPseudo($data['pseudo']);
            $book->setAvatar($data['avatar']);
            $books[] = $book;
        }
        return $books;
    }

    /**
     * Méthode getBookByTitle() pour rechercher un livre par son titre.
     * - Exécute une requête SQL pour rechercher un livre dont le titre correspond partiellement au paramètre fourni.
     * @param string $title Le titre (ou partie du titre) du livre à rechercher.
     * @return Books|null Retourne un objet Books si trouvé, sinon null.
     */
    // Récupère un livre par son titre (LIKE)
    public function getBookByTitle($title): ?Books
    {
        $dbConnection = $this->db->getConnection();
        $query = "SELECT books.*, users.pseudo, users.avatar
                  FROM books
                  JOIN users ON books.user_id = users.id
                  WHERE books.title LIKE :title
                  LIMIT 1";
        $req = $dbConnection->prepare($query);
        $req->execute([':title' => "%$title%"]);
        $data = $req->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $book = new Books(
                $data['id'],
                $data['title'],
                $data['author'],
                $data['description'],
                $data['image'],
                $data['user_id'],
                $data['created_at'],
                $data['updated_at'],
                $data['status']
            );
            $book->setPseudo($data['pseudo']);
            $book->setAvatar($data['avatar']);
            return $book;
        }
        return null;
    }


    /**
     * Méthode getBookById() pour récupérer les détails d'un livre spécifique par son ID,
     * avec les informations de l'utilisateur associé (pseudo, avatar).
     * - Exécute une requête SQL avec une jointure entre les tables `books` et `users`.
     * - Retourne toutes les colonnes du livre (`books.*`) ainsi que :
     *   - Le pseudo, l'avatar et identifiant de l'utilisateur (alias `user_id`).
     * @param int $id Identifiant unique du livre à récupérer.
     * @return array|null Tableau associatif contenant les détails du livre et de l'utilisateur,
     *                    ou null si aucun livre n'est trouvé.
     */
    public function getBookById($id): ?Books
    {
        $dbConnection = $this->db->getConnection();
        $query = "SELECT books.*, users.pseudo, users.avatar, users.id AS user_id -- Récupère toutes les colonnes de la table books et le pseudo, avatar de l'utilisateur
              FROM books
              /* jointure entre les tables books et users sur user_id */
              JOIN users ON books.user_id = users.id -- La condition ON relie chaque livre à son propriétaire dont on récupère les infos associées.
              /*Filtre pour ne récupérer qu’un livre spécifique, identifié par son ID/ :id est un paramètre nommé qui sera lié par execute([':id' => $id]) */
              WHERE books.id = :id";
        $req = $dbConnection->prepare($query);
        $req->execute([':id' => $id]);
        return $req->fetch(PDO::FETCH_ASSOC); //un seul résultat attendu
        if ($data) {
            $book = new Books(
                $data['id'],
                $data['title'],
                $data['author'],
                $data['description'],
                $data['image'],
                $data['user_id'],
                $data['created_at'],
                $data['updated_at'],
                $data['status']
            );
            $book->setPseudo($data['pseudo']);
            $book->setAvatar($data['avatar']);
            return $book;
        }

        return null;
    }

    /**
     * Méthode getLastBooks() pour récupérer les 4 derniers livres ajoutés avec les informations de l'utilisateur associé.
     * - Exécute une requête SQL avec une jointure entre les tables `books` et `users`.
     * - Trie les résultats par ID de livre décroissant (livres les plus récents en premier).
     * - Limite le nombre de résultats retournés (par défaut 4).
     * @param int $limit Nombre maximum de livres à récupérer (par défaut 4).
     * @return array Liste des objets Books avec les informations utilisateur (pseudo, avatar).
     */
    public function getLastBooks(int $limit = 4): array
    {
        $dbConnection = $this->db->getConnection();

        $query = "SELECT books.*, users.pseudo, users.avatar
                  FROM books
                  JOIN users ON books.user_id = users.id
                  ORDER BY books.id DESC
                  LIMIT :limit";

        $req = $dbConnection->prepare($query);
        $req->bindValue(':limit', $limit, PDO::PARAM_INT);
        $req->execute();

        $rows = $req->fetchAll(PDO::FETCH_ASSOC);

        $books = [];
        foreach ($rows as $data) {
            $book = new Books(
                $data['id'],
                $data['title'],
                $data['author'],
                $data['description'],
                $data['image'],
                $data['user_id'],
                $data['created_at'],
                $data['updated_at'],
                $data['status']
            );
            $book->setPseudo($data['pseudo']);
            $book->setAvatar($data['avatar']);
            $books[] = $book;
        }

        return $books;
    }

    /**
     * Méthode pour compter le nombre de livres ajoutés par un utilisateur spécifique    
     * - Exécute une requête SQL sur la table `books` filtrée par l'identifiant utilisateur.
     * - Utilise la fonction d'agrégation COUNT(*) pour obtenir le nombre total de livres.
     * - Retourne ce nombre sous forme d'entier. 
     * @param int $userId Identifiant unique de l'utilisateur.
     * @return int Nombre total de livres ajoutés par l'utilisateur. 
     */
   public function countBooksByUser($userId): int
    {
        $dbConnection = $this->db->getConnection();

        $query = "SELECT COUNT(*) AS total
                  FROM books
                  WHERE user_id = :userId";

        $req = $dbConnection->prepare($query);
        $req->execute([':userId' => $userId]);

        $data = $req->fetch(PDO::FETCH_ASSOC);

        return $data ? (int)$data['total'] : 0;
    }

    /**
     * Méthode getBooksByUserId() pour récupérer tous les livres ajoutés par un utilisateur spécifique.
     * - Exécute une requête SQL sur la table `books` filtrée par l'identifiant utilisateur.
     * - Trie les résultats par date de création décroissante (du plus récent au plus ancien).
     * - Retourne la liste des livres sous forme de tableau associatif.
     * @param int $userId Identifiant unique de l'utilisateur.
     * @return array Liste des objets Books avec les informations utilisateur (pseudo, avatar).
     */
    public function getBooksByUserId($userId): array
    {
        $dbConnection = $this->db->getConnection();

        $query = "SELECT books.*, users.pseudo, users.avatar
                  FROM books
                  JOIN users ON books.user_id = users.id
                  WHERE books.user_id = :userId
                  ORDER BY books.id DESC";

        $req = $dbConnection->prepare($query);
        $req->execute([':userId' => $userId]);

        $results = $req->fetchAll(PDO::FETCH_ASSOC);

        $books = [];
        foreach ($results as $data) {
            $book = new Books(
                $data['id'],
                $data['title'],
                $data['author'],
                $data['description'],
                $data['image'],
                $data['user_id'],
                $data['created_at'],
                $data['updated_at'],
                $data['status']
            );
            $book->setPseudo($data['pseudo']);
            $book->setAvatar($data['avatar']);
            $books[] = $book;
        }

        return $books;
    }
 /* -------------------- CRUD génériques -------------------- */
    /**
     * Insère un livre en base.
     */
     public function registerBook(Books $book): bool
    {       
        $data = [
            'user_id'    => $book->getUserId(),
            'title'      => $book->getTitle(),
            'author'     => $book->getAuthor(),
            'description'=> $book->getDescription(),
            'image'      => $book->getImage(),
            'status'     => $book->getStatus(),
            'created_at' => $book->getCreatedAt(),
            'updated_at' => $book->getUpdatedAt()
        ];
        return $this->add('books', $data);
    }
    
    /**
     * Met à jour un livre existant.
     */
    public function updateBook(Books $book): bool
    {
         $data = [
            'title'      => $book->getTitle(),
            'author'     => $book->getAuthor(),
            'description'=> $book->getDescription(),
            'image'      => $book->getImage(),
            'status'     => $book->getStatus(),
            'updated_at' => $book->getUpdatedAt()
        ];
        return $this->update('books', $data, $book->getId());
    }

    /**
     * Supprime un livre.
     */
    public function deleteBook(int $id): bool
    {
        return $this->delete('books', $id);
    }
}
