# TomTroc - Plateforme d'Échange de Livres

## Description

**TomTroc** est une application web de partage et d'échange de livres entre particuliers. Les utilisateurs peuvent créer un compte, publier leurs livres disponibles, consulter les bibliothèques d'autres membres, et communiquer via une messagerie intégrée pour organiser leurs échanges.

## Fonctionnalités

-  **Authentification sécurisée** : Inscription, connexion avec hachage Bcrypt
-  **Gestion de bibliothèque** : Ajout, modification, suppression de livres
-  **Messagerie privée** : Conversations en temps réel avec compteur de messages non lus
-  **Profils utilisateurs** : Profils publics/privés avec avatar personnalisable
-  **Sécurité renforcée** : Protection CSRF, XSS, SQL Injection, validation serveur
-  **Accessibilité** : Conformité WCAG 2.1 (labels, ARIA, HTML sémantique)

##  Technologies

- **Backend** : PHP 8.2x (POO strict, typage fort)
- **Base de données** : MariaDB 10.4+ avec PDO
- **Architecture** : MVC personnalisé avec Router et Autoloader
- **Frontend** : HTML5, CSS3 
- **Patterns** : Singleton (Database), Repository/Manager (couche d'accès aux données), DTO (BookWithOwnerDTO)

##  Prérequis

- PHP 8.2 ou supérieur
- MariaDB 10.4 ou supérieur (ou MySQL 8.0+)
- Serveur web (Apache/Nginx) avec mod_rewrite activé

##  Installation

### 1. Cloner le projet

```bash
git clone https://github.com/votre-nom/TomTroc.git
cd TomTroc
```

### 2. Créer la base de données

Créez une base de données MySQL nommée **`tomtroc_db`** :

```sql
CREATE DATABASE tomtroc_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Importer le schéma SQL

Importez le fichier SQL fourni pour créer les tables :

```bash
mysql -u votre_utilisateur -p tomtroc_db < tomtroc_db.sql
```

Ou via phpMyAdmin :
1. Ouvrez phpMyAdmin
2. Sélectionnez la base `tomtroc_db`
3. Cliquez sur "Importer"
4. Sélectionnez le fichier `tomtroc_db.sql`
5. Cliquez sur "Exécuter"

### 4. Configurer les paramètres de connexion

Modifiez le fichier **`core/config.php`** avec vos propres paramètres de connexion :

```php
<?php
// Paramètres de connexion à la base de données
define('DB_HOST', 'localhost');        // Hôte de la base de données
define('DB_NAME', 'tomtroc_db');       // Nom de la base de données
define('DB_USER', 'votre_utilisateur'); // Votre nom d'utilisateur MySQL
define('DB_PASS', 'votre_mot_de_passe'); // Votre mot de passe MySQL

// Constante ROOT pour les chemins
define('ROOT', '/TomTroc');  // Ajustez selon votre dossier d'installation
```

### 5. Configurer le serveur web

#### Avec XAMPP/WAMP :
- Placez le projet dans `htdocs/` ou `www/`
- Accédez à `http://localhost/TomTroc`

#### Avec Apache :
Assurez-vous que `mod_rewrite` est activé et que le fichier `.htaccess` est bien présent à la racine :

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

## 📁 Structure du Projet

```
TomTroc/
├── controllers/          # Contrôleurs MVC
│   ├── AbstractController.php
│   ├── BookController.php
│   ├── HomeController.php
│   ├── MessageController.php
│   └── UserController.php
├── core/                 # Configuration et infrastructure
│   ├── config.php        # Paramètres BDD et constantes
│   ├── Database.php      # Singleton PDO
│   └── Router.php        # Routeur personnalisé
├── models/               # Modèles (Managers + Entités)
│   ├── AbstractManager.php
│   ├── BookManager.php
│   ├── BookWithOwnerDTO.php
│   ├── MessageManager.php
│   ├── UserManager.php
│   ├── Book.php
│   ├── Message.php
│   ├── Conversation.php
│   └── User.php
├── services/             # Services utilitaires
│   └── Utils.php         # Fonctions CSRF, etc.
├── views/                # Vues (templates HTML)
│   ├── books/
│   ├── messages/
│   ├── users/
│   └── includes/
├── public/               # Ressources statiques
│   ├── css/
│   └── img/
├── Autoload.php          # Autoloader SPL
├── index.php             # Point d'entrée unique
├── .htaccess             # Configuration Apache
└── README.md             # Ce fichier
```

##  Utilisation

### Créer un compte
1. Accédez à `/user/register`
2. Remplissez le formulaire (pseudo, email, mot de passe)
3. Votre mot de passe doit contenir au moins 6 caractères, une majuscule, un chiffre et un caractère spécial

### Se connecter
1. Accédez à `/user/login`
2. Saisissez vos identifiants

### Ajouter un livre
1. Connectez-vous à votre compte
2. Accédez à votre profil
3. Cliquez sur "Ajouter un livre"
4. Remplissez les informations (titre, auteur, description, image optionnelle)

### Envoyer un message
1. Consultez le profil d'un utilisateur via détail d'un livre
2. Cliquez sur "Envoyer un message"
3. Rédigez votre message et envoyez

##  Sécurité

- **Protection CSRF** : Tokens aléatoires 64 caractères sur tous les formulaires
- **Hachage Bcrypt** : Mots de passe hachés avec `password_hash()`
- **Requêtes préparées** : PDO avec `bindValue()` sur toutes les requêtes
- **Échappement XSS** : `htmlspecialchars()` sur toutes les sorties utilisateur
- **Sessions sécurisées** : Régénération ID après connexion
- **Validation serveur** : Vérification de toutes les données côté backend

##  Tests

### Tests Manuels Recommandés
-  Inscription/Connexion avec données valides/invalides
-  Ajout/Modification/Suppression de livres
-  Envoi de messages entre utilisateurs
-  Navigation au clavier (accessibilité)


### Tests Accessibilité
- **Lighthouse** (Chrome DevTools) : Score 80+ recommandé
- **WAVE Extension** : 0 erreurs WCAG niveau A
- **NVDA** : Navigation complète au lecteur d'écran

## Licence

Ce projet est développé dans le cadre d'une formation. Tous droits réservés.

## 👤 Auteur

Valérie lavoisier - Formation Développeur WebD'application PHP Symfony

## Support

Pour toute question ou problème :
1. Vérifiez les paramètres de `config.php`
2. Assurez-vous que la base de données est correctement importée
3. Consultez les logs d'erreur PHP (`error_log`)

---

**Version** : 1.0.0  
**Date de mise à jour** : Décembre 2025
