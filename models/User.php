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
    public function getId() { return $this->id; }
    public function getPseudo() { return $this->pseudo; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getAvatar() { return $this->avatar; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }

    // Setters
    public function setPseudo($pseudo) { $this->pseudo = $pseudo; }
    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = $password; }
    public function setAvatar($avatar) { $this->avatar = $avatar ?: 'user.png'; }
    public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }
}
