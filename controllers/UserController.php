<?php

/**
 * Contrôleur pour la gestion des utilisateurs
 * @extends AbstractController
 * @uses UserManager Pour la gestion des utilisateurs.
 * @property UserManager $userManager Instance de la classe UserManager.
 * Responsabilités principales :
 * - Gérer l'inscription, la connexion et la déconnexion des utilisateurs.
 * - Permettre la mise à jour des informations et de l'avatar utilisateur.
 * - Afficher les comptes publics et privés des utilisateurs.
 * Méthodes :
 * - index() → redirige vers account().
 * - publicAccount($id) → affiche le compte public d'un utilisateur.
 * - register() → gère l'inscription des utilisateurs.
 * - login() → gère la connexion des utilisateurs.
 * - logout() → gère la déconnexion des utilisateurs.
 * - updateInfo() → permet la mise à jour des informations utilisateur.
 * - updateAvatar() → permet la mise à jour de l'avatar utilisateur.
 * - account() → affiche le compte privé de l'utilisateur connecté.
 * @uses Users Pour représenter l'entité utilisateur. *  
 * @uses Utils Pour les fonctionnalités utilitaires (ex: confirmations JavaScript). *  
 */
class UserController extends AbstractController
{
    private $userManager;

    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
    }
    /** 
     * Méthode index() redirigeant vers account().
     * Cette méthode :
     * - Sert de point d'entrée par défaut pour le contrôleur utilisateur.
     * - Redirige automatiquement vers la méthode account() pour afficher le compte utilisateur.
     * @return void
     */
    public function index()
    {
        $this->account();
    }

    /** 
     * Méthode publicAccount($id) pour afficher le compte public d'un utilisateur.
     * 
     * Cette méthode :
     * - Vérifie si un ID utilisateur est fourni ; si non, redirige vers la liste des livres disponibles.
     * - Récupère l'utilisateur correspondant à l'ID via UserManager.
     * - Si l'utilisateur n'existe pas, affiche un message d'erreur.
     * - Récupère le nombre de livres mis en ligne par l'utilisateur via BookManager.
     * - Récupère la liste des livres mis en ligne par l'utilisateur.
     * - Calcule depuis combien de temps l'utilisateur est membre.
     * - Inclut la vue `views/users/publicAccount.php` en passant les données utilisateur et livres.
     * 
     * @param int|null $id ID de l'utilisateur dont on veut afficher le compte public.
     * @return void
     * @uses UserManager::getUserById() Pour récupérer les informations utilisateur.
     * @uses BookManager::countBooksByUser() Pour compter les livres mis en ligne par l'utilisateur.
     * @uses BookManager::getBooksByUserId() Pour récupérer les livres mis en ligne par l'utilisateur.
     */
    public function publicAccount($id = null)
    {
        // Si aucun ID n'est fourni, rediriger vers la liste des livres disponibles
        if ($id === null) {
            header("Location: " . ROOT . "/book/availableBooks");
            exit;
        }
        // Récupérer l'utilisateur par son ID
        $user = $this->userManager->getUserById($id);
        if (!$user) {
            echo "Utilisateur introuvable.";
            return;
        }
        // Récupérer le nombre de livres et la liste des livres mis en ligne par l'utilisateur
        $bookManager = new BookManager();
        $bookCount = $bookManager->countBooksByUser($id); //nombre de livres
        $userBooks = $bookManager->getBooksByUserId($id); //liste des livres
        $createdAt = new DateTime($user->getCreatedAt()); //date de création du compte
        $now = new DateTime(); // date actuelle
        $interval = $createdAt->diff($now); //différence entre les 2 dates
        // Calcul du texte "Membre depuis ..."
        // Si plus d'un an, afficher le nombre d'années, sinon "moins d'un an"
        $memberSince = $interval->y > 0
            ? "Membre depuis {$interval->y} an" . ($interval->y > 1 ? "s" : "")
            : "Membre depuis moins d'un an";
        // Passe un objet $user et la liste $userBooks à la vue
        $this->render('views/users/publicAccount.php', [
            'user' => $user,
            'bookCount' => $bookCount,
            'userBooks' => $userBooks,
            'memberSince' => $memberSince
        ]);
    }

    /** 
     * Méthode register() pour gérer l'inscription des utilisateurs.
     * 
     * Cette méthode :
     * - Gère l'affichage du formulaire d'inscription.
     * - Valide les données soumises (pseudo, email, mot de passe).
     * - Vérifie la présence et la validité du token CSRF.
     * - Hash le mot de passe avant de créer un nouvel utilisateur via UserManager.
     * - Redirige vers la page de connexion après une inscription réussie.
     * - Affiche des messages d'erreur en cas de validation échouée.
     * 
     * @return void
     * @uses UserManager::findByEmail() Pour vérifier l'existence d'un utilisateur par email.
     * @uses UserManager::registerUser() Pour enregistrer un nouvel utilisateur.
     */
    public function register()
    {
        // Gérer la soumission du formulaire d'inscription
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                // Vérification du token CSRF
                !isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                // Régénérer un nouveau token CSRF
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $message = "Requête invalide (CSRF token incorrect).";
                $this->render('views/users/register.php', ['message' => $message]);
                return;
            }
            // Validation des champs du formulaire
            if (empty($_POST['pseudo']) || !ctype_alpha($_POST['pseudo'])) {
                $message = "Le pseudo est obligatoire, il doit être alphabétique.";
                $this->render('views/users/register.php', ['message' => $message]);
                return;
            } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $message = "L'email est obligatoire et doit être valide.";
                $this->render('views/users/register.php', ['message' => $message]);
                return;
            } elseif (empty($_POST['password'])) {
                $message = "Le mot de passe est obligatoire.";
                $this->render('views/users/register.php', ['message' => $message]);
                return;
            }
            // Validation de la complexité du mot de passe
            $passwordPlain = $_POST['password']; //mot de passe en clair
            $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/"; //au moins 6 caractères, 1 majuscule, 1 chiffre, 1 caractère spécial
            // Vérification du mot de passe avec l'expression régulière
            // !preg_match() retourne true si le mot de passe ne correspond pas au pattern
            if (!preg_match($pattern, $passwordPlain)) {
                $message = "Le mot de passe doit contenir au moins 6 caractères dont une majuscule, un chiffre et un caractère spécial.";
                $this->render('views/users/register.php', ['message' => $message]);
                return;
            }
            // Sanitize et préparation des données utilisateur
            $pseudo   = htmlspecialchars($_POST['pseudo'], ENT_QUOTES, 'UTF-8');
            $email    = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            // Vérifier si l'email est déjà utilisé
            $existingUser = $this->userManager->findByEmail($email);
            // Si l'utilisateur existe déjà, afficher un message d'erreur
            if ($existingUser) {
                $message = "Cet email est déjà utilisé.";
                $this->render('views/users/register.php', ['message' => $message]);
                return;
            }
            // Créer un nouvel utilisateur
            $now = date("Y-m-d H:i:s"); //date actuelle pour created_at et updated_at
            $user = new User(
                0,
                $pseudo,
                $email,
                $password,
                'user.png',
                $now,
                $now
            );
            // Enregistrer l'utilisateur via UserManager
            $isRegistered = $this->userManager->registerUser($user);
            // Si l'inscription réussit, rediriger vers la page de connexion
            if ($isRegistered) {
                header("Location: " . ROOT . "/user/login");
                exit;
            } else {
                $message = "Erreur lors de l'inscription.";
                $this->render('views/users/register.php', ['message' => $message]);
            }
        } else {
            // Générer un token CSRF pour le formulaire
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->render('views/users/register.php');
        }
    }

    /** 
     * Méthode login() pour gérer la connexion des utilisateurs.
     * 
     * Cette méthode :
     * - Gère l'affichage du formulaire de connexion.
     * - Valide les données soumises (email, mot de passe).
     * - Vérifie la présence et la validité du token CSRF.
     * - Vérifie les informations d'identification via UserManager.
     * - Initialise la session utilisateur après une connexion réussie.
     * - Redirige vers la page du compte utilisateur après connexion.
     * - Affiche des messages d'erreur en cas de validation échouée.
     * 
     * @return void
     * @uses UserManager::findByEmail() Pour récupérer les informations utilisateur par email.
     */
    public function login()
    {
        // Gérer la soumission du formulaire de connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification du token CSRF
            if (
                !isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                // Régénérer un nouveau token CSRF
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $message = "Requête invalide (CSRF token incorrect).";
                $this->render('views/users/login.php', ['message' => $message]);
                return;
            }
            // Validation des champs du formulaire
            if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $message = "Veuillez saisir une adresse email valide.";
                $this->render('views/users/login.php', ['message' => $message]);
                return;
                // Si le mot de passe est vide
            } elseif (empty($_POST['password'])) {
                $message = "Veuillez saisir votre mot de passe.";
                $this->render('views/users/login.php', ['message' => $message]);
                return;
            }
            // Sanitize des données
            $email    = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $password = $_POST['password']; //mot de passe en clair
            // Récupérer l'utilisateur par email
            $user = $this->userManager->findByEmail($email);
            // Vérifier le mot de passe
            if ($user && password_verify($password, $user->getPassword())) {
                // Initialiser la session utilisateur
                $_SESSION['user'] = [
                    'id'         => $user->getId(),
                    'pseudo'     => $user->getPseudo(),
                    'email'      => $user->getEmail(),
                    'avatar'     => $user->getAvatar(),
                    'created_at' => $user->getCreatedAt()
                ];
                // Rediriger vers la page du compte utilisateur
                header("Location: " . ROOT . "/user/account");
                exit;
            } else {
                // Informations d'identification incorrectes
                $message = "Email ou mot de passe incorrect.";
                $this->render('views/users/login.php', ['message' => $message]);
            }
        } else {
            // Générer un token CSRF pour le formulaire
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->render('views/users/login.php');
        }
    }

    /** 
     * Méthode logout() pour gérer la déconnexion des utilisateurs.
     * 
     * Cette méthode :
     * - Détruit la session utilisateur.
     * - Redirige vers la page de connexion après déconnexion.
     * 
     * @return void
     */
    public function logout()
    {
        // Détruire la session utilisateur
        $_SESSION = [];
        session_destroy();
        header("Location: " . ROOT . "/user/login");
        exit;
    }

    /** 
     * Méthode updateInfo() pour permettre la mise à jour des informations utilisateur.
     * 
     * Cette méthode :
     * - Vérifie si l'utilisateur est connecté ; sinon redirige vers la page de connexion.
     * - Gère la soumission du formulaire de mise à jour des informations.
     * - Valide les données soumises (email, pseudo, mot de passe optionnel).
     * - Met à jour les informations utilisateur via UserManager.
     * - Met à jour les données de session après une mise à jour réussie.
     * - Redirige vers la page du compte utilisateur après mise à jour.
     * 
     * @return void
     * @uses UserManager::getUserById() Pour récupérer les informations utilisateur.
     * @uses UserManager::updateUserInfo() Pour mettre à jour les informations utilisateur.
     */
    public function updateInfo()
    {   // Gérer la soumission du formulaire de mise à jour des informations
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['user']['id'])) {
                header("Location: " . ROOT . "/user/login");
                exit;
            }
            // Validation des champs du formulaire
            $email    = trim($_POST['email']);
            $pseudo   = trim($_POST['pseudo']);
            $password = trim($_POST['password']);
            //vérifier email valide
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Email invalide.";
                $this->render('views/users/account.php', ['message' => $message]);
                return;
            }

            // Charger l'utilisateur courant
            $user = $this->userManager->getUserById($_SESSION['user']['id']);
            if (!$user) {
                $message = "Utilisateur introuvable.";
                $this->render('views/users/account.php', ['message' => $message]);
                return;
            }
            // Mettre à jour les champs
            $user->setEmail(htmlspecialchars($email, ENT_QUOTES, 'UTF-8'));
            $user->setPseudo(htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8'));
            $user->setUpdatedAt(date("Y-m-d H:i:s"));
            // Si un nouveau mot de passe est fourni, le hasher et le mettre à jour
            if (!empty($password)) {
                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            }
            // Mettre à jour les informations utilisateur via UserManager
            $this->userManager->updateUserInfo($user);
            // Mise à jour session
            $_SESSION['user']['email']  = $user->getEmail(); //mettre à jour email
            $_SESSION['user']['pseudo'] = $user->getPseudo(); //mettre à jour pseudo
            // Rediriger vers la page du compte utilisateur
            header("Location: " . ROOT . "/user/account");
            exit;
        }
    }

    /** 
     * Méthode updateAvatar() pour permettre la mise à jour de l'avatar utilisateur.
     * 
     * Cette méthode :
     * - Vérifie si l'utilisateur est connecté ; sinon redirige vers la page de connexion.
     * - Gère la soumission du formulaire de mise à jour de l'avatar.
     * - Valide le fichier uploadé (type, taille).
     * - Déplace le fichier uploadé dans le répertoire des avatars.
     * - Met à jour l'avatar utilisateur via UserManager.
     * - Met à jour les données de session après une mise à jour réussie.
     * - Redirige vers la page du compte utilisateur après mise à jour.
     * 
     * @return void
     * @uses UserManager::getUserById() Pour récupérer les informations utilisateur.
     * @uses UserManager::updateUserInfo() Pour mettre à jour les informations utilisateur.
     */
    public function updateAvatar()
    {
        // Gérer la soumission du formulaire de mise à jour de l'avatar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['user']['id'])) {
                // Rediriger vers la page de connexion si non connecté
                header("Location: " . ROOT . "/user/login");
                exit;
            }
            // Vérifier si un fichier a été uploadé sans erreur
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                // Valider le fichier uploadé
                $file = $_FILES['avatar']; //données du fichier uploadé
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']; //types MIME autorisés
                // Vérifier le type de fichier
                if (!in_array($file['type'], $allowedTypes)) {
                    // Type de fichier non autorisé
                    $_SESSION['error'] = "Format de fichier non autorisé. Utilisez JPG, PNG ou GIF.";
                    header("Location: " . ROOT . "/user/account");
                    exit;
                }
                // Vérifier la taille du fichier (max 10Mo)
                if ($file['size'] > 10000000) {
                    // Fichier trop volumineux
                    $_SESSION['error'] = "Le fichier est trop volumineux (max 10Mo).";
                    header("Location: " . ROOT . "/user/account");
                    exit;
                }
                // Déplacer le fichier uploadé vers le répertoire des avatars
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION); //extension du fichier uploadé
                $newFileName = 'avatar_' . $_SESSION['user']['id'] . '_' . time() . '.' . $extension; //nouveau nom de fichier unique
                $uploadPath = 'public/img/' . $newFileName; //chemin de destination
                // Déplacer le fichier uploadé
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    // supprimer ancien avatar si pertinent
                    if (
                        // Vérifier si un ancien avatar existe et n'est pas l'avatar par défaut
                        isset($_SESSION['user']['avatar']) &&
                        $_SESSION['user']['avatar'] !== 'user.png' &&
                        file_exists('public/img/' . $_SESSION['user']['avatar'])
                    ) {
                        // Supprimer l'ancien avatar
                        @unlink('public/img/' . $_SESSION['user']['avatar']);
                    }
                    // Charger l'utilisateur et mettre à jour
                    $user = $this->userManager->getUserById($_SESSION['user']['id']);
                    // Mettre à jour l'avatar dans la base de données
                    if ($user) {
                        $user->setAvatar($newFileName); //mettre à jour avatar
                        $user->setUpdatedAt(date("Y-m-d H:i:s")); //mettre à jour updated_at
                        $this->userManager->updateUserInfo($user); //met à jour en base
                        // Mettre à jour l'avatar dans la session
                        $_SESSION['user']['avatar'] = $newFileName; //met à jour session
                        $_SESSION['success'] = "Avatar mis à jour avec succès !"; //message de succès
                    } else {   // utilisateur non trouvé
                        $_SESSION['error'] = "Utilisateur introuvable.";
                    }
                } else {
                    // Erreur lors du déplacement du fichier uploadé
                    $_SESSION['error'] = "Erreur lors du téléchargement du fichier.";
                }
            }
            // Rediriger vers la page du compte utilisateur
            header("Location: " . ROOT . "/user/account");
            exit;
        }
    }
    /**  Méthode account() pour afficher le compte privé de l'utilisateur connecté.
     * Cette méthode :
     * - Vérifie si l'utilisateur est connecté ; sinon redirige vers la page de connexion.
     * - Récupère les informations de l'utilisateur connecté via <UserManager class="UserManager">
     * - Récupère le nombre de livres mis en ligne par l'utilisateur via BookManager.
     * - Récupère la liste des livres mis en ligne par l'utilisateur.
     * - Calcule depuis combien de temps l'utilisateur est membre.
     * - Inclut la vue `views/users/account.php` en passant les données utilisateur et livres.
     * @return void
     * @uses UserManager::getUserById() Pour récupérer les informations utilisateur.
     * @uses BookManager::countBooksByUser() Pour compter les livres mis en ligne par l'utilisateur.
     * @uses BookManager::getBooksByUserId() Pour récupérer les livres mis en ligne par l'utilisateur.
     */
    public function account()
    {// Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user']['id'])) {
            // Rediriger vers la page de connexion si non connecté
            header("Location: " . ROOT . "/user/login");
            exit;
        }
        // Récupérer les informations de l'utilisateur connecté
        $user = $this->userManager->getUserById($_SESSION['user']['id']);
        $bookManager = new BookManager();
        // Récupérer le nombre de livres et la liste des livres mis en ligne par l'utilisateur
        $bookCount = $bookManager->countBooksByUser($user->getId());
        //liste des livres
        $userBooks = $bookManager->getBooksByUserId($user->getId());
        // Calculer depuis combien de temps l'utilisateur est membre
        $createdAt = new DateTime($user->getCreatedAt());
        $now = new DateTime();
        $interval = $createdAt->diff($now);//différence entre les 2 dates
        // Calcul du texte "Membre depuis ..."
        // Si plus d'un an, afficher le nombre d'années, sinon "moins d'un an"
        $memberSince = $interval->y > 0
            ? "Membre depuis {$interval->y} an" . ($interval->y > 1 ? "s" : "")
            : "Membre depuis moins d'un an";
        // Passe un objet $user et la liste $userBooks à la vue
        $this->render('views/users/account.php', [
            'user' => $user,//objet utilisateur
            'bookCount' => $bookCount,//nombre de livres
            'userBooks' => $userBooks,//liste des livres
            'memberSince' => $memberSince//texte "Membre depuis ..."        
        ]);
    }
}
