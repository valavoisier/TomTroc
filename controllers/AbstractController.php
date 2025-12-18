<?php
declare(strict_types=1);
/**
 * Classe AbstractController
 *
 * Classe de base pour tous les contrôleurs de l'application qui en héritent
 * Centralise la logique commune à tous les contrôleurs :
 * - Préparation des données communes pour toutes les vues (ex: compteur de messages non lus)
 * - Gestion des dépendances communes
 * - Méthodes utilitaires partagées (ex: rendu de vues, vérification CSRF)
 * Méthodes:
 * - __construct() : Initialise les données communes
 * - prepareCommonData() : Prépare les données communes pour les vues
 * - render($viewPath, $data) : Rend une vue avec les données communes
 * - verifyCSRF($redirectUrl) : Vérifie le token CSRF pour les formulaires POST
 * @package Controllers
 * @uses MessageManager Pour récupérer le nombre de conversations non lues
 * @uses Utils Pour la vérification des tokens CSRF *  
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
        // Vérifier si un utilisateur est connecté
        if (isset($_SESSION['user'])) {
            // Instancier MessageManager pour accéder aux données des messages
            $messageManager = new MessageManager();
            // Récupérer le nombre de conversations non lues pour l'utilisateur connecté
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
    protected function verifyCSRF(string $redirectUrl): void {
        // Récupérer le token soumis via le formulaire si absent $token vaut null
        $token = $_POST['csrf_token'] ?? null;
        
        //veifyCSRFToken ompare le token soumis avec celui stocké en session et retourne true si le token est valide, false sinon
        if (!Utils::verifyCSRFToken($token)) {
            // gestion de l'erreur CSRF
            $_SESSION['error'] = "Requête invalide. Veuillez réessayer.";
            
            // Redirection vers l'URL spécifiée $redirectUrl 
            if ($redirectUrl) {
                header('Location: ' . $redirectUrl);
            } else {
                // Redirection vers la page précédente par défaut
                $referer = $_SERVER['HTTP_REFERER'] ?? ROOT;
                header('Location: ' . $referer);
            }
            exit;// arrêt exécution du script après la redirection
        }
    }
}
