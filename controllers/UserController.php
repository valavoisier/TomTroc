<?php

/**
 * Contrôleur des utilisateurs.
 *
 * Cette classe gère les actions liées aux utilisateurs (inscription, connexion, profil, etc.).
 */
class UserController
{
    /**
     * Instance du modèle Users utilisée pour interagir avec la table `users`.
     *
     * @var Users
     */
    private $users;

    /**
     * Constructeur du contrôleur.
     *
     * - Instancie le modèle Users avec la table `users`.
     * - Permet d'accéder aux méthodes du modèle pour gérer les données utilisateur.
     */
    public function __construct()
    {
        $this->users = new Users('users');
    }
    /**
     * Méthode  index() pour afficher la page d'accueil du compte utilisateur.
     *
     * Cette méthode :
     * - Sert de point d'entrée pour l'action "index".
     * - Redirige directement vers la méthode account() afin d'éviter la duplication de code.
     * - Permet de centraliser la logique de gestion du compte utilisateur dans une seule méthode.
     * @return void
     * @see self::account() Méthode appelée pour afficher le compte utilisateur.
     */
    public function index()
    {
        // Redirection vers la méthode account pour éviter la duplication de code
        $this->account();
    }

    /**
     * Méthode publicAccount() pour afficher la page de profil public d'un utilisateur.
     *
     * Cette méthode :
     * - Vérifie que l'identifiant de l'utilisateur est fourni, sinon redirige vers la liste des livres disponibles.
     * - Récupère les informations de l'utilisateur via Users::getUserById().
     * - Si l'utilisateur n'existe pas, affiche un message d'erreur.
     * - Instancie BookManager pour :
     *   - Compter le nombre de livres associés à l'utilisateur.
     *   - Récupérer la liste complète des livres de l'utilisateur.
     * - Calcule la durée d'adhésion ("Membre depuis") à partir de la date de création du compte.
     * - Inclut la vue correspondante (views/users/publicAccount.php) avec les variables disponibles
     *   ($user, $bookCount, $memberSince, $userBooks).
     * @param int|null $id Identifiant de l'utilisateur dont on veut afficher le profil public.
     *                     Si null, redirection vers la liste des livres disponibles.
     * @return void
     * @uses Users::getUserById() Pour récupérer les informations de l'utilisateur.
     * @uses BookManager::countBooksByUser() Pour compter le nombre de livres de l'utilisateur.
     * @uses BookManager::getBooksByUserId() Pour récupérer tous les livres de l'utilisateur.
     */
    public function publicAccount($id = null)
    {
        // Vérifier que l'ID est fourni
        if ($id === null) {
            header("Location: " . ROOT . "/book/availableBooks");
            exit;
        }
        // Récupérer les informations de l'utilisateur
        $user = $this->users->getUserById($id);
        if (!$user) {
            echo "Utilisateur introuvable.";
            return;
        }
        // Instancier BookManager et récupérer le nombre de livres
        $bookManager = new BookManager();
        $bookCount = $bookManager->countBooksByUser($id);
        // Calcul "Membre depuis"
        $createdAt = new DateTime($user['created_at']);
        $now = new DateTime();
        $interval = $createdAt->diff($now);
        $memberSince = $interval->y > 0
            ? "Membre depuis {$interval->y} an" . ($interval->y > 1 ? "s" : "")
            : "Membre depuis moins d'un an";
        // Récupérer tous les livres de l'utilisateur
        $userBooks = $bookManager->getBooksByUserId($id);
        // Inclure la vue avec les variables disponibles
        include('views/users/publicAccount.php');
    }

