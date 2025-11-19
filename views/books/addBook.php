<?php

$pageTitle = "Ajout d'un nouveau livre";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>

<section class="edit-book">
    <div class="edit-book-container">
        <!-- Lien retour -->
        <a href="<?= ROOT ?>/user/account" class="back-link">← Retour</a>

        <!-- Titre principal -->
        <h1 class="edit-title">Ajouter un livre</h1>
         <?php if (isset($message)): ?>
                <div class="alert alert-error"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

        <!-- Bloc principal blanc -->
        <div class="edit-content-box">
           
            <form action="<?= ROOT ?>/book/registerBook" method="POST" enctype="multipart/form-data" class="edit-form">

                <!-- Partie gauche -->
                <div class="edit-left">
                    <p class="section-label">Photos</p>
                    <div class="book-image-wrapper">
                        <img src="<?= ROOT ?>/public/img/edit-book.jpg" alt="The Kinfolk Table">
                    </div>
                    <label for="upload-book-image" class="edit-photo-link">Modifier la photo</label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                    <input type="file" name="image" id="upload-book-image" class="upload-book-image" accept="image/*" <?= Utils::askConfirmationOnChange('Voulez-vous enregistrer cette nouvelle photo ?', 'add-book-form') ?>>



                </div>

                <!-- Partie droite -->
                <div class="edit-right">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" id="title" name="title">
                    </div>
                    <div class="form-group">
                        <label for="author">Auteur</label>
                        <input type="text" id="author" name="author">
                    </div>
                    <div class="form-group">
                        <label for="description">Commentaire</label>
                        <textarea id="description" name="description" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn-primary validate-btn">Valider</button>

                </div>
            </form>
        </div>
    </div>
</section>
<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();

// Inclusion du layout principal
include('views/includes/template.php');
