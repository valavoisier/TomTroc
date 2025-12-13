<?php
$pageTitle = "Mon compte";
// Démarrage de la mise en tampon de sortie
ob_start(); ?>
<section class="account-section">
    <div class="account-container">
        <!-- Titre principal -->
        <h1 class="account-title">Mon compte</h1>
        <!-- Boîte principale : gauche (infos et avatar) et droite (formulaire) -->
        <div class="account-box">
            <!-- Colonne gauche -->
            <div class="account-left">
                <!-- Formulaire modification avatar -->
                <form method="POST" action="<?= ROOT ?>/user/updateAvatar" enctype="multipart/form-data" id="avatar-form">
                    <?= Utils::csrfTokenField() ?>
                    <div class="avatar-block">
                        <div class="avatar-wrapper">
                            <img src="<?= ROOT ?>/public/img/avatar/<?= htmlspecialchars(($user ? $user->getAvatar() : ($_SESSION['user']['avatar'] ?? 'user.png'))) ?>" alt="Photo de profil">
                        </div>
                        <label for="upload-avatar" class="edit-avatar-link" aria-label="Télécharger une nouvelle photo de profil">Modifier</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                        <input type="file" id="upload-avatar" class="upload-avatar" accept="image/*" name="avatar" 
                               <?= Utils::askConfirmationOnChange('Voulez-vous enregistrer cette nouvelle photo ?', 'avatar-form') ?>>
                    </div>
                </form>
                <!-- Séparateur -->
                <div class="account-separator-line"></div>
                <!-- Bloc Identité -->
                <div class="identity-block">
                    <p class="pseudo"><?= htmlspecialchars(($user ? $user->getPseudo() : ($_SESSION['user']['pseudo'] ?? ''))) ?></p>
                    <p class="member-since"><?= $memberSince ?></p>
                    <div class="library-block">
                        <p class="library-label">BIBLIOTHÈQUE</p>
                        <div class="library-info">
                            <img src="<?= ROOT ?>/public/img/livres.svg" alt="" class="library-icon" aria-hidden="true">
                            <p class="library-count"><?= $bookCount ?> livres</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Colonne droite: Informations personnelles -->
            <div class="account-right">
                <h2 class="personal-title">Vos informations personnelles</h2>
                <form class="account-form" method="POST" action="<?= ROOT ?>/user/updateInfo">
                    <?= Utils::csrfTokenField() ?>
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars(($user ? $user->getEmail() : ($_SESSION['user']['email'] ?? ''))) ?>" aria-required="true">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" id="pseudo" name="pseudo"
                               value="<?= htmlspecialchars(($user ? $user->getPseudo() : ($_SESSION['user']['pseudo'] ?? ''))) ?>" aria-required="true">
                    </div>
                    <button type="submit" class="btn-account" aria-label="Enregistrer les modifications du compte">Enregistrer</button>
                </form>
            </div>
        </div>
        <!-- Tableau des livres en dessous -->
        <div class="table-container">
            <table class="book-table">
                <caption class="sr-only">Liste de vos livres</caption>
                <thead>
                    <tr>
                        <th scope="col">PHOTO</th>
                        <th scope="col">TITRE</th>
                        <th scope="col">AUTEUR</th>
                        <th scope="col">DESCRIPTION</th>
                        <th scope="col">DISPONIBILITÉ</th>
                        <th scope="col">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($userBooks)): ?>
                        <?php foreach ($userBooks as $index => $book): ?>
                            <?php
                                $image = is_object($book) ? $book->getImage() : ($book['image'] ?? 'default-book.jpg');
                                $title = is_object($book) ? $book->getTitle() : $book['title'];
                                $author = is_object($book) ? $book->getAuthor() : $book['author'];
                                $description = is_object($book) ? $book->getDescription() : $book['description'];
                                $status = is_object($book) ? $book->getStatus() : $book['status'];
                                $id = is_object($book) ? $book->getId() : $book['id'];
                            ?>
                            <tr <?= ($index === count($userBooks) - 1) ? 'class="last-row"' : '' ?>>
                                <td><img src="<?= ROOT ?>/public/img/cover/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($title) ?>"></td>
                                <td><?= htmlspecialchars($title) ?></td>
                                <td><?= htmlspecialchars($author) ?></td>
                                <td class="description"><?= htmlspecialchars($description) ?></td>
                                <td>
                                    <span class="tag <?= $status ? 'disponible' : 'non-dispo' ?>">
                                        <?= $status ? 'Disponible' : 'Non dispo.' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= ROOT ?>/book/editBook/<?= $id ?>" class="edit-link">Éditer</a>
                                    <a href="<?= ROOT ?>/book/deleteBook/<?= $id ?>" class="delete-link" 
                                       <?= Utils::askConfirmation('Êtes-vous sûr de vouloir supprimer ce livre ?') ?>>
                                       Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td>
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
// Inclusion du template principal
include('views/includes/template.php');


