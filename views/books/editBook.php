<?php
$pageTitle = "Modifier les informations du livre";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>

<section class="edit-book">
    <div class="edit-book-container">
        <!-- Lien retour -->
        <a href="<?= ROOT ?>/book/availableBooks" class="back-link">← Retour</a>

        <!-- Titre principal -->
        <h1 class="edit-title">Modifier les informations</h1>

        <!-- Bloc principal blanc -->
        <div class="edit-content-box">
            <form action="<?= ROOT ?>/book/registerBook" method="POST" enctype="multipart/form-data" class="edit-form">

                <!-- Partie gauche -->
                <div class="edit-left">
                    <p class="section-label">Photos</p>
                    <div class="book-image-wrapper">
                        <img src="<?= ROOT ?>/public/img/kinfolk_table.jpg" alt="The Kinfolk Table">
                    </div>

                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                    <input type="file" name="image" id="image" accept="image/*">


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
                    <div class="form-group">
                        <label for="status">Disponibilité</label>
                        <select id="status" name="status">
                            <option value="disponible">Disponible</option>
                            <option value="non-dispo.">Non disponible</option>
                        </select>
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
