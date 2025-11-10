<?php
//Récupération de l'URL depuis les paramètres GET, par défaut '/' si non définie
$url = isset($_GET['url']) ? $_GET['url'] : '/';
//Découpage de l'URL en segments pour le routage
//trim() supprime les slashs de début et fin (ex: "/controller/action/" → "controller/action")
//explode() divise la chaîne en tableau selon le délimiteur '/'
$urlParts = explode('/', trim($url, '/'));
//test affichage tableau de toutes es parties de l'URL
echo "<pre>";
print_r($urlParts);
echo "</pre>";
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
//mise en place du processus qui permet l'éxécution de la méthode du contrôleur après vérifications:
//1) si chemin existe/ fichier $controllerName.php existe alors on l'inclut
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    //fonction native vérification éxistance classe, si éxiste on l'instancie
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        //méthode native vérification éxistance méthode dans la classe
        //on cherche méthode $actiondans objet $controller
        if (method_exists($controller, $action)) {
            //récupération de tous les paramètres supplémentaires dans l'URL
            //fonction array_slice() pour obtenir un tableau des paramètres à partir du 3ème segment de l'URL
            $params = array_slice($urlParts, 2);
            //test affichage tableau paramètres
            echo "<pre>";
            print_r($params);
            echo "</pre>";
            //récupération des informations de la méthode (stockées dans $action) à éxécuter avec la classe native ReflectionMethod que l'on instancie
            $reflectionMethod = new ReflectionMethod($controller, $action); //objet $controller et méthode $action
            //utilisation de la méthode getParameters de la classe ReflectionMethod pour obtenir le nombre de paramètres requis par la méthode
            $methodParams = $reflectionMethod->getParameters();
            foreach ($methodParams as $parametre) {
                echo "Paramètre : " . $parametre->getName() . "<br>"; //affiche un seul paramètre dans chaque ligne
            }
            //Appel dynamique de la méthode du contrôleur
            $controller->$action();
        } else {
            // Si la méthode n'existe pas, on affiche une erreur
            echo "Erreur 404 - page non trouvée! - méthode(action) non trouvée";
        }
    } else {
        // Si la méthode n'existe pas, on affiche une erreur
        echo "Erreur 404 - page non trouvée! - classe non trouvée";
    }
} else {
    // Si absence fichier, on affiche une erreur
    echo "Erreur 404 - page non trouvée! - contrôleur non trouvé";
}
