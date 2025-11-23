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
     * Récupère tous les messages d'une conversation entre deux utilisateurs 
     * @param int $userId ID de l'utilisateur connecté
     * @param int $otherUserId ID de l'autre utilisateur
     * @return array Liste des messages triés par date
     * Jointure avec la table users pour obtenir les infos expéditeur et destinataire
     * Récupération des messages bidirectionnels entre deux utilisateurs
     * Tri des messages par ordre chronologique (du plus ancien au plus récent)
     * ----------------------- Explication requête SQL:--------------------------------
     * La requête sélectionne tous les champs de la table messages (m.*) ainsi que les pseudos et avatars des utilisateurs expéditeur et destinataire en utilisant des jointures internes (INNER JOIN) avec la table users.
     * La clause WHERE filtre les messages pour inclure ceux envoyés par l'utilisateur connecté à l'autre utilisateur et vice versa, assurant ainsi que tous les messages pertinents sont récupérés.
     * Les résultats sont ordonnés par la date de création (created_at) en ordre croissant (du plus ancien au plus récent).
     * ---------En détails:---->
     * sender est l'alias pour la table users représentant l'expéditeur (sender.pseudo  est la colonne pseudo de la table users de l'expéditeur qui s'appellera sender_pseudo au lieu de pseudo)
     * receiver est l'alias pour la table users représentant le destinataire
     * messages m correspond à la table principale, alias m
     * explication avec sender (et idem pour receiver)
     * INNER JOIN users sender correspond à la jointure de m avec la table users pour l’expéditeur,
     * ON est la condition : m.receiver_id = receiver.id → relie chaque message à son destinataire 
     * (m.sender_id → c’est la colonne dans la table messages qui stocke l’identifiant de l’expéditeur et .)
     * La condition dit : relie chaque message à l’utilisateur dont l’identifiant correspond à sender_id.
     * Filtrage des messages pour récupération de la conversation bilatérale:
     * WHERE Cas 1 : :userId est l’expéditeur ET :otherUserId est le destinataire.
     * OR Cas 2 : :otherUserId est l’expéditeur ET :userId est le destinataire.
     * ORDER BY m.created_at ASC trie les messages par date croissante (du plus ancien au plus récent)
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
        // Liaison des paramètres pour la requête préparée évite injection SQL
        // bindValue utilise le type de données approprié pour chaque paramètre
        // PDO::PARAM_INT pour les entiers
        // PDO::PARAM_STR pour les chaînes de caractères
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':otherUserId', $otherUserId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}