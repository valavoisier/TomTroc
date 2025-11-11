 <!-- Header-->
    <body>
    <header>
        <div class="header-container">
            <!-- Section gauche : Logo + Menu principal -->
            <div class="header-left">
                <div class="logo">
                    <img src="<?=ROOT?>/public/img/logo.png" alt="Logo Tom Troc">
                </div>
                <nav class="nav-left">
                    <a href="<?=ROOT?>" class="active">Accueil</a>
                    <a href="available-books.html">Nos livres à l'échange</a>
                </nav>
            </div>

            <!-- Section droite : Menu utilisateur -->
            <nav class="nav-right">
                <a href="#" class="nav-link account-link">
                    <img src="<?=ROOT?>/public/img/messagerie.svg" alt="Messagerie" class="nav-icon">
                    Messagerie
                    <span class="message-counter">1</span>
                </a>
                <a href="account.html" class="nav-link">
                    <img src="<?=ROOT?>/public/img/compte.svg" alt="Compte" class="nav-icon">
                    Mon compte
                </a>
                <a href="login.html" class="nav-link connexion">Connexion</a>
            </nav>
        </div>
    </header>
    
    <main>
        