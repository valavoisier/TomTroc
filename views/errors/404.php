<?php
$pageTitle = "Erreur 404";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>

<section class="error-section">
    <div class="error-container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Page non trouvée</h2>
            <p class="error-message">
                Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
            </p>
            <a href="<?= ROOT ?>/" class="btn-primary">
                Retour à l'accueil
            </a>
        </div>
    </div>
</section>

<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();

// Inclusion du layout principal
include('views/includes/template.php');
