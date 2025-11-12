<?php
class BookController {
    public function index() {
        echo "Bonjour BookController";
    }

    public function availableBooks() {
        include ('views/books/availableBooks.php');       
    }

    public function editBook() {
        include ('views/books/editBook.php');               
    }
    public function singleBook() {
        include ('views/books/singleBook.php');       
    }
}