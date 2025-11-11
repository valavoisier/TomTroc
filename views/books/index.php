<?php

$pageTitle = "Page d'accueil Tom Troc";

// Démarrage de la mise en tampon de sortie

ob_start();?>
 <!-- Section Hero-Header -->
        <section class="hero-header">
            <div class="hero-container">
                <!-- Div à gauche avec contenu texte -->
                <div class="hero-content">
                    <h1>Rejoignez nos lecteurs passionnés</h1>
                    <p>Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.</p>
                    <a href="available-books.html"><button class="btn-primary">Découvrir</button></a>
                </div>
                
                <!-- Div à droite avec image -->
                <div class="hero-image">
                    <img src="<?=ROOT?>/public/img/hamza-nouasria.jpg" alt="Lecteur passionné">
                    <p>Hamza</p>
                </div>
            </div>
        </section>
        
        <!-- Section Latest Books -->
        <section class="latest-books">
            <div class="latest-books-container">
                <h2>Les derniers livres ajoutés</h2>
                <div class="books-grid">
                    <!-- Card Livre 1 -->
                    <a href="single-book.html" class="card-livre-link">
                        <div class="card-livre">
                            <img src="<?=ROOT?>/public/img/esther.jpg" alt="Livre" class="book-image">
                            <div class="card-content">
                                <h3 class="book-title">Esther</h3>
                                <h4 class="book-subtitle">Alabaster</h4>
                                <p class="book-author">Vendu par: CamilleClubLit</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Card Livre 2 -->
                    <a href="single-book.html" class="card-livre-link">
                        <div class="card-livre">
                            <img src="<?=ROOT?>/public/img/kinfolk_table.jpg" alt="Livre" class="book-image">
                            <div class="card-content">
                                <h3 class="book-title">The Kinfolk Table</h3>
                                <h4 class="book-subtitle">Nathan Williams</h4>
                                <p class="book-author">Vendu par: Nathalire</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Card Livre 3 -->
                    <a href="single-book.html" class="card-livre-link">
                        <div class="card-livre">
                            <img src="<?=ROOT?>/public/img/wabi_Sabi.jpg" alt="Livre" class="book-image">
                            <div class="card-content">
                                <h3 class="book-title">Wabi Sabi</h3>
                                <h4 class="book-subtitle">Beth Kempton</h4>
                                <p class="book-author">Vendu par: Alexlecture</p>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Card Livre 4 -->
                    <a href="single-book.html" class="card-livre-link">
                        <div class="card-livre">
                            <img src="<?=ROOT?>/public/img/milk_honey.jpg" alt="Livre" class="book-image">
                            <div class="card-content">
                                <h3 class="book-title">Wabi Sabi</h3>
                                <h4 class="book-subtitle">Rupi Kaur</h4>
                                <p class="book-author">Vendu par: Hugo1990_12</p>
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- Bouton Voir tous les livres -->
                <a href="available-books.html"><button class="btn-primary">Voir tous les livres</button></a>
            </div>
        </section>
        <!-- Section getting started -->
        <section class="getting-started">
            <div class="getting-started-container">
                <h2>Comment ça marche ?</h2>
                <p class="intro-text">Échanger des livres avec TomTroc c'est simple et amusant ! Suivez ces étapes pour commencer :</p>
                
                <div class="steps-container">
                    <p class="step-text">Inscrivez-vous gratuitement sur notre plateforme.</p>
                    <p class="step-text">Ajoutez les livres que vous souhaitez échanger à votre profil.</p>
                    <p class="step-text">Parcourez les livres disponibles chez d'autres membres.</p>
                    <p class="step-text">Proposez un échange et discutez avec d'autres passionnés de lecture.</p>
                </div>
                
                <!-- Bouton Tous les livres -->
                <a href="available-books.html"><button class="btn-secondary">Voir tous les livres</button></a>
            </div>
        </section>
        <!-- Section library banner -->
        <section class="library-banner">
            <div class="library-banner-container">
                <!-- Contenu de la bannière public/img/library_banner.jpg ajouté ici / voir css -->
            </div>
        </section>
        <!-- Section Our Values -->
        <section class="our-values">
            <div class="values-content">
                <h2>Nos valeurs</h2>
                <p>Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs. Nous croyons en la puissance des histoires pour rassembler les gens et inspirer des conversations enrichissantes.</p>
                
                <p>Notre association a été fondée avec une conviction profonde : chaque livre mérite d'être lu et partagé.</p>
                
                <p>Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment sur les étagères.</p>
                
                <p class="team-signature">L'équipe Tom Troc</p>
            </div>
            <img src="<?=ROOT?>/public/img/vector_heart.svg" alt="Heart" class="heart-icon">
        </section>
<?php
// Récupération du contenu mis en tampon
// Cela permet de capturer le contenu HTML généré par cette page
$content = ob_get_clean();

// Inclusion du layout principal
// Le layout principal est généralement défini dans un fichier séparé
include('views/includes/template.php');