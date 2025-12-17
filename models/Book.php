<?php
/**
 * Modèle représentant un livre.
 * Propriétés : id, title, author, description, image, userId, createdAt, updatedAt, status.
 * @package Models
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
    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getAuthor(): string { return $this->author; }
    public function getDescription(): string { return $this->description; }
    public function getImage(): string { return $this->image ?: 'default-book.jpg'; }
    public function getUserId(): int { return $this->userId; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getUpdatedAt(): string { return $this->updatedAt; }
    public function getStatus(): int { return $this->status; }

    // Setters
    public function setTitle(string $title): void { $this->title = $title; }
    public function setAuthor(string $author): void { $this->author = $author; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setImage(?string $image): void { $this->image = $image; }
    public function setStatus(int $status): void { $this->status = (int)$status; }
    public function setUpdatedAt(string $updatedAt): void { $this->updatedAt = $updatedAt; }
}
