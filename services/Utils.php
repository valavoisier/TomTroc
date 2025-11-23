<?php
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
 * Méthodes :
 * - askConfirmation() → confirmation sur bouton.
 * - askConfirmationOnChange() → confirmation + soumission formulaire sur input file.
 * - previewImage() → aperçu d’image sélectionnée dans un input file.
 * 
 * @property Utils $instance Instance de la classe Utils (non instanciable).
 */
class Utils{
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
     *
     * @param string $imagePreviewId ID de l'élément <img> où afficher l'aperçu.
     * @return string                Code JavaScript à insérer dans un input file HTML.
     */
    public static function previewImage(string $imagePreviewId): string
    {
        return "onchange=\"const file = event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = function(e) { document.getElementById('$imagePreviewId').src = e.target.result; }; reader.readAsDataURL(file); }\"";
    }
}