<?php
class UserController {
    public function index() {
        include ('views/users/account.php'); 
    }

     public function publicAccount() {
        include ('views/users/publicAccount.php'); 
    }

    public function register() {
        include ('views/users/register.php');       
    }

   public function login() {
        include ('views/users/login.php');       
    }
}