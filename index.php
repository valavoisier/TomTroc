<?php
//Récupération de l'URL depuis les paramètres GET, par défaut '/' si non définie
$url = isset($_GET['url']) ? $_GET['url'] : '/';
//Découpage de l'URL en segments pour le routage
//trim() supprime les slashs de début et fin (ex: "/controller/action/" → "controller/action")
//explode() divise la chaîne en tableau selon le délimiteur '/'
$urlParts = explode('/', trim($url, '/'));
//Construire dynamiquement le nom du controlleur en fonction de la première partie de l'URL
//ucfirst() met la première lettre en majuscule pour respecter la convention de nommage des classes
//Ajout de 'Controller' à la fin pour obtenir le nom complet de la classe du contrôleur
//Si la première partie de l'URL est vide, on utilise 'HomeController' par défaut
$controllerName = (!empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'HomeController');
//Déterminer l'action en fonction de la deuxième partie de l'URL
//Si la deuxième partie de l'URL est vide, on utilise une méthode'index' par défaut
$action = (!empty($urlParts[1]) ? $urlParts[1] : 'index');
//Construction du chemin vers le fichier du contrôleur
//Concatène le dossier 'controllers/' avec le nom du contrôleur et l'extension '.php'
$controllerFile = 'controllers/' . $controllerName . '.php';
//mise en place du processus qui permet l'éxécution de la méthode du contrôleur après vérifications
//si chemin existe/ $controllerName.php existe
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    echo "le contrôleur existe<br>";
} else {
        // Si le nombre de paramètres fournis est insuffisant, on affiche une erreur
        echo "Erreur 404 - page non trouvée! - contrôleur non trouvé";
    }
