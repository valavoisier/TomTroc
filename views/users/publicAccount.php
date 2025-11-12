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
                        <img src="img/nathalire.jpg" alt="Photo de profil">
                    </div>
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
                            <img src="img/livres.svg" alt="Livres" class="library-icon">
                            <p class="library-count">4 livres</p>
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
                    <tbody> <!-- Ligne 1 -->
                        <tr>
                            <td><img src="img/kinfolk_table.jpg" alt="Kinfolk Table"></td>
                            <td>The Kinfolk Table</td>
                            <td>Nathan Williams</td>
                            <td class="description"> J'ai récemment plongé dans les pages de 'The Kinfolk Table'
                                et j'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà d'une
                                simple collection de recettes ; il célèbre l'art de partager des moments
                                authentiques autour de la table. Les photographies magnifiques et le ton
                                chaleureux captivent dès le départ, transportant le lecteur dans un voyage à
                                travers des recettes et des histoires qui mettent en avant la beauté de la
                                simplicité et de la convivialité. Chaque page est une invitation à ralentir, à
                                savourer et à créer des souvenirs durables avec les êtres chers. 'The Kinfolk
                                Table' incarne parfaitement l'esprit de la cuisine et de la camaraderie, et il
                                est certain que ce livre trouvera une place spéciale dans le cœur de tout
                                amoureux de la cuisine et des rencontres inspirantes. </td>
                        </tr> <!-- Ligne 2 -->
                        <tr>
                            <td><img src="img/kinfolk_table.jpg" alt="Kinfolk Table"></td>
                            <td>The Kinfolk Table</td>
                            <td>Nathan Williams</td>
                            <td class="description"> J'ai récemment plongé dans les pages de 'The Kinfolk Table'
                                et j'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà d'une
                                simple collection de recettes ; il célèbre l'art de partager des moments
                                authentiques autour de la table. Les photographies magnifiques et le ton
                                chaleureux captivent dès le départ, transportant le lecteur dans un voyage à
                                travers des recettes et des histoires qui mettent en avant la beauté de la
                                simplicité et de la convivialité. Chaque page est une invitation à ralentir, à
                                savourer et à créer des souvenirs durables avec les êtres chers. 'The Kinfolk
                                Table' incarne parfaitement l'esprit de la cuisine et de la camaraderie, et il
                                est certain que ce livre trouvera une place spéciale dans le cœur de tout
                                amoureux de la cuisine et des rencontres inspirantes. </td>
                        </tr> <!-- Ligne 3 -->
                        <tr>
                            <td><img src="img/kinfolk_table.jpg" alt="Kinfolk Table"></td>
                            <td>The Kinfolk Table</td>
                            <td>Nathan Williams</td>
                            <td class="description"> J'ai récemment plongé dans les pages de 'The Kinfolk Table'
                                et j'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà d'une
                                simple collection de recettes ; il célèbre l'art de partager des moments
                                authentiques autour de la table. Les photographies magnifiques et le ton
                                chaleureux captivent dès le départ, transportant le lecteur dans un voyage à
                                travers des recettes et des histoires qui mettent en avant la beauté de la
                                simplicité et de la convivialité. Chaque page est une invitation à ralentir, à
                                savourer et à créer des souvenirs durables avec les êtres chers. 'The Kinfolk
                                Table' incarne parfaitement l'esprit de la cuisine et de la camaraderie, et il
                                est certain que ce livre trouvera une place spéciale dans le cœur de tout
                                amoureux de la cuisine et des rencontres inspirantes. </td>
                        </tr> <!-- Ligne 4 (avec radius bas) -->
                        <tr class="last-row">
                            <td><img src="img/kinfolk_table.jpg" alt="Kinfolk Table"></td>
                            <td>The Kinfolk Table</td>
                            <td>Nathan Williams</td>
                            <td class="description"> J'ai récemment plongé dans les pages de 'The Kinfolk Table'
                                et j'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà d'une
                                simple collection de recettes ; il célèbre l'art de partager des moments
                                authentiques autour de la table. Les photographies magnifiques et le ton
                                chaleureux captivent dès le départ, transportant le lecteur dans un voyage à
                                travers des recettes et des histoires qui mettent en avant la beauté de la
                                simplicité et de la convivialité. Chaque page est une invitation à ralentir, à
                                savourer et à créer des souvenirs durables avec les êtres chers. 'The Kinfolk
                                Table' incarne parfaitement l'esprit de la cuisine et de la camaraderie, et il
                                est certain que ce livre trouvera une place spéciale dans le cœur de tout
                                amoureux de la cuisine et des rencontres inspirantes. </td>
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
