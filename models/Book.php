<?php
/**
 * Modèle représentant un livre.
 * Responsabilités principales :
 * - Stocker les propriétés d'un livre (id, titre, auteur, description, image, userId, createdAt, updatedAt, status).
 * - Fournir des getters et setters pour accéder et modifier ces propriétés.
 * @package Models
 * @uses User Pour accéder aux informations de l'utilisateur (pseudo, avatar)
 * 
 */
class Book {
    private $id;
    private $title;
    private $author;
    private $description;
    private $image;
    private $userId;
    private $createdAt;
    private $updatedAt;
    private $status;
    private $pseudo;
    private $avatar;

    public function __construct($id, $title, $author, $description, $image, $userId, $createdAt, $updatedAt, $status) {
        $this->id = $id ? (int)$id : null;
        $this->title = $title;
        $this->author = $author;
        $this->description = $description;
        $this->image = $image;
        $this->userId = (int)$userId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->status = (int)$status;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getAuthor() { return $this->author; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image ?: 'default-book.jpg'; }
    public function getUserId() { return $this->userId; }
    public function getCreatedAt() { return $this->createdAt; }
    public function getUpdatedAt() { return $this->updatedAt; }
    public function getStatus() { return $this->status; }

    // Setters
    public function setTitle($title) { $this->title = $title; }
    public function setAuthor($author) { $this->author = $author; }
    public function setDescription($description) { $this->description = $description; }
    public function setImage($image) { $this->image = $image; }
    public function setStatus($status) { $this->status = (int)$status; }
    public function setUpdatedAt($updatedAt) { $this->updatedAt = $updatedAt; }

    // Utilisateur
    public function getPseudo() { return $this->pseudo; }
    public function setPseudo($pseudo) { $this->pseudo = $pseudo; }

    public function getAvatar() { return $this->avatar ?: 'user.png'; }
    public function setAvatar($avatar) { $this->avatar = $avatar; }
}
