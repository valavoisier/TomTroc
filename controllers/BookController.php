<?php
class BookController {
    public function index() {
        echo "Bonjour BookController";
    }
    public function bookById($id) {
        echo "Affichage d'un livre par ID: $id";
    }
    public function list() {
        echo"Affichage de la liste des livres";
    }
}