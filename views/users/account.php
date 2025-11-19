<?php
$pageTitle = "Mon compte";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>

<section class="account-section">
    <div class="account-container">
        <!-- Titre principal -->
        <h1 class="account-title">Mon compte</h1>

        <!-- Bloc principal -->
        <div class="account-box">
            <!-- Colonne gauche -->
            <div class="account-left">
                <!-- Bloc image + modifier -->
                <form method="POST" action="<?= ROOT ?>/user/updateAvatar" enctype="multipart/form-data" id="avatar-form">
                    <div class="avatar-block">
                        <div class="avatar-wrapper">
                            <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($_SESSION['user']['avatar'] ?? 'user.png') ?>" alt="Photo de profil">
                        </div>
                        <label for="upload-avatar" class="edit-avatar-link">Modifier</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                        <input type="file" id="upload-avatar" class="upload-avatar" accept="image/*" name="avatar" <?= Utils::askConfirmationOnChange('Voulez-vous enregistrer cette nouvelle photo ?', 'avatar-form') ?>>
                    </div>
                </form>

                <!-- Séparateur -->
                <div class="separator-line"></div>

                <!-- Bloc identité complet -->
                <div class="identity-block">
                    <p class="pseudo"><?= htmlspecialchars($_SESSION['user']['pseudo'] ?? '') ?></p>
                    <p class="member-since"><?= $memberSince ?></p>
                    <div class="library-block">
                        <p class="library-label">BIBLIOTHÈQUE</p>
                        <div class="library-info">
                            <img src="<?= ROOT ?>/public/img/livres.svg" alt="Livres" class="library-icon">
                            <p class="library-count"><?= $bookCount ?> livres</p>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Colonne droite -->
            <div class="account-right">
                <h2 class="personal-title">Vos informations personnelles</h2>

                <form class="account-form" method="POST" action="<?= ROOT ?>/user/updateInfo">
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email"
                            value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" id="pseudo" name="pseudo"
                            value="<?= htmlspecialchars($_SESSION['user']['pseudo'] ?? '') ?>">
                    </div>

                    <button type="submit" class="btn-account">Enregistrer</button>
                </form>
            </div>

        </div>
        <div class="table-container">
            <table class="book-table">
                <thead>
                    <tr>
                        <th>PHOTO</th>
                        <th>TITRE</th>
                        <th>AUTEUR</th>
                        <th>DESCRIPTION</th>
                        <th>DISPONIBILITÉ</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($userBooks)): ?>
                        <?php foreach ($userBooks as $index => $book): ?>
                            <tr <?= ($index === count($userBooks) - 1) ? 'class="last-row"' : '' ?>>
                                <td><img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book['image'] ?? 'default-book.jpg') ?>" alt="<?= htmlspecialchars($book['title']) ?>"></td>
                                <td><?= htmlspecialchars($book['title']) ?></td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td class="description">
                                    <?= htmlspecialchars($book['description']) ?>
                                </td>
                                <td>
                                    <span class="tag <?= $book['status'] ? 'disponible' : 'non-dispo' ?>">
                                        <?= $book['status'] ? 'Disponible' : 'Non dispo.' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= ROOT ?>/book/editBook/<?= $book['id'] ?>" class="edit-link">Éditer</a>
                                    <a href="<?= ROOT ?>/book/deleteBook/<?= $book['id'] ?>" class="delete-link" <?= Utils::askConfirmation('Êtes-vous sûr de vouloir supprimer ce livre ?') ?>>Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">
                                Vous n'avez pas encore ajouté de livres.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <a href="<?= ROOT ?>/book/addBook" class="section-label">Ajouter un livre</a>
    </div>
</section>

<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();

// Inclusion du layout principal
include('views/includes/template.php');
