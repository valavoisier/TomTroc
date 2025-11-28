<?php
/*
MANAGER pour les opérations spécifiques aux messages
*/ 
class MessageManager extends AbstractManager {
    
    /**
     * Méthode sendMessage() pour envoyer un nouveau message entre deux utilisateurs.
     */
    public function sendMessage($senderId, $receiverId, $content): bool {
            $data = [
                'sender_id'   => $senderId,
                'receiver_id' => $receiverId,
                'content'     => $content,
                'is_read'     => 0,
                'created_at'  => date('Y-m-d H:i:s')
            ];
            return $this->add('messages', $data);
        }
        
    /**
     * Méthode getConversationMessages() Rpour récupérer tous les messages d'une conversation entre deux utilisateurs
     *  
     * @param int $userId ID de l'utilisateur connecté
     * @param int $otherUserId ID de l'autre utilisateur
     * @return array Liste des messages triés par date
     * Jointure avec la table users pour obtenir les infos expéditeur et destinataire
     * Récupération des messages bidirectionnels entre deux utilisateurs
     * Tri des messages par ordre chronologique (du plus ancien au plus récent)
     * 
     * ----------------------- Explication requête SQL:--------------------------------
     * La requête sélectionne tous les champs de la table messages (m.*) ainsi que les pseudos et avatars des utilisateurs expéditeur et destinataire en utilisant des jointures internes (INNER JOIN) avec la table users.
     * La clause WHERE filtre les messages pour inclure ceux envoyés par l'utilisateur connecté à l'autre utilisateur et vice versa, assurant ainsi que tous les messages pertinents sont récupérés.
     * Les résultats sont ordonnés par la date de création (created_at) en ordre croissant (du plus ancien au plus récent).
     */
    public function getConversationMessages($userId, $otherUserId):array {
        // Requête SQL pour récupérer les messages entre les deux utilisateurs
        // Inclut les informations de l'expéditeur et du destinataire via des jointures
        $sql = "SELECT 
                    m.*, -- Toutes les colonnes de la table messages (alias m)
                    sender.pseudo as sender_pseudo, -- pseudo de l'expéditeur(sender.pseudo  est la colonne pseudo de la table users de l'expéditeur qui s'appellera sender_pseudo au lieu de pseudo)
                    sender.avatar as sender_avatar, -- avatar de l'expéditeur
                    receiver.pseudo as receiver_pseudo, -- pseudo du destinataire
                    receiver.avatar as receiver_avatar -- avatar du destinataire
                FROM messages m -- Table principale des messages avec alias m
                INNER JOIN users sender ON m.sender_id = sender.id -- Jointure de m avec la table users pour l'expéditeur
                INNER JOIN users receiver ON m.receiver_id = receiver.id -- Jointure de m avec la table users pour le destinataire (ON est la condition : m.receiver_id = receiver.id → relie chaque message à son destinataire)
                /*Filtrage des messages pour récupération de la conversation bilatérale:*/
                WHERE (m.sender_id = :userId AND m.receiver_id = :otherUserId) -- Cas 1 : userId est l’expéditeur ET otherUserId est le destinataire
                   OR (m.sender_id = :otherUserId AND m.receiver_id = :userId) -- Cas 2 : otherUserId est l’expéditeur ET userId est le destinataire
                ORDER BY m.created_at DESC";// Tri par date décroissante du plus récent au plus ancien        
        $dbConnection = $this->db->getConnection();
        $stmt = $dbConnection->prepare($sql);
        // Liaison des paramètres pour la requête préparée évite injection SQL
        // bindValue utilise le type de données approprié pour chaque paramètre
        // PDO::PARAM_INT pour les entiers
        // PDO::PARAM_STR pour les chaînes de caractères
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':otherUserId', $otherUserId, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $messages = [];
        foreach ($rows as $row) {
            $messages[] = new Messages($row);
        }
        return $messages;
    }

