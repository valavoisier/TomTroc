<?php
class Messages {
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

    public function __construct(array $data) {
        $this->id             = isset($data['id']) ? (int)$data['id'] : null;
        $this->senderId       = isset($data['sender_id']) ? (int)$data['sender_id'] : null;
        $this->receiverId     = isset($data['receiver_id']) ? (int)$data['receiver_id'] : null;
        $this->content        = $data['content'] ?? '';
        $this->createdAt      = $data['created_at'] ?? null;
        $this->isRead         = isset($data['is_read']) ? (bool)$data['is_read'] : false;
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
