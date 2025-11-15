    <?php
     
    /**
     * Fichier de configuration config.php
     * 
     * Ce fichier centralise toutes les constantes et paramètres de configuration de l'application.
     * Il est inclus dans Router.php pour définir automatiquement l'environnement d'exécution.
     * 
     * RÔLES PRINCIPAUX :
     * - Définir l'URL de base selon l'environnement (local/production)
     * - Centraliser les paramètres de base de données
     * - Gérer les configurations spécifiques à chaque environnement
     * - Éviter la duplication de paramètres dans l'application
     * 
     * USAGE DANS LE ROUTER :
     * Le Router utilise ces constantes pour construire les URLs absolues,
     * rediriger correctement et adapter le comportement selon l'environnement.
     */
    

    // Définition de la constante ROOT pour l'URL de base de l'application
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        // Environnement local
        define('ROOT', 'http://localhost/TomTroc'); 
        // Paramètres de connexion à la base de données locale
        define('DB_HOST', 'localhost');
        define('DB_USERNAME', 'root');
        define('DB_PASSWORD', '');
        define('DB_NAME', 'tomtroc_db');  
    } else {
        // Environnement de production
        define('ROOT', 'https://valerielavoisier.fr/TomTroc');
    }