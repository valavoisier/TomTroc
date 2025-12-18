<?php
declare(strict_types=1);
/**
 * Classe MessageController
 *
 * Contrôleur responsable de la gestion de la messagerie :
 * - Initialise les managers nécessaires (MessageManager et UserManager).
 * - Orchestration entre les entités (Users, Messages), les managers et les vues.
 * - Permet l'affichage des conversations, l'envoi de nouveaux messages
 *   et la consultation d'une conversation spécifique.
 *  méthodes:
 * - index() : void - Affiche la messagerie avec la liste des conversations.
 * - send() : void - Traite l'envoi d'un nouveau message.
 * - conversation(int $otherUserId) : void - Affiche une conversation spécifique.
 * @extends AbstractController
 * @uses MessageManager Pour la gestion des messages et conversations.
 * @uses UserManager Pour la gestion des utilisateurs.
 * @uses Users Entité représentant un utilisateur.
 * @uses Messages Entité représentant un message.
 */
class MessageController extends AbstractController {
    private $messageManager;//instance MessageManager
    private $userManager;//instance de la classe UserManager

    //constructeur  
    public function __construct() {
        parent::__construct();
        $this->messageManager = new MessageManager();
        $this->userManager = new UserManager();
    }

    /**
     * Méthode index() pour afficher la messagerie avec la liste des conversations.
     *
     * Cette méthode :
     * - Vérifie que l'utilisateur est connecté (présence de $_SESSION['user']).
     *   - Si non connecté, redirige vers la page de connexion.
     * - Récupère l'identifiant de l'utilisateur connecté depuis la session.
     * - Récupère toutes les conversations de l'utilisateur via MessageManager.
     * - Initialise les variables $selectedConversation et $messages.
     * - Si des conversations existent :
     *   - Sélectionne par défaut la première conversation.
     *   - Récupère l'entité Users correspondant à l'autre participant.
     *   - Marque les messages de cette conversation comme lus.
     *   - Récupère les messages échangés sous forme d'entités Messages.
     * - Inclut la vue `views/messages/messages.php` pour afficher la liste des conversations et le contenu de la conversation sélectionnée.
     *
     * @return void Prépare les données et inclut une vue.
     */
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: ' . ROOT . '/user/login');
            exit;
        }
        // Récupérer l'ID de l'utilisateur connecté 
        $userId = $_SESSION['user']['id'];
        
        // Récupérer toutes les conversations
        $conversations = $this->messageManager->getConversations($userId);
        
        // Auto-sélectionner la première conversation et marquer les messages comme lus
        $selectedConversation = null;
        // Initialiser le tableau des messages
        $messages = [];
        
        // Si des conversations existent, sélectionner la première par défaut     
        if (!empty($conversations)) {
            // Sélectionner la première conversation
            $firstConversation = $conversations[0];
            // Récupérer les informations de l'utilisateur sélectionné
            $selectedConversation = $this->userManager->getUserById($firstConversation->getUserId());
            // Marquer les messages comme lus
            $this->messageManager->markMessagesAsRead($userId, $firstConversation->getUserId());
            // Récupérer les messages de la conversation
            $messages = $this->messageManager->getConversationMessages($userId, $firstConversation->getUserId());
            
            // Recalculer le compteur après avoir marqué les messages comme lus
            $this->conversationsCount = $this->messageManager->getUnreadConversationsCount($userId);
        }
        
        // Inclure la vue avec les données préparées
        $this->render('views/messages/messages.php', [
            'conversations' => $conversations,//liste des conversations
            'selectedConversation' => $selectedConversation,//utilisateur sélectionné
            'messages' => $messages//messages de la conversation sélectionnée
        ]);
    }

    /**
     * Méthode send() pour traiter l'envoi d'un nouveau message.
     *
     * Cette méthode :
     * - Vérifie que l'utilisateur est connecté (sinon redirection vers la page de connexion).
     * - Vérifie que la requête HTTP est bien de type POST.
     * - Récupère l'identifiant de l'expéditeur depuis la session.
     * - Récupère l'identifiant du destinataire et le contenu du message depuis $_POST.
     * - Valide les données :
     *   - Si le destinataire est manquant, enregistre une erreur en session et redirige vers la messagerie.
     *   - Si le contenu est vide, enregistre une erreur en session et redirige vers la conversation.
     * - Si les données sont valides :
     *   - Appelle la méthode sendMessage() du MessageManager pour insérer le message en base.
     *   - Redirige vers la conversation avec le destinataire.
     * - Si la requête n'est pas POST, redirige vers la messagerie.
     *
     * @return void Cette méthode ne retourne rien ; elle effectue des actions (validation, insertion, redirection).
     */
    public function send()
    {
        // Vérifier si l'utilisateur est connecté sinon redirection formulaire connexion 
        if (!isset($_SESSION['user'])) {
            header('Location: ' . ROOT . '/user/login');
            exit;
        }
        
        // Vérifier si la requête est de type POST
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérification du token CSRF
            $this->verifyCSRF(ROOT . '/message');
            
            // Récupérer les données du formulaire 
            $senderId   = $_SESSION['user']['id'];//ID de l'expéditeur
            $receiverId = isset($_POST['receiver_id']) ? (int) $_POST['receiver_id'] : null;//ID du destinataire
            $content    = isset($_POST['content']) ? trim($_POST['content']) : '';//Contenu du message
            
            // Validation des données
            if (!$receiverId) {
                $_SESSION['error'] = "Destinataire manquant.";
                header('Location: ' . ROOT . '/message');
                exit;
            }
            
            // Vérifier que le contenu n'est pas vide
            if ($content === '') {
                $_SESSION['error'] = "Le message ne peut pas être vide.";
                header('Location: ' . ROOT . '/message/conversation/' . $receiverId);
                exit;
            }
            
            // Envoyer le message via le MessageManager
            $this->messageManager->sendMessage($senderId, $receiverId, $content);
            header('Location: ' . ROOT . '/message/conversation/' . $receiverId);
            exit;
        }
        
        header('Location: ' . ROOT . '/message');
        exit;
    }

    /**
     * Méthode conversation() pour afficher une conversation spécifique entre l'utilisateur connecté et un autre utilisateur.
     *
     * Cette méthode :
     * - Vérifie que l'utilisateur est connecté (sinon redirection vers la page de connexion).
     * - Récupère l'identifiant de l'utilisateur connecté depuis la session.
     * - Récupère la liste de toutes les conversations de l'utilisateur (utile pour l'affichage global).
     * - Récupère l'entité Users correspondant à l'autre utilisateur ($otherUserId).
     * - Marque les messages de cette conversation comme lus via MessageManager.
     * - Récupère tous les messages échangés sous forme d'entités Messages.
     * - Inclut la vue `views/messages/messages.php` pour afficher la liste des conversations et le contenu de la conversation sélectionnée.
     *
     * @param int $otherUserId Identifiant unique de l'autre utilisateur avec qui on converse.
     * @return void Prépare les données et inclut une vue.
     */
    public function conversation($otherUserId)
    {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header('Location: ' . ROOT . '/user/login');
            exit;
        }
        
        // Récupérer l'ID de l'utilisateur connecté
        $userId = $_SESSION['user']['id'];
        
        // Récupérer toutes les conversations
        $conversations = $this->messageManager->getConversations($userId);
        
        // Récupérer les informations de l'utilisateur sélectionné
        $selectedConversation = $this->userManager->getUserById($otherUserId);
        $this->messageManager->markMessagesAsRead($userId, $otherUserId);
        // Récupérer les messages de la conversation
        $messages = $this->messageManager->getConversationMessages($userId, $otherUserId);
        
        // Recalculer le compteur après avoir marqué les messages comme lus
        $this->conversationsCount = $this->messageManager->getUnreadConversationsCount($userId);
        
        $this->render('views/messages/messages.php', [
            'conversations' => $conversations,//liste des conversations
            'selectedConversation' => $selectedConversation,//utilisateur sélectionné
            'messages' => $messages//messages de la conversation
        ]);
    }
}