<?php
$pageTitle = "Détails livre";
// Démarrage de la mise en tampon de sortie
ob_start(); ?>
<section class="single-book">
    <div class="single-book-container">
        <!-- Partie gauche : Image -->
        <div class="book-image-section">
            <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book['image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="single-book-image">
        </div>
        <!-- Partie droite : Titre -->
        <div class="book-info-section">
            <div class="book-info-content">
                <h1><?= htmlspecialchars($book['title']) ?></h1>
                <p class="book-info-author">par <?= htmlspecialchars($book['author']) ?></p>
                <div class="separator-line"></div>
                <h3 class="section-title">DESCRIPTION</h3>
                <div class="book-description">
                    <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
                </div>
                <h3 class="section-title">PROPRIÉTAIRE</h3>
                <!-- Lien vers le compte du propriétaire -->
                <a href="<?= ROOT ?>/user/publicAccount/<?= $book['user_id'] ?>" class="owner-profile-link">
                    <div class="owner-profile">
                        <div class="owner-avatar">
                            <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book['avatar'] ?? 'user.png') ?>" alt="<?= htmlspecialchars($book['pseudo']) ?>">
                        </div>
                        <span class="owner-name"><?= htmlspecialchars($book['pseudo']) ?></span>
                    </div>
                </a>
                <!-- Bouton messagerie envoi au propriétaire -->
                <a href="<?= ROOT ?>/message/new/<?= $book['user_id'] ?>" class="message-button">
                    <button class="btn-message">Écrire un message</button>
                </a>
            </div>
        </div>
    </div>
</section>
<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();
// Inclusion du layout principal
include('views/includes/template.php');
