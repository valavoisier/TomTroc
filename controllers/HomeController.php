<?php
/**
 * Contrôleur pour la page d'accueil
  */ 
class HomeController
{
    // Méthode pour afficher la page d'accueil avec les 4 derniers livres ajoutés
    public function index()
    {
        $bookManager = new BookManager();
        $lastBooks = $bookManager->getLastBooks(4);
        require_once "views/books/index.php";
    }
}