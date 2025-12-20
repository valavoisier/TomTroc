<?php
declare(strict_types=1);
/**
 * Classe Utils
 *
 * Classe utilitaire contenant uniquement des méthodes statiques.
 * Ces méthodes peuvent être appelées directement sans instancier un objet `Utils`.
 * Exemple d'utilisation : `Utils::askConfirmation('Êtes-vous sûr ?');`
 *
 * Responsabilités principales :
 * - Générer du code JavaScript à insérer dans des attributs HTML (onclick, onchange).
 * - Faciliter l'intégration de comportements interactifs (confirmation, aperçu d'image).
 * - Gérer la génération et la vérification des tokens CSRF pour la sécurité des formulaires.
 * Méthodes :
 * - askConfirmation() → confirmation sur bouton.
 * - askConfirmationOnChange() → confirmation + soumission formulaire sur input file.
 * - previewImage() → aperçu d’image sélectionnée dans un input file.
 * - generateCSRFToken() → génère un token CSRF et le stocke en session.
 * - verifyCSRFToken() → vérifie la validité d'un token CSRF soumis.
 * - csrfTokenField() → génère un champ input hidden avec le token CSRF.
 * 
 * @property Utils $instance Instance de la classe Utils (non instanciable).
 */
class Utils
{
    /**
     * Méthode askConfirmation() pour générer du code JavaScript de confirmation.
     *
     * Cette méthode :
     * - Retourne une chaîne contenant un attribut `onclick`.
     * - Affiche une popup de confirmation avec le message fourni.
     * - Exécute l'action uniquement si l'utilisateur clique sur "OK".
     *
     * @param string $message Message à afficher dans la popup de confirmation.
     * @return string Code JavaScript à insérer dans un bouton HTML.
     */
    public static function askConfirmation(string $message): string
    {
        return "onclick=\"return confirm('$message');\"";
    }

    /**
     * Méthode askConfirmationOnChange() pour générer du code JavaScript de confirmation sur un input file.
     *
     * Cette méthode :
     * - Retourne une chaîne contenant un attribut `onchange`.
     * - Affiche une popup de confirmation avec le message fourni.
     * - Soumet le formulaire identifié par $formId uniquement si l'utilisateur clique sur "OK".
     *
     * @param string $message Message à afficher dans la popup de confirmation.
     * @param string $formId  ID du formulaire à soumettre si confirmation.
     * @return string         Code JavaScript à insérer dans un input file HTML.
     */
    public static function askConfirmationOnChange(string $message, string $formId): string
    {
        return "onchange=\"if(confirm('$message')) { document.getElementById('$formId').submit(); }\"";
    }

    /**
     * Méthode previewImage() pour générer du code JavaScript d'aperçu d'image.
     *
     * Cette méthode :
     * - Retourne une chaîne contenant un attribut `onchange`.
     * - Charge le fichier sélectionné dans un input file.
     * - Affiche un aperçu de l'image dans l'élément <img> identifié par $imagePreviewId.
     * - L’image choisie par l’utilisateur est affichée immédiatement dans la page, sans envoi au serveur.
     * @param string $imagePreviewId ID de l'élément <img> où afficher l'aperçu.
     * @return string  Code JavaScript intégré dans attribut onchange à insérer dans un input file HTML.
     */
    public static function previewImage(string $imagePreviewId): string
    {
        // Utilisation de l'API FileReader pour lire le fichier sélectionné et afficher l'aperçu
        // event.target.files[0] : récupère le premier fichier sélectionné dans l'input file par l'utilisateur
        // reader.readAsDataURL(file) : lit le fichier et déclenche l'événement onload une fois la lecture terminée
        //reader.onload = function(e) { ... } : quand la lecture est terminée, on met à jour l'attribut src de l'image ciblée par $imagePreviewId.
        return "onchange=\"const file = event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = function(e) { document.getElementById('$imagePreviewId').src = e.target.result; }; reader.readAsDataURL(file); }\"";
    }

    /**
     * Génère un token CSRF unique et le stocke en session.
     * 
     * Cette méthode :
     * - Génère un token aléatoire sécurisé de 32 octets (64 caractères hexadécimaux)
     * - Stocke le token en session pour vérification ultérieure
     * - Doit être appelé avant d'afficher un formulaire
     * 
     * @return string Le token CSRF généré
     */
    public static function generateCSRFToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifie la validité d'un token CSRF soumis via formulaire.
     * 
     * Cette méthode :
     * - Compare le token soumis avec celui stocké en session
     * - Retourne true si les tokens correspondent
     * - Retourne false en cas de non-correspondance ou token manquant
     * - Doit être appelé lors du traitement d'un formulaire POST
     * 
     * @param string|null $token Le token CSRF à vérifier (depuis $_POST)
     * @return bool True si le token est valide, false sinon
     */
    public static function verifyCSRFToken(?string $token): bool
    {
        if (!isset($_SESSION['csrf_token']) || !$token) {
            return false;
        }
        
        // Utilisation de hash_equals pour éviter les timing attacks
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Génère un champ input hidden contenant le token CSRF.
     * 
     * Cette méthode :
     * - Génère automatiquement le token si nécessaire
     * - Retourne le HTML d'un input hidden à insérer dans les formulaires
     * - Simplifie l'ajout de protection CSRF dans les vues
     * 
     * @return string Code HTML de l'input hidden avec le token CSRF
     */
    public static function csrfTokenField(): string
    {
        $token = self::generateCSRFToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Affiche la page d'erreur 404 et arrête l'exécution.
     * 
     * Cette méthode :
     * - Définit le code de réponse HTTP à 404
     * - Affiche la vue d'erreur 404
     * - Termine l'exécution du script
     * - Utilisable depuis Router ou Controllers
     * 
     * @return void
     */
    public static function show404(): void
    {
        http_response_code(404);
        require_once 'views/errors/404.php';
        exit;
    }
}