    /**
     * Méthode register() pour gérer l'inscription des utilisateurs.
     *
     * Cette méthode :
     * - Vérifie si la requête est envoyée en POST (soumission du formulaire).
     * - Effectue une vérification CSRF via un token stocké en session.
     * - Valide les champs obligatoires :
     *   - Pseudo (alphabétique uniquement).
     *   - Email (format valide).
     *   - Mot de passe (non vide et conforme aux règles de complexité).
     * - Nettoie les données pour éviter les failles XSS.
     * - Vérifie si l'email existe déjà en base via Users::findByEmail().
     * - Prépare les données à insérer (pseudo, email, mot de passe hashé, date de création).
     * - Appelle Users::registerUserBdd() pour enregistrer l'utilisateur en base.
     * - Redirige vers la page de login en cas de succès.
     * - Affiche un message d'erreur et recharge la vue en cas d'échec ou de validation invalide.
     * - En GET, génère un nouveau token CSRF et affiche la vue d'inscription.
     * @return void
     * @uses Users::findByEmail() Pour vérifier si l'email existe déjà.
     * @uses Users::registerUserBdd() Pour insérer un nouvel utilisateur en base.
     */
    public function register()
    {
        // Vérification que le formulaire est soumis
        // superglobale $_SERVER qui contient la méthode HTTP pour envoyer la requête vers le serveur
        // pour vérifier la méthode de la requête actuelle = POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification CSRF
            if (
                !isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $message = "Requête invalide (CSRF token incorrect).";
                include('views/users/register.php');
                return;
            }
            // Validation des données
            if (empty($_POST['pseudo']) || !ctype_alpha($_POST['pseudo'])) {
                $message = "Le pseudo est obligatoire, il doit être composé d'une chaîne de caractères alphabétiques.";
                include('views/users/register.php');
                return;
            } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $message = "L'email est obligatoire et doit être valide.";
                include('views/users/register.php');
                return;
            } elseif (empty($_POST['password'])) {
                $message = "Le mot de passe est obligatoire.";
                include('views/users/register.php');
                return;
            }
            // Vérification de la complexité du mot de passe
            $passwordPlain = $_POST['password'];
            $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/";

            if (!preg_match($pattern, $passwordPlain)) {
                $message = "Le mot de passe doit contenir au moins 6 caractères dont une majuscule, un chiffre et un caractère spécial.";
                include('views/users/register.php');
                return;
            }
            // Nettoyage des données pour éviter XSS
            $pseudo   = htmlspecialchars($_POST['pseudo'], ENT_QUOTES, 'UTF-8');
            $email    = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            // Vérification si l'email existe déjà
            //appel de la méthode findByEmail() du modèle UserManager
            $existingUser = $this->users->findByEmail($email);
            if ($existingUser) {
                $message = "Cet email est déjà utilisé.";
                include('views/users/register.php');
                return;
            }
            // Préparation des données à insérer dans la BDD
            $data = [
                "pseudo"     => $pseudo,
                "email"      => $email,
                "password"   => $password,
                "created_at" => date("Y-m-d H:i:s")
            ];
            // Appel de la méthode registerUserBdd() du modèle Users pour enregistrer l'utilisateur
            $isRegistered = $this->users->registerUserBdd($data);

