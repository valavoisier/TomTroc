<?php
$pageTitle = "Page d'accueil Tom Troc";
ob_start(); ?>
<!-- Section Hero-Header -->
<section class="hero-header">
    <div class="hero-container">
        <div class="hero-content">
            <h1>Rejoignez nos lecteurs passionnés</h1>
            <p>Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.</p>
            <a href="<?= ROOT ?>/book/availableBooks" class="btn-primary">Découvrir</a>
        </div>
        <div class="hero-image">
            <img src="<?= ROOT ?>/public/img/hamza-nouasria.jpg" alt="Lecteur passionné">
            <p>Hamza</p>
        </div>
    </div>
</section>

<section class="latest-books">
    <div class="latest-books-container">
        <h2>Les derniers livres ajoutés</h2>
        <div class="books-grid">
            <?php foreach ($lastBooks as $book) : ?>
                <a href="<?= ROOT ?>/book/singleBook/<?= $book->getId() ?>" class="card-livre-link">
                    <div class="card-livre">
                        <img src="<?= ROOT ?>/public/img/cover/<?= htmlspecialchars($book->getImage() ?: 'default.jpg') ?>"
                            alt="Livre <?= htmlspecialchars($book->getTitle()) ?>" class="book-image">
                        <?php if ($book->getStatus() === 0): ?>
                            <span class="not-available" aria-label="Livre non disponible">non dispo.</span>
                        <?php endif; ?>
                        <div class="card-content">
                            <h3 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h3>
                            <h4 class="book-subtitle"><?= htmlspecialchars($book->getAuthor()) ?></h4>
                            <p class="book-author">Ajouté par <?= htmlspecialchars($book->getPseudo()) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <a href="<?= ROOT ?>/book/availableBooks" class="btn-primary">Voir tous les livres</a>
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
        <a href="<?= ROOT ?>/book/availableBooks" class="btn-secondary">Voir tous les livres</a>
    </div>
</section>

<!-- Section library banner -->
<div class="library-banner">
    <div class="library-banner-container">
        <!-- Image de bannière gérée via CSS -->
    </div>
</div>

<!-- Section Our Values -->
<section class="our-values">
    <div class="values-content">
        <h2>Nos valeurs</h2>
        <p>Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs. Nous croyons en la puissance des histoires pour rassembler les gens et inspirer des conversations enrichissantes</p>
        <p>Notre association a été fondée avec une conviction profonde : chaque livre mérite d'être lu et partagé.</p>
        <p>Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment sur les étagères.</p>
        <p class="team-signature">L'équipe Tom Troc</p>
    </div>
    <img src="<?= ROOT ?>/public/img/vector_heart.svg" alt="Heart" class="heart-icon">
</section>
<?php
$content = ob_get_clean();
include('views/includes/template.php');
