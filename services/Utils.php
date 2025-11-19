<?php

/**
 * Classe utilitaire : cette classe ne contient que des méthodes statiques qui peuvent être appelées
 * directement sans avoir besoin d'instancier un objet Utils.
 * Exemple : Utils::redirect('home'); 
 */
class Utils{
    /**
     * Cette méthode retourne le code js a insérer en attribut d'un bouton.
     * pour ouvrir une popup "confirm", et n'effectuer l'action que si l'utilisateur
     * a bien cliqué sur "ok".
     * @param string $message : le message à afficher dans la popup.
     * @return string : le code js à insérer dans le bouton.
     */
    public static function askConfirmation(string $message): string
    {
        return "onclick=\"return confirm('$message');\"";
    }

    /**
     * Cette méthode retourne le code js à insérer en attribut d'un input file.
     * pour ouvrir une popup "confirm" et soumettre le formulaire si l'utilisateur clique sur "ok".
     * @param string $message : le message à afficher dans la popup.
     * @param string $formId : l'ID du formulaire à soumettre.
     * @return string : le code js à insérer dans l'input.
     */
    public static function askConfirmationOnChange(string $message, string $formId): string
    {
        return "onchange=\"if(confirm('$message')) { document.getElementById('$formId').submit(); }\"";
    }

    /**
     * Cette méthode retourne le code js pour afficher un aperçu d'image et soumettre le formulaire après confirmation.
     * @param string $imagePreviewId : l'ID de l'élément img où afficher l'aperçu.
     * @return string : le code js à insérer dans l'input file.
     */
    public static function previewImage(string $imagePreviewId): string
    {
        return "onchange=\"const file = event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = function(e) { document.getElementById('$imagePreviewId').src = e.target.result; }; reader.readAsDataURL(file); }\"";
    }
}