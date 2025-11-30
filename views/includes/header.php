 <!-- Header-->
<?php
// La variable $conversationsCount est passée automatiquement par AbstractController::render()
// Valeur par défaut si non définie (cas des pages d'erreur)
$conversationsCount = $conversationsCount ?? 0;
?>

 <body>
     <header>
         <div class="header-container">
             <!-- Section gauche : Logo + Menu principal -->
             <div class="header-left">
                 <div class="logo">
                     <img src="<?= ROOT ?>/public/img/logo.png" alt="Logo Tom Troc">
                 </div>
                <nav class="nav-left" aria-label="Main navigation">
                    <a href="<?= ROOT ?>" class="active" aria-current="page">Accueil</a>
                    <a href="<?= ROOT ?>/book/availableBooks">Nos livres à l'échange</a>
                </nav>
             </div>

             <!-- Section droite : Menu utilisateur -->
             <nav class="nav-right" aria-label="Secondary navigation">
                <a href="<?= ROOT ?>/message" class="nav-link account-link" aria-label="Messagerie<?php echo (isset($_SESSION['user']) && $conversationsCount > 0) ? ', ' . $conversationsCount . ' message' . ($conversationsCount > 1 ? 's' : '') . ' non lu' . ($conversationsCount > 1 ? 's' : '') : ''; ?>">
                    <img src="<?= ROOT ?>/public/img/messagerie.svg" alt="" class="nav-icon" aria-hidden="true">
                    Messagerie
                    <?php if (isset($_SESSION['user'])): ?>
                        <span class="message-counter" aria-hidden="true"><?= $conversationsCount ?></span>
                    <?php endif; ?>
                 </a>
                <a href="<?= ROOT ?>/user" class="nav-link">
                    <img src="<?= ROOT ?>/public/img/compte.svg" alt="" class="nav-icon" aria-hidden="true">
                    Mon compte
                </a>
                 <?php if (isset($_SESSION['user'])): ?>
                     <!-- Si connecté : afficher Déconnexion -->
                     <a href="<?= ROOT ?>/user/logout" class="nav-link deconnexion">Déconnexion</a>
                 <?php else: ?>
                     <!-- Si non connecté : afficher Connexion -->
                     <a href="<?= ROOT ?>/user/login" class="nav-link connexion">Connexion</a>
                 <?php endif; ?>
             </nav>
         </div>
     </header>

     <main>