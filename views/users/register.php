<?php
$pageTitle = "S'inscrire";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>

<section class="login">
    <div class="login-container">
        <div class="login-form-area">
            <?php if (!empty($message)): ?>
                <p class="error-message" role="alert"><?= $message ?></p>
            <?php endif; ?>
            <h1 class="login-title">Inscription</h1>
            <form class="login-form" method="POST" action="<?= ROOT ?>/user/register">
                <?= Utils::csrfTokenField() ?>
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" value="<?= htmlspecialchars($formData['pseudo'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Adresse email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-primary login-btn">S'inscrire</button>
                <p class="register-link">Déjà inscrit ? <a href="<?= ROOT ?>/user/login">Connectez-vous</a></p>
            </form>
        </div>
        <div class="login-image">
            <img src="<?= ROOT ?>/public/img/rayons_livres.jpg" alt="Rayons de livres">
        </div>
    </div>
</section>

<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();

// Inclusion du layout principal
include('views/includes/template.php');
