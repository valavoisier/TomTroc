<?php
$pageTitle = "Détails livre";
// Démarrage de la mise en tampon de sortie
ob_start(); ?>
<!-- Fil d'Ariane -->
<nav class="breadcrumb" aria-label="Fil d'ariane">
    <div class="breadcrumb-container">
        <a href="available-books.html">Nos livres</a>
        <span class="breadcrumb-separator"> > </span>
        <span class="breadcrumb-current"><?= htmlspecialchars($book->getTitle()) ?></span>
    </div>
</nav>
<section class="single-book">
    <div class="single-book-container">
        <!-- Partie gauche : Image -->
        <div class="book-image-section">
            <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book->getImage() ?? 'default-book.jpg') ?>"
                alt="<?= htmlspecialchars($book->getTitle()) ?>"
                class="single-book-image">
        </div>
        <!-- Partie droite : Titre -->
        <div class="book-info-section">
            <div class="book-info-content">
                <h1><?= htmlspecialchars($book->getTitle()) ?></h1>
                <p class="book-info-author">par <?= htmlspecialchars($book->getAuthor()) ?></p>
                <div class="book-separator-line"></div>
                <h3 class="section-title">DESCRIPTION</h3>
                <div class="book-description">
                    <p><?= str_replace('<br />', '<br>', nl2br(htmlspecialchars($book->getDescription()))) ?></p>
                </div>
                <h3 class="section-title">PROPRIÉTAIRE</h3>
                <!-- Lien vers le compte du propriétaire -->
                <?php
                // Si le livre appartient à l'utilisateur connecté, lien vers son compte
                // Sinon, lien vers le profil public du propriétaire
                $profileUrl = (isset($_SESSION['user']) && (int)$_SESSION['user']['id'] === (int)$book->getUserId())
                    ? ROOT . '/user/account'
                    : ROOT . '/user/publicAccount/' . $book->getUserId();
                ?>
                <a href="<?= $profileUrl ?>" class="owner-profile-link">
                    <div class="owner-profile">
                        <div class="owner-avatar">
                            <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book->getAvatar() ?? 'user.png') ?>"
                                alt="<?= htmlspecialchars($book->getPseudo()) ?>">
                        </div>
                        <span class="owner-name"><?= htmlspecialchars($book->getPseudo()) ?></span>
                    </div>
                </a>
                <!-- Bouton messagerie : lien vers la page messages -->
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ((int)$_SESSION['user']['id'] !== (int)$book->getUserId()): ?>
                        <!-- Utilisateur connecté et ce n'est pas son propre livre -->
                        <a href="<?= ROOT ?>/message/conversation/<?= (int)$book->getUserId() ?>" class="btn-primary btn-message-large">
                            Envoyer un message
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Utilisateur non connecté : bouton redirige vers login -->
                    <a href="<?= ROOT ?>/user/login" class="btn-primary btn-message-large">
                        Envoyer un message
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();
// Inclusion du layout principal
include('views/includes/template.php');
