<?php
/**
 * Contrôleur pour la page d'accueil
  */ 
class HomeController
{
    /**
     * Affiche la page d'accueil avec les 4 derniers livres ajoutés.
     *
     * Cette méthode :
     * - Instancie BookManager pour accéder aux données des livres.
     * - Appelle BookManager::getLastBooks(4) afin de récupérer les 4 derniers livres ajoutés en base.
     * - Inclut la vue correspondante (views/books/index.php) pour afficher ces livres sur la page d'accueil.
     * @return void
     * @uses BookManager::getLastBooks() Pour récupérer les derniers livres ajoutés.
     */
    public function index()
    {
        $bookManager = new BookManager();
        $lastBooks = $bookManager->getLastBooks(4);
        require_once "views/books/index.php";
    }
}