            if ($isRegistered) {
                // Redirection vers la page de login
                header("Location: " . ROOT . "/user/login");
                exit;
            } else {
                // Message d'erreur affiché dans la vue
                $message = "Erreur lors de l'inscription.";
                include('views/users/register.php');
            }
        } else {
            // GET : générer le token avant d’afficher la vue
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // Si le formulaire n'est pas soumis, afficher la page d'inscription
            include('views/users/register.php');
        }
    }

    /**
     * Méthode login() pour gèrer la connexion des utilisateurs.
     *
     * Cette méthode :
     * - Vérifie si la requête est envoyée en POST (soumission du formulaire).
     * - Effectue une vérification CSRF via un token stocké en session.
     * - Valide les champs obligatoires :
     *   - Email (non vide et format valide).
     *   - Mot de passe (non vide).
     * - Nettoie les données pour éviter les failles XSS.
     * - Recherche l'utilisateur en base via Users::findByEmail().
     * - Vérifie le mot de passe avec password_verify() par rapport au hash stocké.
     * - Si les identifiants sont corrects :
     *   - Crée la session utilisateur avec ses informations (id, pseudo, email, avatar, date de création).
     *   - Redirige vers la page du compte utilisateur.
     * - Si les identifiants sont incorrects, affiche un message d'erreur et recharge la vue de connexion.
     * - En GET, génère un nouveau token CSRF et affiche la vue de connexion.
     * @return void
     * @uses Users::findByEmail() Pour rechercher l'utilisateur par email.
     */
    public function login()
    {
        // Vérification que le formulaire est soumis 
        // vérifie si la méthode HTTP utilisée pour accéder à la page = POST
        // code éxécuté si formulaire validé
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification CSRF
            if (
                !isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $message = "Requête invalide (CSRF token incorrect).";
                include('views/users/login.php');
                return;
            }
            // Validation des champs
            if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $message = "Veuillez saisir une adresse email valide.";
                include('views/users/login.php');
                return;
            } elseif (empty($_POST['password'])) {
                $message = "Veuillez saisir votre mot de passe.";
                include('views/users/login.php');
                return;
            }
            // Nettoyage des données
            $email    = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $password = $_POST['password'];
            // Recherche de l'utilisateur par email
            $user = $this->users->findByEmail($email);
            // Vérification du mot de passe avec password_verify (comparaison avec le hash stocké)
            if ($user && password_verify($password, $user['password'])) {
                // Création de la session utilisateur avec toutes les infos nécessaires
                $_SESSION['user'] = [
                    'id'         => $user['id'],
                    'pseudo'     => $user['pseudo'],
                    'email'      => $user['email'],
                    'avatar'     => $user['avatar'],
                    'created_at' => $user['created_at']
                ];
                // Redirection vers la page account.php
                header("Location: " . ROOT . "/user/account");
                exit;
            } else {
                // Message d'erreur si email ou mot de passe incorrect
                $message = "Email ou mot de passe incorrect.";
                include('views/users/login.php');
            }
        } else {
            // GET : générer le token avant d’afficher la vue
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // Si le formulaire n'est pas soumis, afficher la page de connexion
            include('views/users/login.php');
        }
    }

    /**
     * Méthode logout() pour déconnecter l'utilisateur en cours.
     *
     * Cette méthode :
     * - Supprime toutes les variables de session.
     * - Détruit la session en cours pour invalider l'authentification.
     * - Redirige l'utilisateur vers la page de connexion.
     * @return void
     */
    public function logout()
    {
        // Suppression des variables de session
        $_SESSION = [];
        // Destruction de la session
        session_destroy();
        // Redirection vers la page de connexion
        header("Location: " . ROOT . "/user/login");
        exit;
    }

    /**
     * Méthode updateInfo() pour mettre à jour les informations de l'utilisateur dans son compte.
     *
     * Cette méthode :
     * - Vérifie que la requête est envoyée en POST (soumission du formulaire).
     * - Récupère les champs du formulaire (email, pseudo, mot de passe).
     * - Valide l'email (format correct).
     * - Nettoie les données pour éviter les failles XSS.
     * - Si un mot de passe est fourni, le hash avant insertion.
     * - Prépare un tableau associatif des données à mettre à jour.
     * - Appelle Users::updateUserInfo() pour mettre à jour l'utilisateur en base.
     * - Met à jour les informations stockées en session (email, pseudo).
     * - Redirige vers la page du compte utilisateur après mise à jour.
     * @return void
     * @uses Users::updateUserInfo() Pour mettre à jour les informations de l'utilisateur en base.
     */
    public function updateInfo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']);
            $pseudo   = trim($_POST['pseudo']);
            $password = trim($_POST['password']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Email invalide.";
                include('views/users/account.php');
                return;
            }
            $data = [
                "email"  => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
                "pseudo" => htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8'),
                "updated_at" => date("Y-m-d H:i:s")
            ];
            if (!empty($password)) {
                $data["password"] = password_hash($password, PASSWORD_DEFAULT);
            }
            $id = $_SESSION['user']['id'];
            $this->users->updateUserInfo($id, $data);
            // Mise à jour session
            $_SESSION['user']['email']  = $data["email"];
            $_SESSION['user']['pseudo'] = $data["pseudo"];
            header("Location: " . ROOT . "/user/account");
            exit;
        }
    }

    /**
     * Méthode updateAvatar() pour mettre à jour l'avatar de l'utilisateur connecté.
     *
     * Cette méthode :
     * - Vérifie que la requête est envoyée en POST (soumission du formulaire).
     * - Vérifie que l'utilisateur est connecté (via la session).
     * - Contrôle la présence d'un fichier uploadé et valide :
     *   - Le type de fichier (JPG, JPEG, PNG, GIF uniquement).
     *   - La taille maximale (10 Mo).
     * - Génère un nom unique pour le fichier uploadé et le déplace dans le dossier public/img/.
     * - Supprime l'ancien avatar si présent et différent de l'avatar par défaut.
     * - Met à jour l'avatar en base de données via Users::updateUserInfo().
     * - Met à jour la session utilisateur avec le nouvel avatar.
     * - Définit un message de succès ou d'erreur en session.
     * - Redirige vers la page du compte utilisateur.
     * @return void
     * @uses Users::updateUserInfo() Pour mettre à jour l'avatar de l'utilisateur en base.
     */
    public function updateAvatar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier que l'utilisateur est connecté
            if (!isset($_SESSION['user']['id'])) {
                header("Location: " . ROOT . "/user/login");
                exit;
            }
            // Vérifier qu'un fichier a été uploadé
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['avatar'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                // Vérification du type de fichier
                if (!in_array($file['type'], $allowedTypes)) {
                    $_SESSION['error'] = "Format de fichier non autorisé. Utilisez JPG, PNG ou GIF.";
                    header("Location: " . ROOT . "/user/account");
                    exit;
                }
                // Vérification de la taille (max 10Mo)
                if ($file['size'] > 10000000) {
                    $_SESSION['error'] = "Le fichier est trop volumineux (max 10Mo).";
                    header("Location: " . ROOT . "/user/account");
                    exit;
                }
                // Générer un nom unique pour le fichier
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = 'avatar_' . $_SESSION['user']['id'] . '_' . time() . '.' . $extension;
                $uploadPath = 'public/img/' . $newFileName;
                // Déplacer le fichier uploadé
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    // Supprimer l'ancien avatar s'il existe et n'est pas l'avatar par défaut
                    if (
                        isset($_SESSION['user']['avatar']) &&
                        $_SESSION['user']['avatar'] !== 'user.png' &&
                        file_exists('public/img/' . $_SESSION['user']['avatar'])
                    ) {
                        unlink('public/img/' . $_SESSION['user']['avatar']);
                    }
                    // Mettre à jour en BDD
                    $data = [
                        "avatar" => $newFileName,
                        "updated_at" => date("Y-m-d H:i:s")
                    ];
                    $this->users->updateUserInfo($_SESSION['user']['id'], $data);

                    // Mettre à jour la session
                    $_SESSION['user']['avatar'] = $newFileName;
                    $_SESSION['success'] = "Avatar mis à jour avec succès !";
                } else {
                    $_SESSION['error'] = "Erreur lors du téléchargement du fichier.";
                }
            }
            header("Location: " . ROOT . "/user/account");
            exit;
        }
    }

    /**
     * Méthode account() pour afficher la page de compte de l'utilisateur connecté.
     *
     * Cette méthode :
     * - Vérifie que l'utilisateur est connecté (via la session).
     *   - Si non connecté, redirige vers la page de connexion.
     * - Instancie BookManager pour :
     *   - Compter le nombre de livres associés à l'utilisateur.
     *   - Récupérer la liste complète des livres de l'utilisateur.
     * - Calcule la durée d'adhésion ("Membre depuis") à partir de la date de création du compte.
     * - Inclut la vue correspondante (views/users/account.php) avec les variables disponibles :
     *   $bookCount, $memberSince, $userBooks et $_SESSION['user'].
     * @return void
     * @uses BookManager::countBooksByUser() Pour compter le nombre de livres de l'utilisateur.
     * @uses BookManager::getBooksByUserId() Pour récupérer tous les livres de l'utilisateur.
     */
    public function account()
    {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user']['id'])) {
            header("Location: " . ROOT . "/user/login");
            exit;
        }
        // Instancier BookManager et récupérer le nombre de livres
        $bookManager = new BookManager();
        $bookCount = $bookManager->countBooksByUser($_SESSION['user']['id']);
        // Calcul "Membre depuis"
        $createdAt = new DateTime($_SESSION['user']['created_at']);
        $now = new DateTime();
        $interval = $createdAt->diff($now);
        $memberSince = $interval->y > 0
            ? "Membre depuis {$interval->y} an" . ($interval->y > 1 ? "s" : "")
            : "Membre depuis moins d'un an";
        // Récupérer tous les livres de l'utilisateur
        $userBooks = $bookManager->getBooksByUserId($_SESSION['user']['id']);
        // Inclure la vue avec les variables disponibles
        include('views/users/account.php');
    }
}
