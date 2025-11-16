<?php
$pageTitle = "Tous les livres disponibles";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>
<section class="available-books">
    <div class="available-books-container">
        <div class="books-header">
            <h1>Nos livres à l'échange</h1>
            <form class="search-bar" action="<?= ROOT ?>/book/search" method="POST">
                <div class="search-container">
                    <img src="<?= ROOT ?>/public/img/loupe.svg" alt="Recherche" class="search-icon">
                    <input type="search" name="q" placeholder="Titre du livre..." class="search-input">
                </div>
            </form>
            <?php if (isset($message)): echo $message?><?php endif; ?>
        </div>
        <div class="books-grid">
            <!-- Card Livre  -->
            <?php foreach ($books as $book) : ?>               
                <a href="<?= ROOT ?>/book/singleBook/<?= $book['id'] ?>" class="card-livre-link">
                    <div class="card-livre">
                        <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book['image']) ?>" alt="Livre <?= htmlspecialchars($book['title']) ?>" class="book-image">
                        <div class="card-content">
                            <h3 class="book-title"><?= htmlspecialchars($book['title']) ?></h3>
                            <h4 class="book-subtitle"><?= htmlspecialchars($book['author']) ?></h4>
                            <p class="book-author">Vendu par: <?= htmlspecialchars($book['pseudo']) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
            <!-- Card Livre  fin -->
        </div>
    </div>
</section>

<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();

// Inclusion du layout principal
include('views/includes/template.php');
