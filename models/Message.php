<?php
/**
 * Modèle représentant un message.
 * Propriétés : id, senderId, receiverId, content, createdAt, isRead, senderPseudo, senderAvatar, receiverPseudo, receiverAvatar.
 * @package Models
 */
class Message {
    private $id;
    private $senderId;
    private $receiverId;
    private $content;
    private $createdAt;
    private $isRead;
    private $senderPseudo;
    private $senderAvatar;
    private $receiverPseudo;
    private $receiverAvatar;

    /**
     * Constructeur du modèle Message.
     * @param array $data Données du message provenant de la base de données
     */
    public function __construct(array $data) {
        // Initialisation des propriétés à partir du tableau de données $data
        //si clé id éxiste dans $data on la convertie en entier et on l'assigne à $this->id sinon on met null
        $this->id             = isset($data['id']) ? (int)$data['id'] : null;
        $this->senderId       = isset($data['sender_id']) ? (int)$data['sender_id'] : null;
        $this->receiverId     = isset($data['receiver_id']) ? (int)$data['receiver_id'] : null;
        $this->content        = $data['content'] ?? '';//si content éxiste prend sa valeur sinon initialise avec un chaîne vide''
        $this->createdAt      = $data['created_at'] ?? null;//stocke date de création si présente sinon null
       
        $this->isRead         = isset($data['is_read']) ? (bool)$data['is_read'] : false; //si clé is_read existe on la convertie en booléen sinon false (non lu)
        $this->senderPseudo   = $data['sender_pseudo'] ?? null;
        $this->senderAvatar   = $data['sender_avatar'] ?? 'user.png';
        $this->receiverPseudo = $data['receiver_pseudo'] ?? null;
        $this->receiverAvatar = $data['receiver_avatar'] ?? 'user.png';
    }

    // Getters
    public function getId() { return $this->id; }
    public function getSenderId() { return $this->senderId; }
    public function getReceiverId() { return $this->receiverId; }
    public function getContent() { return $this->content; }
    public function getCreatedAt() { return $this->createdAt; }
    public function isRead() { return $this->isRead; }
    public function getSenderPseudo() { return $this->senderPseudo; }
    public function getSenderAvatar() { return $this->senderAvatar; }
    public function getReceiverPseudo() { return $this->receiverPseudo; }
    public function getReceiverAvatar() { return $this->receiverAvatar; }
}
