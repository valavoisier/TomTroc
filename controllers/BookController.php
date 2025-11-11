<?php
class BookController {
    public function index() {
        echo "Bonjour BookController";
    }
     public function addBook() {
        include ('views/books/addBook.php');       
    }
    public function registerBook() {
         echo "processus enregistrement nouveau livre";   
    }
     public function editBook() {
        include ('views/books/editBook.php');       
    }
    public function bookById($id) {
        echo "Affichage d'un livre par ID: $id";
    }
    public function list() {
        echo"Affichage de la liste des livres";
    }
}