<?php
$pageTitle = "Ajout d'un nouveau livre";
ob_start(); ?>

<section class="edit-book">
    <div class="edit-book-container">
        <a href="<?= ROOT ?>/user/account" class="back-link">← Retour</a>
        <h1 class="edit-title">Ajouter un livre</h1>

        <?php if (isset($message)): ?>
            <div class="alert alert-error" role="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="edit-content-box">
            <form action="<?= ROOT ?>/book/registerBook" method="POST" enctype="multipart/form-data" class="edit-form" id="add-book-form">
                <?= Utils::csrfTokenField() ?>
                <div class="edit-left">
                    <p class="section-label">Photos</p>
                    <div class="book-image-wrapper">
                        <img src="<?= ROOT ?>/public/img/cover/edit-book.jpg" alt="Aperçu du livre" id="preview-image">
                    </div>
                    <label for="upload-book-image" class="edit-photo-link" aria-label="Télécharger une photo du livre">Modifier la photo</label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                    <input type="file" name="image" id="upload-book-image" class="upload-book-image" accept="image/*" <?= Utils::previewImage('preview-image') ?>>
                </div>

                <div class="edit-right">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($formData['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required aria-required="true">
                    </div>
                    <div class="form-group">
                        <label for="author">Auteur</label>
                        <input type="text" id="author" name="author" value="<?= htmlspecialchars($formData['author'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required aria-required="true">
                    </div>
                    <div class="form-group">
                        <label for="description">Commentaire</label>
                        <textarea id="description" name="description" rows="10" required aria-required="true"><?= htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <button type="submit" class="btn-primary validate-btn">Valider</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include('views/includes/template.php');

