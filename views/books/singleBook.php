<?php
$pageTitle = "Détails livre";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>

<section class="single-book">
    <div class="single-book-container">
        <!-- Partie gauche : Image -->
        <div class="book-image-section">
            <img src="<?= ROOT ?>/public/img/kinfolk_table.jpg" alt="The Kinfolk Table" class="single-book-image">
        </div>

        <!-- Partie droite : Titre -->
        <div class="book-info-section">
            <div class="book-info-content">
                <h1>The Kinfolk Table</h1>
                <p class="book-info-author">par Nathan Williams</p>
                <div class="separator-line"></div>
                <h3 class="section-title">DESCRIPTION</h3>
                <div class="book-description">
                    <p>J'ai récemment plongé dans les pages de 'The Kinfolk Table' et j'ai été enchanté par
                        cette œuvre captivante. Ce livre va bien au-delà d'une simple collection de recettes ;
                        il célèbre l'art de partager des moments authentiques autour de la table.</p>

                    <p>Les photographies magnifiques et le ton chaleureux captivent dès le départ, transportant
                        le lecteur dans un voyage à travers des recettes et des histoires qui mettent en avant
                        la beauté de la simplicité et de la convivialité.</p>

                    <p>Chaque page est une invitation à ralentir, à savourer et à créer des souvenirs durables
                        avec les êtres chers.</p>

                    <p>'The Kinfolk Table' incarne parfaitement l'esprit de la cuisine et de la camaraderie, et
                        il est certain que ce livre trouvera une place spéciale dans le cœur de tout amoureux de
                        la cuisine et des rencontres inspirantes.</p>
                </div>
                <h3 class="section-title">PROPRIÉTAIRE</h3>

                <!-- Lien vers le compte du propriétaire -->
                <a href="#" class="owner-profile-link">
                    <div class="owner-profile">
                        <img src="<?= ROOT ?>/public/img/alexlecture.jpg" alt="Alexlecture" class="owner-avatar">
                        <span class="owner-name">Alexlecture</span>
                    </div>
                </a>

                <!-- Bouton messagerie -->
                <a href="#" class="message-button">
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
