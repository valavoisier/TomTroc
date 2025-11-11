<?php
/**
 * Fichier Autoload.php
 * Enregistre automatiquement les classes utilisées dans l'application.
 * Évite les inclusions manuelles grâce à la fonction spl_autoload_register().
 * Charge dynamiquement les fichiers de classe lorsque nécessaire.
 */

class Autoload {
    // création d'une méthode statique que l'on n'est pas obligé d'appeler en instanciant la classe    
    public static function register() {
        // Enregistre la méthode load comme autoloader pour le chargement des classes
        // __CLASS__ renvoie la classe courante actuellement en cours d'exécution, ici Autoload
        // spl_autoload_register() est une fonction PHP qui enregistre une fonction de rappel pour l'autoloading des classes
        // Cela signifie que lorsque PHP essaie de charger une classe qui n'est pas encore définie, il appellera la méthode load de cette classe
        // pour tenter de charger le fichier de la classe correspondante.
        spl_autoload_register([__CLASS__, 'load']);//function load récemment créée
    }
   
    /**
     * Méthode static pour charger dynamiquement les classes de contrôleurs.
     * @param string $className Le nom de la classe à charger.
     */
    public static function load($className) {
        $baseDir = __DIR__ . '/'; // Chemin vers le répertoire des contrôleurs.
        // La constante magique __DIR__ donne le chemin du répertoire courant
        // Tableau qui liste les repertoires où l'on trouve les classes
        $directories = [
            'controllers/',
            'models/',
            'views/',
            'core/',
            'core/db/',
            '/'
        ];
        // On parcourt le tableau des répertoires
        // On va chercher le fichier de la classe dans chacun des répertoires
        foreach ($directories as $directory) {
            // Concaténation du chemin de base, du répertoire et du nom de la classe avec l'extension .php
            // $directory est le dossier dans lequel se trouve la classe
            $file = $baseDir . $directory . $className . '.php';
            // Vérification de l'existence du fichier de la classe
            if (file_exists($file)) {
                require_once $file; // Inclusion du fichier de la classe
                return; // Sortie de la méthode si le fichier a été trouvé et inclus
            }
        }
    }     
    
}
// Appel de la méthode statique register() définie ci-dessus (ligne 12)
// pas besoin d'instancier la classe grâce au mot-clé static : Autoload::register()
Autoload::register(); // chargement automatique des classes