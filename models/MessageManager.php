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
        // Requête SQL pour insérer un nouveau message dans la table messages
        $sql = "INSERT INTO messages (sender_id, receiver_id, content, created_at) 
                VALUES (:senderId, :receiverId, :content, NOW())";
        // Préparation et exécution de la requête
        $dbConnection = $this->db->getConnection();
        // Préparation de la requête SQL
        $stmt = $dbConnection->prepare($sql);
        // Liaison des paramètres pour la requête préparée
        // bindValue utilise le type de données approprié pour chaque paramètre
        // PDO::PARAM_INT pour les entiers
        // PDO::PARAM_STR pour les chaînes de caractères
        $stmt->bindValue(':senderId', $senderId, PDO::PARAM_INT);
        $stmt->bindValue(':receiverId', $receiverId, PDO::PARAM_INT);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        // Exécution de la requête et retour du résultat (true si succès, false sinon)
        return $stmt->execute();
    }
    
    /**
     * Récupère tous les messages d'une conversation entre deux utilisateurs (historique de conversation)
     * @param int $userId ID de l'utilisateur connecté
     * @param int $otherUserId ID de l'autre utilisateur
     * @return array Liste des messages triés par date
     * Jointure avec la table users pour obtenir les infos expéditeur et destinataire
     * Récupération des messages bidirectionnels entre deux utilisateurs
     * Tri des messages par ordre chronologique (du plus ancien au plus récent)
     */
    public function getConversationMessages($userId, $otherUserId) {
        // Requête SQL pour récupérer les messages entre les deux utilisateurs
        // Inclut les informations de l'expéditeur et du destinataire via des jointures
        $sql = "SELECT 
                    m.*,
                    sender.pseudo as sender_pseudo,
                    sender.avatar as sender_avatar,
                    receiver.pseudo as receiver_pseudo,
                    receiver.avatar as receiver_avatar
                FROM messages m
                INNER JOIN users sender ON m.sender_id = sender.id
                INNER JOIN users receiver ON m.receiver_id = receiver.id
                WHERE (m.sender_id = :userId AND m.receiver_id = :otherUserId)
                   OR (m.sender_id = :otherUserId AND m.receiver_id = :userId)
                ORDER BY m.created_at ASC";// Tri par date croissante du plus ancien au plus récent
        
        $dbConnection = $this->db->getConnection();
        $stmt = $dbConnection->prepare($sql);
        // Liaison des paramètres pour la requête préparée
        // bindValue utilise le type de données approprié pour chaque paramètre
        // PDO::PARAM_INT pour les entiers
        // PDO::PARAM_STR pour les chaînes de caractères
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':otherUserId', $otherUserId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}