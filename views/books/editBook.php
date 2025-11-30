<?php
$pageTitle = "Modifier les informations du livre";
ob_start(); ?>

<section class="edit-book">
    <div class="edit-book-container">
        <a href="<?= ROOT ?>/user/account" class="back-link">← Retour</a>
        <h1 class="edit-title">Modifier les informations</h1>

        <?php if (isset($message)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="edit-content-box">
            <form action="<?= ROOT ?>/book/editBook/<?= $book->getId() ?>" method="POST" enctype="multipart/form-data" class="edit-form" id="edit-book-form">
                <div class="edit-left">
                    <p class="section-label">Photos</p>
                    <div class="book-image-wrapper">
                        <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book->getImage()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>" id="preview-image">
                    </div>
                    <label for="upload-book-image" class="edit-photo-link">Modifier la photo</label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                    <input type="file" name="image" id="upload-book-image" class="upload-book-image" accept="image/*" <?= Utils::askConfirmationOnChange('Voulez-vous enregistrer cette nouvelle photo ?', 'edit-book-form') ?>>
                </div>

                <div class="edit-right">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($formData['title'] ?? $book->getTitle(), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="form-group">
                        <label for="author">Auteur</label>
                        <input type="text" id="author" name="author" value="<?= htmlspecialchars($formData['author'] ?? $book->getAuthor(), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Commentaire</label>
                        <textarea id="description" name="description" rows="5"><?= htmlspecialchars($formData['description'] ?? $book->getDescription(), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Disponibilité</label>
                        <select id="status" name="status">
                            <option value="1" <?= $book->getStatus() == 1 ? 'selected' : '' ?>>Disponible</option>
                            <option value="0" <?= $book->getStatus() == 0 ? 'selected' : '' ?>>Non disponible</option>
                        </select>
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

