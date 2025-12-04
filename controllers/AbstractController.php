<?php
/**
 * Classe AbstractController
 *
 * Classe de base pour tous les contrôleurs de l'application qui en héritent
 * Centralise la logique commune à tous les contrôleurs :
 * - Préparation des données communes pour toutes les vues (ex: compteur de messages non lus)
 * - Gestion des dépendances communes
 */
abstract class AbstractController {
    protected $conversationsCount = 0;//compteur de conversations non lues

    /**
     * Constructeur : prépare les données communes à toutes les vues
     */
    public function __construct() {
        $this->prepareCommonData();
    }

    /**
     * Prépare les données communes nécessaires à toutes les vues
     * 
     * Cette méthode :
     * - Vérifie si un utilisateur est connecté
     * - Si oui, récupère le nombre de messages non lus pour afficher le compteur dans le header
     * - Initialise la propriété $conversationsCount disponible pour toutes les vues
     */
    protected function prepareCommonData(): void {
        if (isset($_SESSION['user'])) {
            $messageManager = new MessageManager();
            $this->conversationsCount = $messageManager->getUnreadConversationsCount($_SESSION['user']['id']);
        }
    }

    /**
     * Inclut une vue en lui passant les variables communes
     * 
     * @param string $viewPath Chemin vers la vue à inclure
     * @param array $data Données spécifiques à passer à la vue (optionnel)
     */
    protected function render(string $viewPath, array $data = []): void {
        // Rendre disponible le compteur pour la vue
        $conversationsCount = $this->conversationsCount;        
        // Extraire les données spécifiques sous forme de variables
        extract($data);        
        // Inclure la vue
        require_once $viewPath;
    }

    /**
     * Vérifie le token CSRF d'un formulaire POST.
     * 
     * Cette méthode :
     * - Vérifie que le token CSRF soumis correspond à celui en session
     * - Utilise Utils::verifyCSRFToken() pour la validation
     * - Si invalide, enregistre une erreur en session et redirige
     * 
     * @param string $redirectUrl URL de redirection en cas d'échec (optionnel, par défaut page précédente)
     * @return void Redirige si le token est invalide
     */
    protected function verifyCSRF(string $redirectUrl = null): void {
        $token = $_POST['csrf_token'] ?? null;
        
        if (!Utils::verifyCSRFToken($token)) {
            $_SESSION['error'] = "Requête invalide. Veuillez réessayer.";
            
            if ($redirectUrl) {
                header('Location: ' . $redirectUrl);
            } else {
                // Redirection vers la page précédente par défaut
                $referer = $_SERVER['HTTP_REFERER'] ?? ROOT;
                header('Location: ' . $referer);
            }
            exit;
        }
    }
}
