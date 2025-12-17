<?php
/**
 * Contrôleur pour la gestion des livres
 * 
 * Cette classe contient les méthodes pour afficher la liste des livres disponibles,
 * afficher le formulaire d'ajout de livre, et enregistrer un nouveau livre en base de données.
 * Elle hérite de AbstractController pour bénéficier des fonctionnalités communes à tous les contrôleurs.
 * Responsabilités principales :
 * - Afficher la liste des livres disponibles.
 * - Gérer l'ajout de nouveaux livres (formulaire et enregistrement).
 * - Gérer la modification et la suppression des livres.
 * Méthodes :
 * - index() : redirige vers availableBooks().
 * - addBook() : affiche le formulaire d'ajout de livre.
 * - registerBook() : traite l'enregistrement d'un nouveau livre.
 * - availableBooks() : affiche la liste des livres disponibles.
 * - search() : gère la recherche de livres par titre.
 * - singleBook($id) : affiche le détail d’un livre.
 * - editBook($id) : gère l’édition d’un livre.
 * - deleteBook($id) : gère la suppression d’un livre.
 * @extends AbstractController
 * 
 * @property BookManager $bookManager Instance de la classe BookManager pour gérer les opérations sur les livres.
 * 
 * @uses BookManager Pour interagir avec la base de données des livres.
 * @uses Books Pour représenter les entités livre.
 * @uses Utils Pour les fonctionnalités utilitaires (ex: confirmations JavaScript).
 */
class BookController extends AbstractController
{
    /** Page d’accueil redirigeant vers la liste des livres disponibles */
    public function index(): void
    {
        $this->availableBooks();
    }

    /** Formulaire d'ajout de livre */
    public function addBook(): void
    {
        $this->render('views/books/addBook.php');
    }

    /**
     * registerBook
     *
     * Méthode qui gère l'enregistrement d'un nouveau livre :
     * - Vérifie que l'utilisateur est connecté
     * - Valide les données du formulaire
     * - Traite l'image uploadée
     * - Crée l'objet Book et l'enregistre via BookManager
     * - Redirige ou affiche un message d'erreur
     * @return void Cette méthode ne retourne rien ; elle effectue des actions (validation, enregistrement, redirection).
     * @uses BookManager::registerBook() Pour enregistrer le livre en base de données.
     * @uses Books Pour représenter l'entité livre.
     * @uses Utils Pour les fonctionnalités utilitaires (ex: confirmations JavaScript).
     */
    public function registerBook(): void
    {
        // Vérifie si la requête est bien envoyée en POST (soumission du formulaire)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification du token CSRF
            $this->verifyCSRF(ROOT . '/book/addBook');
            
            // Vérifier que l'utilisateur est connecté
            if (!isset($_SESSION['user']['id'])) {
                // Si non connecté → redirection vers la page de login
                header('Location: ' . ROOT . '/user/login');
                exit;
            }
            
            // Validation des données du formulaire
            if (empty($_POST['title'])) {
                $message = "Le titre est obligatoire.";
                $this->render('views/books/addBook.php', ['message' => $message, 'formData' => $_POST]);
                return;
            } elseif (empty($_POST['author'])) {
                $message = "L'auteur est obligatoire.";
                $this->render('views/books/addBook.php', ['message' => $message, 'formData' => $_POST]);
                return;
            } elseif (empty($_POST['description'])) {
                $message = "La description est obligatoire.";
                $this->render('views/books/addBook.php', ['message' => $message, 'formData' => $_POST]);
                return;
            }
            
            // Gestion de l'image uploadée (optionnelle)
            $image = 'edit-book.jpg'; // Image par défaut
            
            // Si une image a été uploadée et est valide
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Vérifier le type MIME
                if (preg_match("#jpeg|jpg|png#", $_FILES['image']['type'])) {
                    $dateHour = date('YmdHHis'); // Génère un préfixe basé sur la date/heure pour éviter les doublons
                    $image = $dateHour . '_' . $_FILES['image']['name']; // Nouveau nom du fichier
                    $path = "public/img/cover/"; // Dossier de destination
                    /* move_uploaded_file déplace le fichier téléchargé vers le répertoire spécifié
                     - 1er argument → chemin temporaire ($_FILES['image']['tmp_name'])
                     - 2e argument → destination finale (ici $path . $image)*/
                    move_uploaded_file($_FILES['image']['tmp_name'], $path . $image);
                } else {
                    $message = "L'image doit être de type jpg, jpeg ou png.";
                    $this->render('views/books/addBook.php', ['message' => $message, 'formData' => $_POST]);
                    return;
                }
            }
            
