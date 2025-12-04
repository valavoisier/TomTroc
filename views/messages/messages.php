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
                <?php if (!empty($conversations)): ?>
                    <?php foreach ($conversations as $index => $conversation): ?>
                        <a href="<?= ROOT ?>/message/conversation/<?= $conversation->getUserId() ?>" class="conversation-link">
                            <div class="conversation-item <?= ($index === 0 && !isset($selectedConversation))
                                                                || (isset($selectedConversation) && $selectedConversation->getId() == $conversation->getUserId()) ? 'first' : '' ?>">
                                <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($conversation->getAvatarPath()) ?>" alt="Avatar" class="conversation-avatar">
                                <div class="conversation-content">
                                    <div class="conversation-header">
                                        <span class="conversation-name"><?= htmlspecialchars($conversation->getPseudo()) ?></span>
                                        <span class="conversation-date">
                                            <?php
                                            if ($conversation->getLastMessageDate()) {
                                                $messageDate = strtotime($conversation->getLastMessageDate());
                                                $today = strtotime('today');
                                                echo $messageDate >= $today ? date('H:i', $messageDate) : date('d.m', $messageDate);
                                            }
                                            ?>
                                        </span>

                                    </div>
                                    <p class="conversation-preview"><?= htmlspecialchars($conversation->getPreview()) ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-conversations">Aucune conversation pour le moment.</p>
                <?php endif; ?>
            </div>
        </aside>
        <!-- Zone de conversation -->
        <div class="messages-main">
            <?php if (isset($selectedConversation) && !empty($selectedConversation)): ?>
                <!-- Header de la conversation -->
                <div class="messages-header">
                    <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($selectedConversation->getAvatar() ?? 'user.png', ENT_NOQUOTES, 'UTF-8') ?>" alt="Avatar" class="messages-header-avatar">
                    <span class="messages-header-name"><?= htmlspecialchars($selectedConversation->getPseudo(), ENT_NOQUOTES, 'UTF-8') ?></span>
                </div>
                <!-- Contenu de la conversation sélectionnée -->
                <div class="messages-content">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $message): ?>
                            <?php
                            $isSender = ($message->getSenderId() != $_SESSION['user']['id']);
                            $messageClass = $isSender ? 'message-sender' : 'message-receiver';
                            ?>
                            <?php if ($isSender): ?>
                                <!-- Message du sender (gauche) -->
                                <div class="message-item <?= $messageClass ?>">
                                    <div class="message-header">
                                        <img src="<?= ROOT ?>/public/img/<?= htmlspecialchars($message->getSenderAvatar(), ENT_NOQUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($message->getSenderPseudo(), ENT_NOQUOTES, 'UTF-8') ?>" class="message-avatar">
                                        <span class="message-time"><?= date('d.m H:i', strtotime($message->getCreatedAt())) ?></span>
                                    </div>
                                    <div class="message-bubble message-bubble-sender">
                                        <?= nl2br(htmlspecialchars($message->getContent(), ENT_NOQUOTES, 'UTF-8')) ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Message du receiver (droite) -->
                                <div class="message-item <?= $messageClass ?>">
                                    <div class="message-header-receiver">
                                        <span class="message-time"><?= date('d.m H:i', strtotime($message->getCreatedAt())) ?></span>
                                    </div>
                                    <div class="message-bubble message-bubble-receiver">
                                        <?= nl2br(htmlspecialchars($message->getContent(), ENT_NOQUOTES, 'UTF-8')) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-messages">Démarrez la conversation en envoyant un message.</p>
                    <?php endif; ?>
                </div>
                <!-- Formulaire d'envoi de réponse au message reçu -->
                <form class="messages-form" method="POST" action="<?= ROOT ?>/message/send">
                    <input type="hidden" name="receiver_id" value="<?= $selectedConversation->getId() ?>">
                    <input type="text" name="content" class="messages-input" placeholder="Tapez votre message ici" required>
                    <button type="submit" class="messages-submit">Envoyer</button>
                </form>
            <?php else: ?>
                <div class="no-conversation-selected">
                    <p>Sélectionnez une conversation pour commencer à échanger.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php
// Récupération du contenu mis en tampon
$content = ob_get_clean();
// Inclusion du layout principal
include('views/includes/template.php');
