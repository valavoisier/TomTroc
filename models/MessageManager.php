<?php
require_once './Autoload.php';
/*
MANAGER pour les opérations spécifiques aux messages
*/ 
class MessageManager extends PrincipalManager {
    
    /**
     * Méthode sendMessage() pour envoyer un nouveau message entre deux utilisateurs.
     *
     * Cette méthode :
     * - Construit une requête SQL d'insertion dans la table `messages`.
     * - Insère les informations suivantes :
     *   - L'identifiant de l'expéditeur (`sender_id`).
     *   - L'identifiant du destinataire (`receiver_id`).
     *   - Le contenu du message (`content`).
     *   - La date et l'heure d'envoi (`created_at`) générées automatiquement avec NOW().
     * - Utilise une requête préparée pour sécuriser l'insertion et éviter les injections SQL.
     * - Lie les paramètres avec leur type approprié (entier ou chaîne).
     * - Exécute la requête et retourne un booléen indiquant le succès de l'opération.
     *
     * @param int    $senderId   ID de l'expéditeur du message.
     * @param int    $receiverId ID du destinataire du message.
     * @param string $content    Contenu textuel du message.
     * @return bool  True si l'insertion réussit, False sinon.
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
    public function getConversationMessages($userId, $otherUserId) {
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
                ORDER BY m.created_at ASC";// Tri par date croissante du plus ancien au plus récent        
        $dbConnection = $this->db->getConnection();
        $stmt = $dbConnection->prepare($sql);
        // Liaison des paramètres pour la requête préparée évite injection SQL
        // bindValue utilise le type de données approprié pour chaque paramètre
        // PDO::PARAM_INT pour les entiers
        // PDO::PARAM_STR pour les chaînes de caractères
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':otherUserId', $otherUserId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    public function getConversations($userId) {
        $sql = "SELECT DISTINCT
                    u.id as user_id, -- ID de l'autre utilisateur
                    u.pseudo, -- Pseudo de l'autre utilisateur
                    u.avatar, -- Avatar de l'autre utilisateur
                    (SELECT m2.content -- sous requête m2récupère le contenu du dernier message échangé
                     FROM messages m2 
                     WHERE (m2.sender_id = :userId AND m2.receiver_id = u.id) -- userId est expéditeur
                        OR (m2.sender_id = u.id AND m2.receiver_id = :userId) -- userId est destinataire
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
        
        $dbConnection = $this->db->getConnection();
        $stmt = $dbConnection->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}