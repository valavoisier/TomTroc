<?php

/**
 * Classe Conversation
 * Représente une conversation dans la messagerie avec les informations de l'interlocuteur
 * et le dernier message échangé.
 */
class Conversation
{
    private int $userId;
    private string $pseudo;
    private ?string $avatar;
    private ?string $lastMessage;
    private ?string $lastMessageDate;

    /**
     * Constructeur de la classe Conversation
     * @param array $data Tableau associatif contenant les données de la conversation
     */
    public function __construct(array $data)
    {
        $this->userId = (int)$data['user_id'];
        $this->pseudo = $data['pseudo'];
        $this->avatar = $data['avatar'] ?? null;
        $this->lastMessage = $data['last_message'] ?? null;
        $this->lastMessageDate = $data['last_message_date'] ?? null;
    }

    /**
     * Retourne l'ID de l'utilisateur avec qui on converse
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Retourne le pseudo de l'interlocuteur
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * Retourne l'avatar de l'interlocuteur
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Retourne le contenu du dernier message échangé
     * @return string|null
     */
    public function getLastMessage(): ?string
    {
        return $this->lastMessage;
    }

    /**
     * Retourne la date du dernier message au format DateTime
     * @return string|null
     */
    public function getLastMessageDate(): ?string
    {
        return $this->lastMessageDate;
    }

    /**
     * Retourne un aperçu limité du dernier message (50 caractères max)
     * @return string
     */
    public function getPreview(): string
    {
        if (!$this->lastMessage) {
            return 'Aucun message';
        }
        return strlen($this->lastMessage) > 50 
            ? substr($this->lastMessage, 0, 50) . '...' 
            : $this->lastMessage;
    }

    /**
     * Formate la date du dernier message de manière relative (il y a X temps)
     * @return string
     */
    public function getTimeAgo(): string
    {
        if (!$this->lastMessageDate) {
            return '';
        }

        $date = new DateTime($this->lastMessageDate);
        $now = new DateTime();
        $diff = $now->diff($date);

        if ($diff->days > 7) {
            return $date->format('d/m/Y');
        } elseif ($diff->days > 0) {
            return 'Il y a ' . $diff->days . ' jour' . ($diff->days > 1 ? 's' : '');
        } elseif ($diff->h > 0) {
            return 'Il y a ' . $diff->h . ' heure' . ($diff->h > 1 ? 's' : '');
        } elseif ($diff->i > 0) {
            return 'Il y a ' . $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
        } else {
            return 'À l\'instant';
        }
    }

    /**
     * Retourne le nom du fichier avatar ou l'avatar par défaut
     * @return string
     */
    public function getAvatarPath(): string
    {
        return $this->avatar ?? 'user.png';
    }
}
