<?php
require_once 'Autoload.php';//Autochargement des classes
// Création d'une instance de la classe Router et appel de la méthode de routage
$router = new Router();
$router->routeRequest();