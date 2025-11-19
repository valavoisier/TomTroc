<?php
$pageTitle = "Modifier les informations du livre";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>

<section class="edit-book">
    <div class="edit-book-container">
        <!-- Lien retour -->
        <a href="<?= ROOT ?>/user/account" class="back-link">← Retour</a>

        <!-- Titre principal -->
        <h1 class="edit-title">Modifier les informations</h1>

        <!-- Bloc principal blanc -->
        <div class="edit-content-box">
            <form action="<?= ROOT ?>/book/editBook/<?= $book['id'] ?>" method="POST" enctype="multipart/form-data" class="edit-form" id="edit-book-form">

                <!-- Partie gauche -->
                <div class="edit-left">
                    <p class="section-label">Photos</p>
                    <div class="book-image-wrapper">
                        <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                    </div>
                    <label for="upload-book-image" class="edit-photo-link">Modifier la photo</label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                    <input type="file" name="image" id="upload-book-image" class="upload-book-image" accept="image/*" <?= Utils::askConfirmationOnChange('Voulez-vous enregistrer cette nouvelle photo ?', 'edit-book-form') ?>>


                </div>

                <!-- Partie droite -->
                <div class="edit-right">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="author">Auteur</label>
                        <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Commentaire</label>
                        <textarea id="description" name="description" rows="5"><?= htmlspecialchars($book['description']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Disponibilité</label>
                        <select id="status" name="status">
                            <option value="1" <?= $book['status'] == 1 ? 'selected' : '' ?>>Disponible</option>
                            <option value="0" <?= $book['status'] == 0 ? 'selected' : '' ?>>Non disponible</option>
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
