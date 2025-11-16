<?php
class BookController
{
    public function index()
    {
        echo "Bonjour BookController";
    }

    // Méthode pour afficher le formulaire d'ajout de livre
    public function addBook()
    {
        include('views/books/addBook.php');
    }
    // Méthode pour traiter le formulaire d'ajout de livre en récupérant les données POST
    // appel de la méthode registerBookBdd() du modèle Books
    public function registerBook()
    {
        // Vérification que le formulaire est soumis
        // superglobale $_SERVER qui contient la méthode HTTP pour envoyer la requête vers le serveur
        // pour vérifier la méthode de la requête actuelle = POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation des données du formulaire
            if (empty($_POST['title'])) {
                $message = "Le titre est obligatoire.";
                include('views/books/addBook.php');
                return;
            } elseif (empty($_POST['author'])) {
                $message = "L'auteur est obligatoire.";
                include('views/books/addBook.php');
                return;
            } elseif (empty($_POST['description'])) {
                $message = "La description est obligatoire.";
                include('views/books/addBook.php');
                return;
            } elseif (!isset($_FILES['image']) || !preg_match("#jpeg|jpg|png#", $_FILES['image']['type'])) {
                $message = "L'image est obligatoire et doit être de type jpg, jpeg ou png.";
                include('views/books/addBook.php');
                return;
            } else {
                // Préparation des données à insérer dans la BDD
                // Tableau associatif des données du livre / récupération données du formulaire
                $data = [
                    'user_id' => $_POST['user_id'],
                    'title' => $_POST['title'],
                    'author' => $_POST['author'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')

                ];

                $dateHour = date('YmdHHis'); // Date et heure actuelles pour rendre le nom unique
                $image = $dateHour . '_' . $_FILES['image']['name']; // Préfixer le nom du fichier avec la date et l'heure
                $path = "public/img/";
                move_uploaded_file($_FILES['image']['tmp_name'], $path . $image);
                $data['image'] = $image;

                // Instanciation du modèle Books  avec paramètre $table du constructeur de la classe Books         
                $booksModel = new Books('books');

                // Appel de la méthode pour enregistrer le livre en BDD
                // appel de la méthode registerBookBdd() du modèle Books.php avec son paramètre tableau $data construit ci-dessus lignes 21 à 36
                $isRegistered = $booksModel->registerBookBdd($data);
                // Vérification du résultat de l'enregistrement
                if ($isRegistered) {
                    // message de succès ou redirection
                    //echo "Livre enregistré avec succès.";
                    //redirection vers la liste des livres disponibles
                    header('Location: ' . ROOT . '/book/availableBooks');
                } else {
                    // Message d'erreur
                    $message = "Erreur lors de l'enregistrement du livre.";
                }
            }
        } else {
            // Si le formulaire n'est pas soumis, afficher la page d'ajout de livre
            include('views/books/addBook.php');
        }
    }


    /*
    Méthode pour afficher tous les livres disponibles en appelant la méthode getAllBooks() du modèle Books
    */
    public function availableBooks()
    {
        // Instanciation du BookManager pour utiliser la méthode getAllBooksWithUser()
        $bookManager = new BookManager();
        // Appel de la méthode getAllBooks du principalManager pour récupérer tous les livres en BDD
        $books = $bookManager->getAllBooksWithUser();
        if ($books) {
            // Inclure la vue pour afficher les livres
            include('views/books/availableBooks.php');
        } else {
            $message = "Aucun livre trouvé.";
            include('views/books/availableBooks.php');
        }
        // Inclure la vue pour afficher les livres
        //include('views/books/availableBooks.php');
    }

    // Méthode pour rechercher un livre par son titre
    public function search()
    {
        if (!empty($_POST['q'])) {
            $bookManager = new BookManager();
            $book = $bookManager->getBookByTitle($_POST['q']);

            if ($book) {
                // Redirection vers la page détail du livre trouvé
                header('Location: ' . ROOT . '/book/singleBook/' . $book['id']);
                exit;
            } else {
                $books = $bookManager->getAllBooksWithUser(); // récupère tous les livres
                $message = "Erreur 404- Aucun livre trouvé.";
                include('views/books/availableBooks.php');
            }
        } else {
            // Si champ vide, retour à la liste
            header('Location: ' . ROOT . '/book/availableBooks');
            exit;
        }
    }

    // Méthode pour afficher les détails d'un livre spécifique en appelant la méthode getBookById() de BookManager
    public function singleBook($id = null)
    {
        if ($id !== null) {
            $bookManager = new BookManager();
            $book = $bookManager->getBookById($id);

            if ($book) {
                include('views/books/singleBook.php');
            } else {
                echo "Livre introuvable.";
            }
        } else {
            echo "Aucun livre sélectionné.";
        }
    }

    public function editBook()
    {
        include('views/books/editBook.php');
    }
}