    /**
     * Méthode getConversations() pour récupérer toutes les conversations d'un utilisateur avec leur dernier message.
     *
     * Cette méthode :
     * - Sélectionne les utilisateurs avec lesquels l'utilisateur connecté ($userId) a échangé des messages (requête partie 3).
     * - Utilise une sous-requête m2 pour récupérer le contenu du dernier message échangé avec chaque utilisateur.
     * - Utilise une autre sous-requête m3 pour récupérer la date de ce dernier message.
     * - Trie les résultats par date du dernier message (ordre décroissant).
     * - Retourne la liste des conversations sous forme de tableau associatif.
     *
     * Structure du résultat :
     * - user_id          → ID de l'autre utilisateur.
     * - pseudo           → Pseudo de l'autre utilisateur.
     * - avatar           → Avatar de l'autre utilisateur.
     * - last_message     → Contenu du dernier message échangé.
     * - last_message_date→ Date de ce dernier message.
     *
     * @param int $userId Identifiant unique de l'utilisateur connecté.
     * @return array      Tableau associatif contenant la liste des conversations,
     *                    chaque élément représentant une conversation avec son dernier message.
     */
    public function getConversations($userId): array {
        $sql = "SELECT DISTINCT
                    u.id as user_id, -- ID de l'autre utilisateur
                    u.pseudo, -- Pseudo de l'autre utilisateur
                    u.avatar, -- Avatar de l'autre utilisateur
                    (SELECT m2.content -- sous requête m2 récupère le contenu du dernier message échangé
                     FROM messages m2 
                     WHERE (m2.sender_id = :userId AND m2.receiver_id = u.id) -- userId est expéditeur
                        OR (m2.sender_id = u.id AND m2.receiver_id = :userId) -- userId est destinataire
                     ORDER BY m2.created_at DESC -- Trie par date décroissante pour obtenir le plus récent en premier
                     LIMIT 1) as last_message, -- Prend seulement le 1er = le plus récent
                    (SELECT m3.created_at -- sous requête m3 récupère la date du dernier message échangé
                     FROM messages m3 
                     WHERE (m3.sender_id = :userId AND m3.receiver_id = u.id)
                        OR (m3.sender_id = u.id AND m3.receiver_id = :userId)
                     ORDER BY m3.created_at DESC -- Trie par date décroissante pour obtenir le plus récent en premier
                     LIMIT 1) as last_message_date
                FROM users u -- Table de tous les utilisateurs
                WHERE u.id IN ( -- Partie 3 : filtre les utilisateurs ayant échangé des messages avec userId
                    SELECT DISTINCT 
                        CASE 
                            WHEN sender_id = :userId THEN receiver_id -- Si userId est l'expéditeur, prendre le destinataire
                            ELSE sender_id -- Sinon prendre l'expéditeur
                        END as other_user_id -- Récupère les IDs des autres utilisateurs ayant échangé des messages avec userId
                    FROM messages
                    WHERE sender_id = :userId OR receiver_id = :userId -- Filtre les messages impliquant userId
                ) -- Tri par date du dernier message (le plus récent en premier)
                ORDER BY last_message_date DESC"; 
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode getUnreadConversationsCount() pour compter le nombre de conversations non lues pour un utilisateur donné.
     * @param int $userId Identifiant unique de l'utilisateur connecté.
     * @return int Nombre de conversations non lues.
     * Cette méthode :
     * - Construit une requête SQL pour compter les messages non lus (is_read = 0) destinés à l'utilisateur spécifié (receiver_id = :userId).
     * - Prépare et exécute la requête via PDO pour sécuriser l'accès aux données.
     * - Récupère le résultat de la requête, qui contient le nombre de messages non lus.
     * - Retourne ce nombre sous forme d'entier.
     */
     public function getUnreadConversationsCount($userId): int {
        $sql = "SELECT COUNT(*) as count
                FROM messages
                WHERE receiver_id = :userId AND is_read = 0";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

    /**
     * Méthode markMessagesAsRead() pour marquer les messages d'une conversation comme lus.
     *
     * Cette méthode :
     * - Construit une requête SQL pour mettre à jour le statut des messages (is_read = 1) destinés à l'utilisateur spécifié (receiver_id = :userId) et envoyés par l'autre utilisateur (sender_id = :otherUserId).
     * - Prépare et exécute la requête via PDO pour sécuriser l'accès aux données.
     * - Retourne un booléen indiquant si l'opération a réussi (true si au moins une ligne a été affectée, false sinon).
     *
     * @param int $userId Identifiant unique de l'utilisateur connecté.
     * @param int $otherUserId Identifiant unique de l'autre utilisateur dans la conversation.
     * @return bool Indique si l'opération de mise à jour a réussi.
     */
    public function markMessagesAsRead($userId, $otherUserId): bool {
        $sql = "UPDATE messages 
                SET is_read = 1 
                /* Filtrage des messages à mettre à jour : 
                 * Destinés à l'utilisateur connecté (receiver_id = :userId)
                 * Envoyés par l'autre utilisateur (sender_id = :otherUserId)
                */
                WHERE receiver_id = :userId AND sender_id = :otherUserId AND is_read = 0";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':otherUserId', $otherUserId, PDO::PARAM_INT);
        return $stmt->execute();
    }

}