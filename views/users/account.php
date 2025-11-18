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
                <div class="avatar-block">
                    <div class="avatar-wrapper">
                        <img src="<?= ROOT ?>/public/img/nathalire.jpg" alt="Photo de profil">
                    </div>
                    <label for="upload-avatar" class="edit-avatar-link">Modifier</label>
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                    <input type="file" id="upload-avatar" class="upload-avatar" accept="image/*">
                </div>

                <!-- Séparateur -->
                <div class="separator-line"></div>

                <!-- Bloc identité complet -->
                <div class="identity-block">
                    <p class="pseudo">nathalire</p>
                    <p class="member-since">Membre depuis 1 an</p>
                    <div class="library-block">
                        <p class="library-label">BIBLIOTHÈQUE</p>
                        <div class="library-info">
                            <img src="<?= ROOT ?>/public/img/livres.svg" alt="Livres" class="library-icon">
                            <p class="library-count">4 livres</p>
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
                    <!-- Ligne 1 -->
                    <tr>
                        <td><img src="<?= ROOT ?>/public/img/kinfolk_table.jpg" alt="Kinfolk Table"></td>
                        <td>The Kinfolk Table</td>
                        <td>Nathan Williams</td>
                        <td class="description">
                            J'ai récemment plongé dans les pages de 'The Kinfolk Table' et j'ai été enchanté par
                            cette œuvre captivante. Ce livre va bien au-delà d'une simple collection de recettes
                            ; il célèbre l'art de partager des moments authentiques autour de la table.

                            Les photographies magnifiques et le ton chaleureux captivent dès le départ,
                            transportant le lecteur dans un voyage à travers des recettes et des histoires qui
                            mettent en avant la beauté de la simplicité et de la convivialité.

                            Chaque page est une invitation à ralentir, à savourer et à créer des souvenirs
                            durables avec les êtres chers.

                            'The Kinfolk Table' incarne parfaitement l'esprit de la cuisine et de la
                            camaraderie, et il est certain que ce livre trouvera une place spéciale dans le cœur
                            de tout amoureux de la cuisine et des rencontres inspirantes.
                        </td>
                        <td><span class="tag disponible">Disponible</span></td>
                        <td>
                            <a href="<?=ROOT?>/book/editBook" class="edit-link">Éditer</a>
                            <a href="#" class="delete-link">Supprimer</a>
                        </td>
                    </tr>

                    <!-- Ligne 2 -->
                    <tr>
                        <td><img src="<?= ROOT ?>/public/img/kinfolk_table.jpg" alt="Kinfolk Table"></td>
                        <td>The Kinfolk Table</td>
                        <td>Nathan Williams</td>
                        <td class="description">
                            J'ai récemment plongé dans les pages de 'The Kinfolk Table' et j'ai été enchanté par
                            cette œuvre captivante. Ce livre va bien au-delà d'une simple collection de recettes
                            ; il célèbre l'art de partager des moments authentiques autour de la table.

                            Les photographies magnifiques et le ton chaleureux captivent dès le départ,
                            transportant le lecteur dans un voyage à travers des recettes et des histoires qui
                            mettent en avant la beauté de la simplicité et de la convivialité.

                            Chaque page est une invitation à ralentir, à savourer et à créer des souvenirs
                            durables avec les êtres chers.

                            'The Kinfolk Table' incarne parfaitement l'esprit de la cuisine et de la
                            camaraderie, et il est certain que ce livre trouvera une place spéciale dans le cœur
                            de tout amoureux de la cuisine et des rencontres inspirantes.
                        </td>
                        <td><span class="tag non-dispo">Non dispo.</span></td>
                        <td>
                            <a href="<?=ROOT?>/book/editBook" class="edit-link">Éditer</a>
                            <a href="#" class="delete-link">Supprimer</a>
                        </td>
                    </tr>

                    <!-- Ligne 3 -->
                    <tr>
                        <td><img src="<?= ROOT ?>/public/img/kinfolk_table.jpg" alt="Kinfolk Table"></td>
                        <td>The Kinfolk Table</td>
                        <td>Nathan Williams</td>
                        <td class="description">
                            J'ai récemment plongé dans les pages de 'The Kinfolk Table' et j'ai été enchanté par
                            cette œuvre captivante. Ce livre va bien au-delà d'une simple collection de recettes
                            ; il célèbre l'art de partager des moments authentiques autour de la table.

                            Les photographies magnifiques et le ton chaleureux captivent dès le départ,
                            transportant le lecteur dans un voyage à travers des recettes et des histoires qui
                            mettent en avant la beauté de la simplicité et de la convivialité.

                            Chaque page est une invitation à ralentir, à savourer et à créer des souvenirs
                            durables avec les êtres chers.

                            'The Kinfolk Table' incarne parfaitement l'esprit de la cuisine et de la
                            camaraderie, et il est certain que ce livre trouvera une place spéciale dans le cœur
                            de tout amoureux de la cuisine et des rencontres inspirantes.
                        </td>
                        <td><span class="tag disponible">Disponible</span></td>
                        <td>
                            <a href="<?=ROOT?>/book/editBook" class="edit-link">Éditer</a>
                            <a href="#" class="delete-link">Supprimer</a>
                        </td>
                    </tr>

                    <!-- Ligne 4 (avec radius bas) -->
                    <tr class="last-row">
                        <td><img src="<?= ROOT ?>/public/img/kinfolk_table.jpg" alt="Kinfolk Table"></td>
                        <td>The Kinfolk Table</td>
                        <td>Nathan Williams</td>
                        <td class="description">
                            J'ai récemment plongé dans les pages de 'The Kinfolk Table' et j'ai été enchanté par
                            cette œuvre captivante. Ce livre va bien au-delà d'une simple collection de recettes
                            ; il célèbre l'art de partager des moments authentiques autour de la table.

                            Les photographies magnifiques et le ton chaleureux captivent dès le départ,
                            transportant le lecteur dans un voyage à travers des recettes et des histoires qui
                            mettent en avant la beauté de la simplicité et de la convivialité.

                            Chaque page est une invitation à ralentir, à savourer et à créer des souvenirs
                            durables avec les êtres chers.

                            'The Kinfolk Table' incarne parfaitement l'esprit de la cuisine et de la
                            camaraderie, et il est certain que ce livre trouvera une place spéciale dans le cœur
                            de tout amoureux de la cuisine et des rencontres inspirantes.
                        </td>
                        <td><span class="tag disponible">Disponible</span></td>
                        <td>
                            <a href="<?=ROOT?>/book/editBook" class="edit-link">Éditer</a>
                            <a href="#" class="delete-link">Supprimer</a>
                        </td>
                    </tr>
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
