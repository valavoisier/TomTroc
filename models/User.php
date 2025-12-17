<?php
/**
 * Modèle représentant un utilisateur.
 * Responsabilités principales :
 * - Stocker les propriétés d'un utilisateur (id, pseudo, email, password, avatar, createdAt, updatedAt).
 * - Fournir des getters et setters pour accéder et modifier ces propriétés.
 * @package Models
 * 
 */
class User {
    private $id;
    private $pseudo;
    private $email;
    private $password;
    private $avatar;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        $id,
        $pseudo,
        $email,
        $password,
        $avatar,
        $createdAt,
        $updatedAt
    ) {
        $this->id = (int) $id;
        $this->pseudo = $pseudo;
        $this->email = $email;
        $this->password = $password;
        $this->avatar = $avatar ?: 'user.png';
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getPseudo(): string { return $this->pseudo; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getAvatar(): string { return $this->avatar; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getUpdatedAt(): string { return $this->updatedAt; }

    // Setters
    public function setPseudo(string $pseudo): void { $this->pseudo = $pseudo; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setAvatar(string $avatar): void { $this->avatar = $avatar ?: 'user.png'; }
    public function setUpdatedAt(string $updatedAt): void { $this->updatedAt = $updatedAt; }
}
