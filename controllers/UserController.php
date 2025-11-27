<?php
require_once './Autoload.php';

class UserController
{
    private $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    public function index()
    {
        $this->account();
    }

    public function publicAccount($id = null)
    {
        if ($id === null) {
            header("Location: " . ROOT . "/book/availableBooks");
            exit;
        }

        $user = $this->userManager->getUserById($id);
        if (!$user) {
            echo "Utilisateur introuvable.";
            return;
        }

        $bookManager = new BookManager();
        $bookCount = $bookManager->countBooksByUser($id);
        $userBooks = $bookManager->getBooksByUserId($id); 
        $createdAt = new DateTime($user->getCreatedAt());
        $now = new DateTime();
        $interval = $createdAt->diff($now);
        $memberSince = $interval->y > 0
            ? "Membre depuis {$interval->y} an" . ($interval->y > 1 ? "s" : "")
            : "Membre depuis moins d'un an";

        // Passe un objet $user et la liste $userBooks à la vue
        include('views/users/publicAccount.php');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                !isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $message = "Requête invalide (CSRF token incorrect).";
                include('views/users/register.php');
                return;
            }

            if (empty($_POST['pseudo']) || !ctype_alpha($_POST['pseudo'])) {
                $message = "Le pseudo est obligatoire, il doit être alphabétique.";
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

            $passwordPlain = $_POST['password'];
            $pattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/";
            if (!preg_match($pattern, $passwordPlain)) {
                $message = "Le mot de passe doit contenir au moins 6 caractères dont une majuscule, un chiffre et un caractère spécial.";
                include('views/users/register.php');
                return;
            }

            $pseudo   = htmlspecialchars($_POST['pseudo'], ENT_QUOTES, 'UTF-8');
            $email    = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $existingUser = $this->userManager->findByEmail($email);
            if ($existingUser) {
                $message = "Cet email est déjà utilisé.";
                include('views/users/register.php');
                return;
            }

            $now = date("Y-m-d H:i:s");
            $user = new Users(
                0,
                $pseudo,
                $email,
                $password,
                'user.png',
                $now,
                $now
            );

            $isRegistered = $this->userManager->registerUser($user);
            if ($isRegistered) {
                header("Location: " . ROOT . "/user/login");
                exit;
            } else {
                $message = "Erreur lors de l'inscription.";
                include('views/users/register.php');
            }
        } else {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            include('views/users/register.php');
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                !isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) ||
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
            ) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $message = "Requête invalide (CSRF token incorrect).";
                include('views/users/login.php');
                return;
            }

            if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $message = "Veuillez saisir une adresse email valide.";
                include('views/users/login.php');
                return;
            } elseif (empty($_POST['password'])) {
                $message = "Veuillez saisir votre mot de passe.";
                include('views/users/login.php');
                return;
            }

            $email    = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
            $password = $_POST['password'];

            $user = $this->userManager->findByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['user'] = [
                    'id'         => $user->getId(),
                    'pseudo'     => $user->getPseudo(),
                    'email'      => $user->getEmail(),
                    'avatar'     => $user->getAvatar(),
                    'created_at' => $user->getCreatedAt()
                ];
                header("Location: " . ROOT . "/user/account");
                exit;
            } else {
                $message = "Email ou mot de passe incorrect.";
                include('views/users/login.php');
            }
        } else {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            include('views/users/login.php');
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header("Location: " . ROOT . "/user/login");
        exit;
    }

    public function updateInfo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user']['id'])) {
                header("Location: " . ROOT . "/user/login");
                exit;
            }

            $email    = trim($_POST['email']);
            $pseudo   = trim($_POST['pseudo']);
            $password = trim($_POST['password']);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Email invalide.";
                include('views/users/account.php');
                return;
            }

            // Charger l'utilisateur courant
            $user = $this->userManager->getUserById($_SESSION['user']['id']);
            if (!$user) {
                $message = "Utilisateur introuvable.";
                include('views/users/account.php');
                return;
            }

            $user->setEmail(htmlspecialchars($email, ENT_QUOTES, 'UTF-8'));
            $user->setPseudo(htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8'));
            $user->setUpdatedAt(date("Y-m-d H:i:s"));

            if (!empty($password)) {
                $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            }

            $this->userManager->updateUserInfo($user);

            // Mise à jour session
            $_SESSION['user']['email']  = $user->getEmail();
            $_SESSION['user']['pseudo'] = $user->getPseudo();

            header("Location: " . ROOT . "/user/account");
            exit;
        }
    }

    public function updateAvatar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user']['id'])) {
                header("Location: " . ROOT . "/user/login");
                exit;
            }

            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['avatar'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

                if (!in_array($file['type'], $allowedTypes)) {
                    $_SESSION['error'] = "Format de fichier non autorisé. Utilisez JPG, PNG ou GIF.";
                    header("Location: " . ROOT . "/user/account");
                    exit;
                }

                if ($file['size'] > 10000000) {
                    $_SESSION['error'] = "Le fichier est trop volumineux (max 10Mo).";
                    header("Location: " . ROOT . "/user/account");
                    exit;
                }

                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = 'avatar_' . $_SESSION['user']['id'] . '_' . time() . '.' . $extension;
                $uploadPath = 'public/img/' . $newFileName;

                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    // supprimer ancien avatar si pertinent
                    if (
                        isset($_SESSION['user']['avatar']) &&
                        $_SESSION['user']['avatar'] !== 'user.png' &&
                        file_exists('public/img/' . $_SESSION['user']['avatar'])
                    ) {
                        @unlink('public/img/' . $_SESSION['user']['avatar']);
                    }

                    // Charger l'utilisateur et mettre à jour
                    $user = $this->userManager->getUserById($_SESSION['user']['id']);
                    if ($user) {
                        $user->setAvatar($newFileName);
                        $user->setUpdatedAt(date("Y-m-d H:i:s"));
                        $this->userManager->updateUserInfo($user);

                        $_SESSION['user']['avatar'] = $newFileName;
                        $_SESSION['success'] = "Avatar mis à jour avec succès !";
                    } else {
                        $_SESSION['error'] = "Utilisateur introuvable.";
                    }
                } else {
                    $_SESSION['error'] = "Erreur lors du téléchargement du fichier.";
                }
            }

            header("Location: " . ROOT . "/user/account");
            exit;
        }
    }

       public function account() {
        if (!isset($_SESSION['user']['id'])) {
            header("Location: " . ROOT . "/user/login");
            exit;
        }

        $user = $this->userManager->getUserById($_SESSION['user']['id']);
        $bookManager = new BookManager();
        $bookCount = $bookManager->countBooksByUser($user->getId());
        $userBooks = $bookManager->getBooksByUserId($user->getId());

        $createdAt = new DateTime($user->getCreatedAt());
        $now = new DateTime();
        $interval = $createdAt->diff($now);
        $memberSince = $interval->y > 0
            ? "Membre depuis {$interval->y} an" . ($interval->y > 1 ? "s" : "")
            : "Membre depuis moins d'un an";

        include('views/users/account.php');
    }
}
