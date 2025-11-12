<?php
$pageTitle = "Tous les livres disponibles";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>
<section class="available-books">
    <div class="available-books-container">
        <div class="books-header">
            <h1>Nos livres à l'échange</h1>
            <form class="search-bar">
                <div class="search-container">
                    <img src="<?= ROOT ?>/public/img/loupe.svg" alt="Recherche" class="search-icon">
                    <input type="search" placeholder="Rechercher un livre" class="search-input">
                </div>
            </form>
        </div>
        <div class="books-grid">
            <!-- Card Livre 1 -->
            <a href="<?= ROOT ?>/book/singleBook" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/esther.jpg" alt="Livre Esther" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">Esther</h3>
                        <h4 class="book-subtitle">Alabaster</h4>
                        <p class="book-author">Vendu par: CamilleClubLit</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 2 -->
            <a href="<?= ROOT ?>/book/singleBook" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/kinfolk_table.jpg" alt="Livre The Kinfolk Table" class="book-image">
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
                    <img src="<?= ROOT ?>/public/img/wabi_Sabi.jpg" alt="Livre Wabi Sabi" class="book-image">
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
                    <img src="<?= ROOT ?>/public/img/milk_honey.jpg" alt="Livre Milk & Honey" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">Milk & Honey</h3>
                        <h4 class="book-subtitle">Rupi Kaur</h4>
                        <p class="book-author">Vendu par: Hugo1990_12</p>
                    </div>
                </div>
            </a>

            <!-- Ligne 2 -->
            <!-- Card Livre 5 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/delight.jpg" alt="Livre Delight!" class="book-image">
                    <span class="not-available">non dispo.</span>
                    <div class="card-content">
                        <h3 class="book-title">Delight!</h3>
                        <h4 class="book-subtitle">Justin Rossow</h4>
                        <p class="book-author">Vendu par: Juju1432</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 6 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/milwaukee_mission.jpg" alt="Livre Milwaukee Mission" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">Milwaukee Mission</h3>
                        <h4 class="book-subtitle">Milwaukee Mission</h4>
                        <p class="book-author">Vendu par: Christiane75014</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 7 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/minimalist_graphics.jpg" alt="Livre Minimalist Graphics" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">Minimalist Graphics</h3>
                        <h4 class="book-subtitle">Julia Schonlau</h4>
                        <p class="book-author">Vendu par: Hamzalecture</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 8 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/hygge.jpg" alt="Livre Hygge" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">Hygge</h3>
                        <h4 class="book-subtitle">Meik Wiking</h4>
                        <p class="book-author">Vendu par: Hugo1990_12</p>
                    </div>
                </div>
            </a>

            <!-- Ligne 3 -->
            <!-- Card Livre 9 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/innovation.jpg" alt="Livre Innovation" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">Innovation</h3>
                        <h4 class="book-subtitle">Matt Ridley</h4>
                        <p class="book-author">Vendu par: Lou&Ben50</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 10 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/psalms.jpg" alt="Livre Psalms" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">Psalms</h3>
                        <h4 class="book-subtitle">Alabaster</h4>
                        <p class="book-author">Vendu par: Lolobzh</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 11 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/thinking_fast_&_slow.jpg" alt="Livre Thinking, Fast & Slow" class="book-image">
                    <span class="not-available">non dispo.</span>
                    <div class="card-content">
                        <h3 class="book-title">Thinking, Fast & Slow</h3>
                        <h4 class="book-subtitle">Daniel Kahneman</h4>
                        <p class="book-author">Vendu par: Sas634</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 12 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/a_book_full_of_hope.jpg" alt="Livre A Book Full Of Hope" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">A Book Full Of Hope</h3>
                        <h4 class="book-subtitle">Rupi Kaur</h4>
                        <p class="book-author">Vendu par: ML95</p>
                    </div>
                </div>
            </a>

            <!-- Ligne 4 -->
            <!-- Card Livre 13 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/the_subtle_art_of.jpg" alt="Livre The Subtle Art Of..." class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">The Subtle Art Of...</h3>
                        <h4 class="book-subtitle">Mark Manson</h4>
                        <p class="book-author">Vendu par: Verogo33</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 14 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/narnia.jpg" alt="Livre Narnia" class="book-image">
                    <span class="not-available">non dispo.</span>
                    <div class="card-content">
                        <h3 class="book-title">Narnia</h3>
                        <h4 class="book-subtitle">C.S Lewis</h4>
                        <p class="book-author">Vendu par: AnnikaBrahms</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 15 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/company_of_one.jpg" alt="Livre Company Of One" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">Company Of One</h3>
                        <h4 class="book-subtitle">Paul Jarvis</h4>
                        <p class="book-author">Vendu par: Victoirefabr912</p>
                    </div>
                </div>
            </a>

            <!-- Card Livre 16 -->
            <a href="single-book.html" class="card-livre-link">
                <div class="card-livre">
                    <img src="<?= ROOT ?>/public/img/the_two_towers.jpg" alt="Livre The Two Towers" class="book-image">
                    <div class="card-content">
                        <h3 class="book-title">The Two Towers</h3>
                        <h4 class="book-subtitle">J.R.R Tolkien</h4>
                        <p class="book-author">Vendu par: Lotrfanclub67</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</section>

<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();

// Inclusion du layout principal
include('views/includes/template.php');
