<?php
$pageTitle = "Compte publique";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>

<section class="account-section">
    <div class="account-container">
        <!-- Bloc principal -->
        <div class="public-account-box">
            <!-- Colonne gauche -->
            <div class="public-account-left">
                <!-- Bloc image -->
                <div class="avatar-block">
                    <div class="avatar-wrapper">
                        <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($user['avatar'] ?? 'user.png') ?>" alt="Photo de profil">
                    </div>
                </div>
                <!-- Séparateur -->
                <div class="separator-line"></div>
                <!-- Bloc identité complet -->
                <div class="identity-block">
                    <p class="pseudo"><?= htmlspecialchars($user['pseudo']) ?></p>
                    <p class="member-since"><?= $memberSince ?></p>
                    <div class="library-block">
                        <p class="library-label">BIBLIOTHÈQUE</p>
                        <div class="library-info">
                            <img src="<?= ROOT ?>/public/img/livres.svg" alt="Livres" class="library-icon">
                            <p class="library-count"><?= $bookCount ?> livres</p>
                        </div>
                    </div>
                </div>
                <!-- Bouton Écrire un message -->
                <a href="#" class="btn-message">Écrire un message</a>
            </div>
            <!-- Colonne droite -->
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
                                <td><img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book['image'] ?? 'default-book.jpg') ?>" alt="<?= htmlspecialchars($book['title']) ?>"></td>
                                <td><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td class="description">
                                    <?= htmlspecialchars($book['description']) ?>
                                </td>                               
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">
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
