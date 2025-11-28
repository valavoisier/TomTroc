<?php
/**
 * Contrôleur pour la gestion des livres
 * 
 * Cette classe contient les méthodes pour afficher la liste des livres disponibles,
 * afficher le formulaire d'ajout de livre, et enregistrer un nouveau livre en base de données.
 */
class BookController extends AbstractController
{
    public function index()
    {
        $this->availableBooks();
    }

    // Formulaire d'ajout de livre
    public function addBook()
    {
        $this->render('views/books/addBook.php');
    }

    // Enregistrement d’un nouveau livre
    public function registerBook()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier que l'utilisateur est connecté
            if (!isset($_SESSION['user']['id'])) {
                header('Location: ' . ROOT . '/user/login');
                exit;
            }

            // Validation des données du formulaire
            if (empty($_POST['title'])) {
                $message = "Le titre est obligatoire.";
                $this->render('views/books/addBook.php', ['message' => $message]);
                return;
            } elseif (empty($_POST['author'])) {
                $message = "L'auteur est obligatoire.";
                $this->render('views/books/addBook.php', ['message' => $message]);
                return;
            } elseif (empty($_POST['description'])) {
                $message = "La description est obligatoire.";
                $this->render('views/books/addBook.php', ['message' => $message]);
                return;
            } elseif (!isset($_FILES['image']) || !preg_match("#jpeg|jpg|png#", $_FILES['image']['type'])) {
                $message = "L'image est obligatoire et doit être de type jpg, jpeg ou png.";
                $this->render('views/books/addBook.php', ['message' => $message]);
                return;
            }

            // Gestion de l'image
            $dateHour = date('YmdHHis');
            $image = $dateHour . '_' . $_FILES['image']['name'];
            $path = "public/img/";
            move_uploaded_file($_FILES['image']['tmp_name'], $path . $image);

            // Création de l’objet Books
            $book = new Books(
                null,
                $_POST['title'],
                $_POST['author'],
                $_POST['description'],
                $image,
                $_SESSION['user']['id'],
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s'),
                1 // disponible par défaut
            );

            // Enregistrement via BookManager
            $bookManager = new BookManager();
            if ($bookManager->registerBook($book)) {
                header('Location: ' . ROOT . '/user/account');
                exit;
            } else {
                $message = "Erreur lors de l'enregistrement du livre.";
                $this->render('views/books/addBook.php', ['message' => $message]);
            }
        } else {
            $this->render('views/books/addBook.php');
        }
    }

    // Liste des livres disponibles
    public function availableBooks()
    {
        $bookManager = new BookManager();
        $books = $bookManager->findAll();

        if ($books) {
            $this->render('views/books/availableBooks.php', ['books' => $books]);
        } else {
            $message = "Aucun livre trouvé.";
            $this->render('views/books/availableBooks.php', ['message' => $message]);
        }
    }

    // Recherche par titre
    public function search()
    {
        if (!empty($_POST['q'])) {
            $bookManager = new BookManager();
            $book = $bookManager->getBookByTitle($_POST['q']);

            if ($book) {
                header('Location: ' . ROOT . '/book/singleBook/' . $book->getId());
                exit;
            } else {
                $books = $bookManager->findAll();
                $message = "Aucun livre trouvé.";
                $this->render('views/books/availableBooks.php', ['books' => $books, 'message' => $message]);
            }
        } else {
            header('Location: ' . ROOT . '/book/availableBooks');
            exit;
        }
    }

    // Détail d’un livre
    public function singleBook($id = null)
    {
        if ($id !== null) {
            $bookManager = new BookManager();
            $book = $bookManager->getBookById($id);

            if ($book) {
                $this->render('views/books/singleBook.php', ['book' => $book]);
            } else {
                echo "Livre introuvable.";
            }
        } else {
            echo "Aucun livre sélectionné.";
        }
    }

    // Edition d’un livre
    public function editBook($id = null)
    {
        $bookManager = new BookManager();

        // Si GET : afficher le formulaire avec les données du livre
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $id !== null) {
            $book = $bookManager->getBookById($id);
            if ($book) {
                $this->render('views/books/editBook.php', ['book' => $book]);
            } else {
                echo "Livre introuvable.";
            }
        }
        // Si POST : traiter la modification du livre
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
            $book = $bookManager->getBookById($id);

            if (!$book) {
                echo "Livre introuvable.";
                return;
            }

            // Validation des données
            if (empty($_POST['title'])) {
                $message = "Le titre est obligatoire.";
                $this->render('views/books/editBook.php', ['book' => $book, 'message' => $message]);
                return;
            } elseif (empty($_POST['author'])) {
                $message = "L'auteur est obligatoire.";
                $this->render('views/books/editBook.php', ['book' => $book, 'message' => $message]);
                return;
            } elseif (empty($_POST['description'])) {
                $message = "La description est obligatoire.";
                $this->render('views/books/editBook.php', ['book' => $book, 'message' => $message]);
                return;
            }

            // Mise à jour des propriétés de l’objet
            $book->setTitle($_POST['title']);
            $book->setAuthor($_POST['author']);
            $book->setDescription($_POST['description']);
            $book->setStatus(isset($_POST['status']) ? (int)$_POST['status'] : 1);
            $book->setUpdatedAt(date('Y-m-d H:i:s'));

            // Gestion de l'image si une nouvelle est uploadée
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                if (preg_match("#jpeg|jpg|png#", $_FILES['image']['type'])) {
                    // Supprimer l'ancienne image
                    if ($book->getImage() && file_exists('public/img/' . $book->getImage())) {
                        unlink('public/img/' . $book->getImage());
                    }
                    // Uploader la nouvelle image
                    $dateHour = date('YmdHHis');
                    $image = $dateHour . '_' . $_FILES['image']['name'];
                    $path = "public/img/";
                    move_uploaded_file($_FILES['image']['tmp_name'], $path . $image);
                    $book->setImage($image);
                }
            }

            // Mise à jour via BookManager
            if ($bookManager->updateBook($book)) {
                header('Location: ' . ROOT . '/book/singleBook/' . $book->getId());
                exit;
            } else {
                $message = "Erreur lors de la modification du livre.";
                $this->render('views/books/editBook.php', ['book' => $book, 'message' => $message]);
            }
        } else {
            echo "Requête invalide.";
        }
    }

    // Suppression d’un livre
    public function deleteBook($id = null)
    {
        if ($id !== null) {
            $bookManager = new BookManager();
            if ($bookManager->deleteBook($id)) {
                header('Location: ' . ROOT . '/book/availableBooks');
                exit;
            } else {
                echo "Erreur lors de la suppression.";
            }
        } else {
            echo "Aucun livre sélectionné.";
        }
    }
}
