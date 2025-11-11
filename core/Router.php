<?php
// Inclusion du fichier de configuration pour charger les constantes et configurations de l'application
require_once 'config.php';
class Router
{
    public function routeRequest()
    {
        // Récupération de l'URL depuis les paramètres GET, par défaut '/' si non définie
        $url = isset($_GET['url']) ? $_GET['url'] : '/';

        // Découpage de l'URL en segments pour préparer le routage
        // trim() supprime les slashs de début et fin (ex: "/controller/action/" → "controller/action")
        // explode() divise la chaîne en tableau selon le délimiteur '/'
        $urlParts = explode('/', trim($url, '/'));

        // Construire dynamiquement le nom du controlleur en fonction du premier segment de l'URL
        // ucfirst() met la première lettre en majuscule pour respecter la convention de nommage des classes
        // Ajout de 'Controller' à la fin pour obtenir le nom complet de la classe du contrôleur
        // Si le premier segment de l'URL est vide, on utilise 'HomeController' par défaut
        $controllerName = (!empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'HomeController');

        // Déterminer l'action à éxécuter en fonction du deuxième segment de l'URL
        // Si le deuxième segment de l'URL est vide, on utilise la méthode 'index' de la classe par défaut
        $action = (!empty($urlParts[1]) ? $urlParts[1] : 'index');

        // Construction du chemin vers le fichier du contrôleur correspondant
        // Concatène le dossier 'controllers/' avec le nom du contrôleur et l'extension '.php'
        $controllerFile = 'controllers/' . $controllerName . '.php';

        // Mise en place du processus de routage après vérifications :
        // 1) Vérifier si le fichier du contrôleur existe.
        // 2) Si oui, l’inclure pour pouvoir utiliser sa classe.
        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            // Vérification que la classe du contrôleur existe bien dans le fichier inclus.
            // Si c’est le cas, on instancie un objet de cette classe.
            if (class_exists($controllerName)) {
                $controller = new $controllerName();

                // Vérification que la méthode correspondant à l’action existe dans la classe du contrôleur.
                // Si elle existe, on peut la préparer pour exécution.
                // on cherche méthode $action dans objet $controller
                if (method_exists($controller, $action)) {

                    // Récupération des éventuels paramètres supplémentaires passés dans l’URL.
                    // array_slice() permet de récupérer tous les segments (tableau) à partir du 3ème segment (indice 2),
                    // ce qui correspond aux paramètres après '/controller/action/...'.
                    $params = array_slice($urlParts, 2);

                    // Utilisation de la classe native ReflectionMethod pour analyser la méthode à exécuter.
                    // récupération des informations de la méthode (stockées dans $action) 
                    // On crée un objet ReflectionMethod basé sur le contrôleur et l’action.
                    $reflectionMethod = new ReflectionMethod($controller, $action); //objet $controller et méthode $action

                    // Récupération de la liste des paramètres attendus par cette méthode.
                    // getParameters() retourne un tableau décrivant chaque paramètre.
                    // utilisation pour obtenir le nombre de paramètres requis par la méthode
                    $methodParams = $reflectionMethod->getParameters();

                    // Vérification du nombre de paramètres requis par la méthode.
                    // Si la méthode attend au moins un paramètre (count > 0), on compare avec ceux fournis dans l’URL.
                    if (count($methodParams) > 0) {

                        // Vérification que le nombre de paramètres passés dans l’URL (stockés dans $params)
                        // est suffisant par rapport au nombre de paramètres attendus (stockés dans $methodParams)
                        if (count($params) >= count($methodParams)) {

                            //Appel dynamique de la méthode du contrôleur avec les paramètres
                            call_user_func_array([$controller, $action], $params); //tableau avec 1er élément=objet, 2ème=méthode a éxécuter
                            //revient à l'Appel dynamique de la méthode du contrôleur  $controller->$action();
                        } else {
                            // Si le nombre de paramètres est insuffisant, on affiche une erreur
                            echo "Erreur 404 - page non trouvée! - nombre de paramètres passés en URL insuffisant";
                        }
                    } else {
                        // Si la méthode n’attend aucun paramètre, on l’appelle directement sans arguments.
                        // Appel dynamique de la méthode du contrôleur
                        $controller->$action();
                    }
                } else {
                    // Si la méthode correspondant à l’action n’existe pas dans le contrôleur, on affiche une erreur.                    // Si la méthode n'existe pas, on affiche une erreur
                    echo "Erreur 404 - page non trouvée! - méthode(action) non trouvée";
                }
            } else {
                // Si la classe du contrôleur n’existe pas dans le fichier inclus, on affiche une erreur.
                echo "Erreur 404 - page non trouvée! - classe non trouvée";
            }
        } else {
            // Si le fichier du contrôleur n’existe pas dans le dossier 'controllers/', on affiche une erreur.
            echo "Erreur 404 - page non trouvée! - contrôleur non trouvé";
        }
    }
}
