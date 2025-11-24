<?php
require_once './Autoload.php';
class MessageController {
    private $messageManager;//instance de la classe Messages
    //constructeur qui initialise l'instance de MessageManager
    public function __construct() {
        $this->messageManager = new Messages();
    }
    /**
     * Affiche la page des messages
     */
    public function index()
    {
             include('views/messages/messages.php');
    }

    /**
     * Méthode send() pour envoyer un nouveau message.
     *
     * Cette méthode :
     * - Vérifie que l'utilisateur est connecté (présence de $_SESSION['user']).
     *   - Si non connecté, redirige vers la page de connexion.
     * - Vérifie que la requête HTTP est bien de type POST.
     * - Récupère l'identifiant de l'expéditeur depuis la session.
     * - Récupère l'identifiant du destinataire et le contenu du message depuis $_POST.
     * - Vérifie que l'identifiant du destinataire est valide et que le contenu n'est pas vide.
     * - Appelle la méthode sendMessage() du MessageManager pour insérer le message en base.
     * - Redirige ensuite vers la conversation avec le destinataire.
     *
     * @return void Cette méthode ne retourne rien ; elle effectue des actions (insertion + redirection).
     */
    public function send() {
        // Vérifier si l'utilisateur est connecté sinon redirection formulaire connexion 
        if (!isset($_SESSION['user'])) {
            header('Location: ' . ROOT . '/user/login');
            exit;
        }
        // Vérifier si la requête est de type POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $senderId = $_SESSION['user']['id'];//ID de l'expéditeur
            $receiverId = $_POST['receiver_id'] ?? null;//ID du destinataire
            $content = $_POST['content'] ?? '';//Contenu du message
            // Valider les données avant l'envoi
            if ($receiverId && !empty(trim($content))) {
                // Appeler la méthode sendMessage() du MessageManager pour insérer le message en base
                $this->messageManager->sendMessage($senderId, $receiverId, trim($content));
            }            
            // Rediriger vers la conversation avec le destinataire
            header('Location: ' . ROOT . '/message/conversation/' . $receiverId);
            exit;
        }
    }
}