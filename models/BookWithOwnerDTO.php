<?php
/**
 * DTO (Data Transfer Object) pour transporter les données d'un livre avec son propriétaire
 * 
 * Ce DTO respecte le principe de séparation des responsabilités :
 * - Book représente uniquement l'entité livre (table books)
 * - User représente uniquement l'entité utilisateur (table users)
 * - BookWithOwnerDTO combine les données nécessaires pour l'affichage
 * 
 * Avantages :
 * - Respecte le principe SRP (Single Responsibility Principle)
 * - Facilite le transfert de données entre couches (Model → Controller → View)
 * - Évite la pollution des modèles métier avec des données d'affichage
 * 
 * @package Models
 */
class BookWithOwnerDTO
{
    private Book $book;
    private string $ownerPseudo;
    private string $ownerAvatar;

    public function __construct(Book $book, string $ownerPseudo, string $ownerAvatar)
    {
        $this->book = $book;
        $this->ownerPseudo = $ownerPseudo;
        $this->ownerAvatar = $ownerAvatar ?: 'user.png';
    }

    // Délégation vers l'objet Book
    public function getId() { return $this->book->getId(); }
    public function getTitle() { return $this->book->getTitle(); }
    public function getAuthor() { return $this->book->getAuthor(); }
    public function getDescription() { return $this->book->getDescription(); }
    public function getImage() { return $this->book->getImage(); }
    public function getUserId() { return $this->book->getUserId(); }
    public function getCreatedAt() { return $this->book->getCreatedAt(); }
    public function getUpdatedAt() { return $this->book->getUpdatedAt(); }
    public function getStatus() { return $this->book->getStatus(); }

    // Accès à l'objet Book complet si nécessaire
    public function getBook(): Book { return $this->book; }

    // Données du propriétaire
    public function getOwnerPseudo(): string { return $this->ownerPseudo; }
    public function getOwnerAvatar(): string { return $this->ownerAvatar; }

    // Alias pour compatibilité avec le code existant (à retirer progressivement)
    public function getPseudo(): string { return $this->ownerPseudo; }
    public function getAvatar(): string { return $this->ownerAvatar; }
}
