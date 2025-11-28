<?php
class BookManager extends AbstractManager
{
    // Récupère tous les livres (avec pseudo et avatar)
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

    // Récupère un livre par son id
    public function getBookById($id): ?Books
    {
        $dbConnection = $this->db->getConnection();

        $query = "SELECT books.*, users.pseudo, users.avatar
                  FROM books
                  JOIN users ON books.user_id = users.id
                  WHERE books.id = :id";

        $req = $dbConnection->prepare($query);
        $req->execute([':id' => $id]);
        $data = $req->fetch(PDO::FETCH_ASSOC);//un seul résultat attendu

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

    // Récupère les derniers livres (limit)
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

    // Insère un livre
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
    
    // Met à jour un livre
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

    // Récupère les livres par utilisateur
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
}