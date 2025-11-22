<?php
require_once './Autoload.php';
/*
MANAGER pour les opérations spécifiques aux messages
*/ 
class MessageManager extends PrincipalManager {
    
    /**
     * Envoie un nouveau message
     * @param int $senderId ID de l'expéditeur
     * @param int $receiverId ID du destinataire
     * @param string $content Contenu du message
     * @return bool Succès de l'envoi
     */
    public function sendMessage($senderId, $receiverId, $content) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, content, created_at) 
                VALUES (:senderId, :receiverId, :content, NOW())";
        
        $dbConnection = $this->db->getConnection();
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':senderId', $senderId, PDO::PARAM_INT);
        $stmt->bindValue(':receiverId', $receiverId, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        return $stmt->execute();
    }
}