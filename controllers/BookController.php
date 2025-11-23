<?php

/**
 * Contrôleur pour la gestion des livres
 * 
 * Cette classe contient les méthodes pour afficher la liste des livres disponibles,
 * afficher le formulaire d'ajout de livre, et enregistrer un nouveau livre en base de données.
 */
class BookController
{
    /**
     * Méthode par défaut pour afficher la liste des livres disponibles
     */
    public function index()
    {
        $this->availableBooks();
    }

    /** 
     * Méthode pour afficher le formulaire d'ajout de livre 
     */
    public function addBook()
    {
        include('views/books/addBook.php');
    }

    /**
     * Méthode registerBook() pour traiter le formulaire d'ajout d'un livre en récupérant les données POST.
     *
     * Cette méthode :
     * - Vérifie que la requête est bien envoyée en POST.
     * - Vérifie que l'utilisateur est connecté (via la session).
     * - Valide les champs obligatoires du formulaire (titre, auteur, description, image).
     * - Prépare les données du livre à insérer dans la base (titre, auteur, description, statut, dates).
     * - Gère l'upload de l'image en renommant le fichier pour éviter les doublons.
     * - Instancie le modèle Books et appelle registerBookBdd() pour insérer le livre en base.
     * - Redirige vers la page du compte utilisateur en cas de succès.
     * - Affiche un message d'erreur ou recharge le formulaire en cas d'échec ou de validation invalide.
     * - Si la requête n'est pas en POST, affiche directement la vue d'ajout de livre.
     * 
     * @return void
     * @uses Books::registerBookBdd() Pour insérer le livre en base de données.
     */
    public function registerBook()
    {
        // Vérification que le formulaire est soumis
        // superglobale $_SERVER qui contient la méthode HTTP pour envoyer la requête vers le serveur
        // pour vérifier la méthode de la requête actuelle = POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier que l'utilisateur est connecté
            if (!isset($_SESSION['user']['id'])) {
                header('Location: ' . ROOT . '/user/login');
                exit;
            }

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
                    'user_id' => $_SESSION['user']['id'],
                    'title' => $_POST['title'],
                    'author' => $_POST['author'],
                    'description' => $_POST['description'],
                    'status' => 1, // Par défaut disponible
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
                    header('Location: ' . ROOT . '/user/account');
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

   /**
     * Méthode availableBooks() pour afficher la liste de tous les livres disponibles.
     *
     * Cette méthode :
     * - Instancie BookManager pour accéder aux données des livres.
     * - Appelle BookManager::getAllBooksWithUser() afin de récupérer tous les livres avec leurs utilisateurs associés.
     * - Si des livres existent, inclut la vue correspondante (views/books/availableBooks.php) pour les afficher.
     * - Si aucun livre n'est trouvé, définit un message d'erreur et inclut la même vue.
     * 
     * @return void
     * @uses BookManager::getAllBooksWithUser() Pour récupérer tous les livres avec leurs utilisateurs.
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
   
    /**
     * Méthode search() pour rechercher un livre par son titre.
     *
     * Cette méthode :
     * - Vérifie que le champ de recherche (`q`) est renseigné dans la requête POST.
     * - Utilise BookManager::getBookByTitle() pour tenter de retrouver un livre correspondant.
     * - Si un livre est trouvé, redirige vers la page de détail du livre.
     * - Si aucun livre n'est trouvé, récupère la liste complète des livres avec leurs utilisateurs
     *   et affiche la vue correspondante avec un message d'erreur.
     * - Si le champ de recherche est vide, redirige vers la liste des livres disponibles.
     * 
     * @return void
     * @uses BookManager::getBookByTitle() Pour rechercher un livre par son titre.
     * @uses BookManager::getAllBooksWithUser() Pour récupérer tous les livres si aucun résultat.
     */
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
                $message = "Aucun livre trouvé.";
                include('views/books/availableBooks.php');
            }
        } else {
            // Si champ vide, retour à la liste
            header('Location: ' . ROOT . '/book/availableBooks');
            exit;
        }
    }

    /**
     * Méthode singleBook() pour afficher les détails d'un livre spécifique.
     *
     * Cette méthode :
     * - Vérifie que l'identifiant du livre est fourni.
     * - Utilise BookManager::getBookById() pour récupérer les informations du livre.
     * - Si le livre existe, inclut la vue correspondante (views/books/singleBook.php).
     * - Si le livre n'existe pas, affiche un message d'erreur.
     * - Si aucun identifiant n'est fourni, affiche un message indiquant qu'aucun livre n'est sélectionné.
     * 
     * @param int|null $id Identifiant du livre à afficher. Si null, aucun livre n'est sélectionné.
     * @return void
     * @uses BookManager::getBookById() Pour récupérer les informations du livre.
     */
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

    /**
     * Méthode editBook() pour éditer un livre existant appartenant à l'utilisateur.
     *
     * Cette méthode gère deux cas :
     * - **GET** : Affiche le formulaire de modification prérempli avec les données du livre.
     * - **POST** : Valide les données envoyées, met à jour le livre en base de données,
     *              gère l'upload d'une nouvelle image (en supprimant l'ancienne si nécessaire),
     *              puis redirige vers la page du compte utilisateur ou réaffiche le formulaire
     *              avec un message d'erreur en cas de problème. 
     * Étapes principales :
     * - Vérifie que l'identifiant du livre est fourni.
     * - Récupère les données du livre via BookManager.
     * - Valide les champs obligatoires (titre, auteur, description).
     * - Prépare les données à mettre à jour (titre, auteur, description, statut, date).
     * - Gère l'image uploadée : suppression de l'ancienne et ajout de la nouvelle.
     * - Met à jour le livre en base via le modèle Books.
     * - Définit des messages d'erreur ou de succès et effectue une redirection.
     * 
     * @param int|null $id Identifiant du livre à modifier. Si null, la requête est considérée invalide.
     * @return void
     * @uses BookManager::getBookById() Pour récupérer les informations du livre.
     * @uses Books::updateBookBdd()     Pour mettre à jour le livre en base de données.
     */
    public function editBook($id = null)
    {
        // Si GET : afficher le formulaire avec les données du livre
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $id !== null) {
            $bookManager = new BookManager();
            $book = $bookManager->getBookById($id);
            if ($book) {
                include('views/books/editBook.php');
            } else {
                echo "Livre introuvable.";
            }
        }
        // Si POST : traiter la modification du livre
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
            // Validation des données
            if (empty($_POST['title'])) {
                $message = "Le titre est obligatoire.";
                $bookManager = new BookManager();
                $book = $bookManager->getBookById($id);
                include('views/books/editBook.php');
                return;
            } elseif (empty($_POST['author'])) {
                $message = "L'auteur est obligatoire.";
                $bookManager = new BookManager();
                $book = $bookManager->getBookById($id);
                include('views/books/editBook.php');
                return;
            } elseif (empty($_POST['description'])) {
                $message = "La description est obligatoire.";
                $bookManager = new BookManager();
                $book = $bookManager->getBookById($id);
                include('views/books/editBook.php');
                return;
            }
            // Préparation des données à mettre à jour
            $data = [
                'title' => $_POST['title'],
                'author' => $_POST['author'],
                'description' => $_POST['description'],
                'status' => isset($_POST['status']) ? (int)$_POST['status'] : 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            // Gestion de l'image si une nouvelle est uploadée
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                if (preg_match("#jpeg|jpg|png#", $_FILES['image']['type'])) {
                    // Supprimer l'ancienne image
                    $bookManager = new BookManager();
                    $oldBook = $bookManager->getBookById($id);
                    if ($oldBook && file_exists('public/img/' . $oldBook['image'])) {
                        unlink('public/img/' . $oldBook['image']);
                    }
                    // Uploader la nouvelle image
                    $dateHour = date('YmdHHis');
                    $image = $dateHour . '_' . $_FILES['image']['name'];
                    $path = "public/img/";
                    move_uploaded_file($_FILES['image']['tmp_name'], $path . $image);
                    $data['image'] = $image;
                }
            }
            // Instanciation du modèle et mise à jour
            $booksModel = new Books('books');
            $isUpdated = $booksModel->updateBookBdd($id, $data);

            if ($isUpdated) {
                header('Location: ' . ROOT . '/user/account');
                exit;
            } else {
                $message = "Erreur lors de la modification du livre.";
                $bookManager = new BookManager();
                $book = $bookManager->getBookById($id);
                include('views/books/editBook.php');
            }
        } else {
            echo "Requête invalide.";
        }
    }

    /*
    * Méthode deleteBook() pour supprimer un livre
    * @param int|null $id Identifiant du livre à supprimer. Si null, aucune suppression n'est effectuée.    *
    * @return void 
    * @uses BookManager::getBookById() Pour récupérer les informations du livre.
    * @uses Books::deleteBookBdd() Pour supprimer le livre de la base de données.
    * Cette méthode :
    * - Vérifie que l'utilisateur est connecté (via la session).
    * - Vérifie que l'identifiant du livre est fourni.
    * - Vérifie que le livre appartient bien à l'utilisateur connecté.
    * - Supprime l'image associée au livre si elle existe dans le dossier public/img.
    * - Supprime le livre de la base de données via le modèle Books.
    * - Définit un message de succès ou d'erreur dans la session.
    * - Redirige l'utilisateur vers la page de son compte.
    */
    public function deleteBook($id = null)
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user']['id'])) {
            header('Location: ' . ROOT . '/user/login');
            exit;
        }
        if ($id !== null) {
            // Vérifier que le livre appartient à l'utilisateur
            $bookManager = new BookManager();
            $book = $bookManager->getBookById($id);

            if ($book && $book['user_id'] == $_SESSION['user']['id']) {
                // Supprimer l'image du livre
                if (isset($book['image']) && file_exists('public/img/' . $book['image'])) {
                    unlink('public/img/' . $book['image']);
                }
                // Supprimer le livre de la BDD
                $booksModel = new Books('books');
                $isDeleted = $booksModel->deleteBookBdd($id);
                if ($isDeleted) {
                    $_SESSION['success'] = "Livre supprimé avec succès.";
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression du livre.";
                }
            } else {
                $_SESSION['error'] = "Vous n'êtes pas autorisé à supprimer ce livre.";
            }
        }
        // Redirection vers le compte
        header('Location: ' . ROOT . '/user/account');
        exit;
    }
}
