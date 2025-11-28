<?php
$pageTitle = "Compte publique";
// Démarrage de la mise en tampon de sortie
ob_start(); ?>
<section class="account-section">
    <div class="account-container">
        <!-- Bloc principal -->
        <div class="public-account-box">
            <!-- Colonne gauche avatar/infos -->
            <div class="public-account-left">
                <!-- Bloc avatar -->
                <div class="avatar-block">
                    <div class="avatar-wrapper">
                        <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($user->getAvatar() ?? 'user.png') ?>" alt="Photo de profil">
                    </div>
                </div>
                <!-- Séparateur -->
                <div class="account-separator-line"></div>
                <!-- Bloc identité infos complet -->
                <div class="identity-block">
                    <p class="pseudo"><?= htmlspecialchars($user->getPseudo()) ?></p>
                    <p class="member-since"><?= $memberSince ?></p>
                    <div class="library-block">
                        <p class="library-label">BIBLIOTHÈQUE</p>
                        <div class="library-info">
                            <img src="<?= ROOT ?>/public/img/livres.svg" alt="Livres" class="library-icon">
                            <p class="library-count"><?= $bookCount ?> livres</p>
                        </div>
                    </div>
                </div>
                <!-- Bouton messagerie envoi au propriétaire -->
                <?php if ($_SESSION['user']['id'] ?? null): ?>
                    <?php if ($_SESSION['user']['id'] !== (int)$user->getId()): ?>
                        <!-- Connecté et pas le propriétaire -->
                        <a href="<?= ROOT ?>/message/conversation/<?= (int)$user->getId() ?>" class="message-button">
                            <button class="btn-message">Écrire un message</button>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Non connecté -->
                    <a href="<?= ROOT ?>/user/login" class="message-button">
                        <button class="btn-message">Écrire un message</button>
                    </a>
                <?php endif; ?>
            </div>
            <!-- Colonne droite / liste des livres-->
            <div class="public-table-container">
                <table class="book-table">
                    <thead>
                        <tr>
                            <th>PHOTO</th>
                            <th>TITRE</th>
                            <th>AUTEUR</th>
                            <th>DESCRIPTION</th>
                        </tr>
                    </thead>
                    <tbody> <!-- Ligne -->
                        <?php if (!empty($userBooks)): ?>
                            <?php foreach ($userBooks as $index => $book): ?>
                                <tr <?= ($index === count($userBooks) - 1) ? 'class="last-row"' : '' ?>>
                                    <td><img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book->getImage() ?? 'default-book.jpg') ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>"></td>
                                    <td><?= htmlspecialchars($book->getTitle()) ?></td>
                                    <td><?= htmlspecialchars($book->getAuthor()) ?></td>
                                    <td class="description">
                                        <?= htmlspecialchars($book->getDescription()) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td>
                                    Cet utilisateur ne propose pas de livres actuellement.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
</section>
<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();
// Inclusion du layout principal
include('views/includes/template.php');