            // Création de l'objet Books (avec les données du formulaire / colonnes de ta table books)
            $book = new Book(
                null, // ID auto-incrémenté → null
                $_POST['title'],
                $_POST['author'],
                $_POST['description'],
                $image, // Nom de l'image uploadée
                $_SESSION['user']['id'], // ID du propriétaire (utilisateur connecté)
                date('Y-m-d H:i:s'), // Date de création
                date('Y-m-d H:i:s'), // Date de mise à jour
                1 // Statusdisponible par défaut
            );
            
            // Enregistrement via BookManager
            $bookManager = new BookManager();
            // Si succès, rediriger vers le compte utilisateur
            if ($bookManager->registerBook($book)) {
                header('Location: ' . ROOT . '/user/account');
                exit;
            } else {
                $message = "Erreur lors de l'enregistrement du livre.";
                $this->render('views/books/addBook.php', ['message' => $message, 'formData' => $_POST]);
            }
        } else {
            // Si la requête n'est pas POST, afficher le formulaire d'ajout
            $this->render('views/books/addBook.php');
        }
    }

    /**
     * availableBooks
     *
     * Méthode qui affiche la liste des livres disponibles :
     * - Récupère tous les livres via BookManager
     * - Passe les résultats à la vue correspondante
     * - Affiche un message si aucun livre n'est trouvé
     * @return void Cette méthode ne retourne rien ; elle effectue des actions (affichage).
     * @uses BookManager::findAll() Pour récupérer tous les livres.
     * @uses BookController::render() Pour afficher la vue avec les données des livres.
     */
    public function availableBooks(): void
    {
        $bookManager = new BookManager();
        
        // Récupérer tous les livres
        $books = $bookManager->findAll();
        if ($books) {
            $this->render('views/books/availableBooks.php', ['books' => $books]);
        } else {
            $message = "Aucun livre trouvé.";
            $this->render('views/books/availableBooks.php', ['message' => $message]);
        }
    }

    /**
     * search
     *
     * Méthode du contrôleur qui gère la recherche de livres par titre :
     * - Vérifie si une requête de recherche a été envoyée via POST
     * - Utilise BookManager pour chercher un livre correspondant au titre
     * - Si trouvé → redirige vers la page du livre (singleBook)
     * - Sinon → recharge la liste des livres disponibles avec un message "Aucun livre trouvé"
     * - Si aucun terme de recherche n'est fourni → redirige vers la liste complète des livres
     * @return void Cette méthode ne retourne rien ; elle effectue des actions (redirection, affichage).
     * @uses BookManager::getBookByTitle() Pour rechercher un livre par titre.
     * @uses BookController::availableBooks() Pour recharger la liste des livres si aucun résultat n'est trouvé.
     */
    public function search(): void
    {
        // Vérification du token CSRF
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCSRF(ROOT . '/book/availableBooks');
        }
        
        // Vérifier si une requête de recherche a été envoyée via POST
        // q : nom du champ de recherche dans le formulaire
        if (!empty($_POST['q'])) {
            
            // Utiliser BookManager pour chercher un livre par titre via getBookByTitle()
            $bookManager = new BookManager();
            $book = $bookManager->getBookByTitle($_POST['q']);
            
            // Si un livre est trouvé, rediriger vers sa page de détail singleBook
            if ($book) {
                header('Location: ' . ROOT . '/book/singleBook/' . $book->getId());
                exit;
            } else {
                // Si aucun livre n'est trouvé, recharger la liste des livres avec un message "Aucun livre trouvé"
                $books = $bookManager->findAll();
                $message = "Aucun livre trouvé.";
                $this->render('views/books/availableBooks.php', ['books' => $books, 'message' => $message]);
            }
        } else {
            // Si aucun terme de recherche n'est fourni, rediriger vers la liste complète des livres
            header('Location: ' . ROOT . '/book/availableBooks');
            exit;
        }
    }

    /**
     * singleBook
     *
     * Méthode qui affiche le détail d’un livre :
     * - Vérifie qu’un identifiant est fourni
     * - Récupère le livre correspondant via BookManager
     * - Si trouvé → rend la vue singleBook avec l’objet $book
     * - Sinon → affiche un message "Livre introuvable"
     * - Si aucun identifiant n’est passé → affiche "Aucun livre sélectionné"
     * @param int|null $id Identifiant du livre à afficher
     * @return void Cette méthode ne retourne rien ; elle effectue des actions (affichage, message d'erreur).
     */
    public function singleBook($id = null): void
    {
        // Vérifier qu’un identifiant est fourni
        if ($id !== null) {
            $bookManager = new BookManager();
            
            // Récupérer le livre par son ID
            $book = $bookManager->getBookById($id);
            
            // Si le livre est trouvé, rendre la vue avec les données du livre
            if ($book) {
                $this->render('views/books/singleBook.php', ['book' => $book]);
            } else {
                echo "Livre introuvable.";
            }
        } else {
            echo "Aucun livre sélectionné.";
        }
    }

    /**
     * editBook
     *
     * Méthode qui gère l’édition d’un livre :
     * - Si requête GET avec un ID → affiche le formulaire prérempli avec les données du livre
     * - Si requête POST avec un ID → valide les champs, met à jour l’objet Book et gère l’upload d’une nouvelle image
     * - Si le livre est trouvé et la mise à jour réussie → redirige vers la page détail du livre
     * - Sinon → affiche un message d’erreur ou "Livre introuvable"
     * - Si aucune requête valide ou pas d’ID → affiche "Requête invalide"
     * @param int|null $id Identifiant du livre à éditer
     * @return void Cette méthode ne retourne rien ; elle effectue des actions (affichage, mise à jour, redirection).
     */
    public function editBook($id = null): void
    {
        $bookManager = new BookManager();
        // Si la requête est en GET et qu'un ID est fourni → afficher le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $id !== null) {
            
            // Récupérer le livre par son ID
            $book = $bookManager->getBookById($id);
            
            if ($book) {
                // Rendre la vue avec les données du livre
                $this->render('views/books/editBook.php', ['book' => $book]);
            } else {
                echo "Livre introuvable.";
            }
        }
        // Si la requête est en POST et qu'un ID est fourni → traiter la modification
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== null) {
            
            // Vérification du token CSRF
            $this->verifyCSRF(ROOT . '/book/editBook/' . $id);
            
            // Récupérer le livre par son ID
            $book = $bookManager->getBookById($id);
            
            // Vérifier que le livre existe
            if (!$book) {
                echo "Livre introuvable.";
                return;
            }
            
            // Validation des données /champs obligatoires
            if (empty($_POST['title'])) {
                $message = "Le titre est obligatoire.";
                $this->render('views/books/editBook.php', ['book' => $book, 'message' => $message, 'formData' => $_POST]);
                return;
            } elseif (empty($_POST['author'])) {
                $message = "L'auteur est obligatoire.";
                $this->render('views/books/editBook.php', ['book' => $book, 'message' => $message, 'formData' => $_POST]);
                return;
            } elseif (empty($_POST['description'])) {
                $message = "La description est obligatoire.";
                $this->render('views/books/editBook.php', ['book' => $book, 'message' => $message, 'formData' => $_POST]);
                return;
            }
            
            // Récupérer l'objet Book depuis le DTO pour pouvoir le modifier
            $bookEntity = $book->getBook();
            
            // Mise à jour des propriétés de l'objet
            $bookEntity->setTitle($_POST['title']);
            $bookEntity->setAuthor($_POST['author']);
            $bookEntity->setDescription($_POST['description']);
            $bookEntity->setStatus(isset($_POST['status']) ? (int)$_POST['status'] : 1);
            $bookEntity->setUpdatedAt(date('Y-m-d H:i:s'));
            
            // Gestion de l'image si une nouvelle est uploadée
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Vérifier le type de l'image
                if (preg_match("#jpeg|jpg|png#", $_FILES['image']['type'])) {
                    // Supprimer l'ancienne image (sauf si c'est l'image par défaut)
                    if ($bookEntity->getImage() && $bookEntity->getImage() !== 'edit-book.jpg' && file_exists('public/img/cover/' . $bookEntity->getImage())) {
                        // Supprimer l'ancienne image du serveur
                        unlink('public/img/cover/' . $bookEntity->getImage());
                    }
                    // Uploader la nouvelle image
                    $dateHour = date('YmdHHis');// Génère un préfixe basé sur la date/heure pour éviter les doublons
                    $image = $dateHour . '_' . $_FILES['image']['name'];// Nouveau nom du fichier
                    $path = "public/img/cover/";// Dossier de destination
                    // Déplacer le fichier uploadé vers le dossier de destination
                    move_uploaded_file($_FILES['image']['tmp_name'], $path . $image);
                    // Mettre à jour le nom de l'image dans l'objet Book
                    $bookEntity->setImage($image);
                }
            }
            
            // Mise à jour via BookManager
            if ($bookManager->updateBook($bookEntity)) {
                // Si succès, rediriger vers la page détail du livre
                header('Location: ' . ROOT . '/book/singleBook/' . $bookEntity->getId());
                exit;
            } else {
                // En cas d'erreur lors de la mise à jour
                $message = "Erreur lors de la modification du livre.";
                // Rendre à nouveau le formulaire avec le message d'erreur
                $this->render('views/books/editBook.php', ['book' => $book, 'message' => $message, 'formData' => $_POST]);
            }
        } else {
            // Si aucune requête valide ou pas d’ID → afficher un message d’erreur
            echo "Requête invalide.";
        }
    }

    /** 
     * deleteBook 
     * 
     * Méthode qui gère la Suppression d’un livre :
     * - Vérifie qu’un identifiant de livre est bien fourni
     * - Appelle la méthode deleteBook() du BookManager pour supprimer le livre
     * - Si la suppression réussit → redirige vers la liste des livres disponibles
     * - Sinon → affiche un message d’erreur
     * - Si aucun identifiant n’est passé → affiche "Aucun livre sélectionné"
     * @param int|null $id Identifiant du livre à supprimer
     * @return void Cette méthode ne retourne rien ; elle effectue des actions (suppression, redirection, affichage).
    */
    public function deleteBook($id = null): void
    {
        // Vérifie qu'un identifiant de livre est bien fourni
        if ($id !== null) {
            $bookManager = new BookManager();
            
            // Récupérer le livre pour obtenir le nom de l'image
            $book = $bookManager->getBookById($id);
            
            // Appelle la méthode du BookManager pour supprimer le livre
            if ($bookManager->deleteBook($id)) {
                // Supprimer l'image physique (sauf si c'est l'image par défaut)
                if ($book && $book->getImage() && $book->getImage() !== 'edit-book.jpg' && file_exists('public/img/cover/' . $book->getImage())) {
                    unlink('public/img/cover/' . $book->getImage());
                }
                // Si la suppression réussit → redirection vers la liste des livres disponibles
                header('Location: ' . ROOT . '/book/availableBooks');
                exit;
            } else {
                // Si la suppression échoue → message d'erreur
                echo "Erreur lors de la suppression.";
            }
        } else {
            // Si aucun identifiant n'est passé → message indiquant qu'aucun livre n'est sélectionné
            echo "Aucun livre sélectionné.";
        }
    }
}
