<?php
declare(strict_types=1);
class BookManager extends AbstractManager
{
    /**
     * Récupère tous les livres avec les informations du propriétaire
     * @return BookWithOwnerDTO[] Tableau de DTOs combinant Book + pseudo et avatar du propriétaire
     */
    public function findAll(): array
    {
        $dbConnection = $this->db->getConnection();

        $query = "SELECT books.*, users.pseudo, users.avatar
                  FROM books
                  JOIN users ON books.user_id = users.id -- (LEFT JOIN utilisé pour tester affichage sans propriétaire)
                  ORDER BY books.id DESC";

        $req = $dbConnection->prepare($query);
        $req->execute();        
        
        $bookDTOs = [];
        // Parcourir les résultats et créer les DTOs
        while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
            $book = new Book(
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
            // Utilisation du DTO pour combiner Book + User en ajoutant pseudo et avatar
            $bookDTOs[] = new BookWithOwnerDTO($book, $data['pseudo'], $data['avatar']);
        }

        return $bookDTOs;
    }

    /**
     * Récupère un livre par son id avec les informations du propriétaire
     * @param int $id ID du livre
     * @return BookWithOwnerDTO|null DTO ou null si non trouvé
     */
    public function getBookById( int $id): ?BookWithOwnerDTO
    {
        $dbConnection = $this->db->getConnection();

        $query = "SELECT books.*, users.pseudo, users.avatar
                  FROM books
                  JOIN users ON books.user_id = users.id
                  WHERE books.id = :id";

        $req = $dbConnection->prepare($query);
        $req->execute([':id' => $id]);
        $data = $req->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $book = new Book(
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
            return new BookWithOwnerDTO($book, $data['pseudo'], $data['avatar']);
        }

        return null;
    }

    /**
     * Récupère un livre par son titre (LIKE) avec les informations du propriétaire
     * @param string $title Titre à rechercher
     * @return BookWithOwnerDTO|null DTO ou null si non trouvé
     */
    public function getBookByTitle($title): ?BookWithOwnerDTO
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
            $book = new Book(
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
            return new BookWithOwnerDTO($book, $data['pseudo'], $data['avatar']);
        }

        return null;
    }

    /**
     * Récupère les derniers livres avec les informations du propriétaire
     * @param int $limit Nombre de livres à récupérer (défaut: 4)
     * @return BookWithOwnerDTO[] Tableau de DTOs
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

        $bookDTOs = [];
        foreach ($rows as $data) {
            $book = new Book(
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
            $bookDTOs[] = new BookWithOwnerDTO($book, $data['pseudo'], $data['avatar']);
        }

        return $bookDTOs;
    }

    // Insère un livre
    public function registerBook(Book $book): bool
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
    
    // Met à jour un livre
    public function updateBook(Book $book): bool
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

    // Supprime un livre
    public function deleteBook($id): bool
    {
        return $this->delete('books', $id);
    }


    // Compte les livres d'un utilisateur
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
     * Récupère les livres par utilisateur avec les informations du propriétaire
     * @param int $userId ID de l'utilisateur
     * @return BookWithOwnerDTO[] Tableau de DTOs
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

        $bookDTOs = [];
        foreach ($results as $data) {
            $book = new Book(
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
            $bookDTOs[] = new BookWithOwnerDTO($book, $data['pseudo'], $data['avatar']);
        }

        return $bookDTOs;
    }
}