<?php
$pageTitle = "Messagerie";

// Démarrage de la mise en tampon de sortie
ob_start(); ?>

   <section class="messages-section">
            <div class="messages-container">
                <!-- Colonne de gauche : Liste des conversations -->
                <aside class="messages-sidebar">
                    <h1 class="messages-title">Messagerie</h1>

                    <div class="conversation-list">
                        <!-- Conversation 1 -->
                         <div class="conversation-item first">
                            <img src="<?= ROOT ?>/public/img/alexlecture.jpg" alt="Avatar" class="conversation-avatar">
                            <div class="conversation-content">
                                <div class="conversation-header">
                                    <span class="conversation-name">Alexlecture</span>
                                    <span class="conversation-date">15:43</span>
                                </div>
                                <p class="conversation-preview">Super, merci pour l'échange !</p>
                            </div>
                        </div>
                        <!-- Conversation 2 -->
                        <div class="conversation-item">
                            <img src="<?= ROOT ?>/public/img/nathalire.jpg" alt="Avatar" class="conversation-avatar">
                            <div class="conversation-content">
                                <div class="conversation-header">
                                    <span class="conversation-name">Nathalire</span>
                                    <span class="conversation-date">20.08</span>
                                </div>
                                <p class="conversation-preview">Bonjour, le livre est-il en bon état?</p>
                            </div>
                        </div>


                        <!-- Conversation 3 -->
                        <div class="conversation-item">
                            <img src="<?= ROOT ?>/public/img/sas634.jpg" alt="Avatar" class="conversation-avatar">
                            <div class="conversation-content">
                                <div class="conversation-header">
                                    <span class="conversation-name">Sas634</span>
                                    <span class="conversation-date">15.08</span>
                                </div>
                                <p class="conversation-preview">Merci pour les renseignements...</p>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Zone de conversation (à compléter) -->
                <div class="messages-main">
                    <!-- Header de la conversation -->
                    <div class="messages-header">
                        <img src="<?= ROOT ?>/public/img/alexlecture.jpg" alt="Avatar Alexlecture" class="messages-header-avatar">
                        <span class="messages-header-name">Alexlecture</span>
                    </div>
                    <!-- Contenu de la conversation sélectionnée -->
                    <div class="messages-content">
                        <!-- Message du sender (gauche) -->
                        <div class="message-item message-sender">
                            <div class="message-header">
                                <img src="<?= ROOT ?>/public/img/alexlecture.jpg" alt="Avatar" class="message-avatar">
                                <span class="message-time">21.11 15:43 </span>
                            </div>
                            <div class="message-bubble message-bubble-sender">
                                Bonjour, le livre est-il toujours disponible ?
                            </div>
                        </div>

                        <!-- Message du receiver (droite) -->
                        <div class="message-item message-receiver">
                            <div class="message-header-receiver">
                                <span class="message-time">21.11 15:45</span>
                            </div>
                            <div class="message-bubble message-bubble-receiver">
                                Oui, il est toujours disponible !
                            </div>
                        </div>

                        <!-- Message du sender (gauche) -->
                        <div class="message-item message-sender">
                            <div class="message-header">
                                <img src="<?= ROOT ?>/public/img/alexlecture.jpg" alt="Avatar" class="message-avatar">
                                <span class="message-time">21.11 15:46</span>
                            </div>
                            <div class="message-bubble message-bubble-sender">
                                Super, merci pour l'échange !
                            </div>
                        </div>
                    </div>
                    <!-- Formulaire d'envoi de message -->
                    <form class="messages-form">
                        <input type="text" class="messages-input" placeholder="Tapez votre message ici">
                        <button type="submit" class="messages-submit">Envoyer</button>
                    </form>
                </div>
            </div>
        </section>

<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();

// Inclusion du layout principal
include('views/includes/template.php');