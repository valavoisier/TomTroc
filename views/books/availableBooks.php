<?php
$pageTitle = "Tous les livres disponibles";
ob_start(); ?>
<section class="available-books">
    <div class="available-books-container">
        <div class="books-header">
            <h1>Nos livres à l'échange</h1>
            <form class="search-bar" action="<?= ROOT ?>/book/search" method="POST" role="search" aria-label="Rechercher un livre">
                <?= Utils::csrfTokenField() ?>
                <div class="search-container">
                    <img src="<?= ROOT ?>/public/img/loupe.svg" alt="" class="search-icon" aria-hidden="true">
                    <input type="search" name="q" placeholder="Rechercher un livre..." class="search-input">
                </div>
            </form>
            <?php if (isset($message)): ?>
                <div class="alert alert-error" role="alert"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
        </div>
        <div class="books-grid">
            <?php foreach ($books as $book) : ?>               
                <a href="<?= ROOT ?>/book/singleBook/<?= $book->getId() ?>" class="card-livre-link">
                    <div class="card-livre">
                        <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($book->getImage()) ?>" 
                             alt="Livre <?= htmlspecialchars($book->getTitle()) ?>" class="book-image">
                        <?php if ($book->getStatus() === 0): ?>
                            <span class="not-available" aria-label="Livre non disponible">non dispo.</span>
                        <?php endif; ?>
                        <div class="card-content">
                            <p class="book-title"><?= htmlspecialchars($book->getTitle()) ?></p>
                            <p class="book-subtitle"><?= htmlspecialchars($book->getAuthor()) ?></p>
                            <p class="book-author">Vendu par : <?= htmlspecialchars($book->getPseudo()) ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php
$content = ob_get_clean();
include('views/includes/template.php');

