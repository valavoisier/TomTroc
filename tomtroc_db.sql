-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 21 nov. 2025 à 22:58
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tomtroc_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

CREATE TABLE `books` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `user_id`, `title`, `author`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 'Esther', 'Alabaster', 'Un récit poétique et visuel qui explore la spiritualité et l\'art à travers des images modernes.', 'esther.jpg', 1, '2025-11-13 20:58:06', NULL),
(2, 2, 'The Kinfolk Table', 'Nathan Williams', 'J\'ai récemment plongé dans les pages de \'The Kinfolk Table\' et j\'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà d\'une simple collection de recettes ; il célèbre l\'art de partager des moments authentiques autour de la table. \r\n\r\nLes photographies magnifiques et le ton chaleureux captivent dès le départ, transportant le lecteur dans un voyage à travers des recettes et des histoires qui mettent en avant la beauté de la simplicité et de la convivialité. \r\n\r\nChaque page est une invitation à ralentir, à savourer et à créer des souvenirs durables avec les êtres chers. \r\n\r\n\'The Kinfolk Table\' incarne parfaitement l\'esprit de la cuisine et de la camaraderie, et il est certain que ce livre trouvera une place spéciale dans le cœur de tout amoureux de la cuisine et des rencontres inspirantes.', 'kinfolk_table.jpg', 1, '2025-11-13 20:58:06', '2025-11-15 12:03:32'),
(3, 1, 'Wabi Sabi', 'Beth Kempton', 'Un guide inspirant qui invite à apprécier la beauté de l\'imperfection et de la simplicité japonaise.', 'wabi_Sabi.jpg', 1, '2025-11-13 20:58:06', NULL),
(4, 5, 'Milk & Honey', 'Rupi Kaur', 'Recueil de poèmes intimes sur l\'amour, la douleur et la guérison, empreint de force et de douceur.', 'milk_honey.jpg', 1, '2025-11-13 20:58:06', NULL),
(5, 6, 'Delight!', 'Justin Rossow', 'Un livre qui met en avant la joie et la gratitude au quotidien, à travers réflexions et anecdotes.', 'delight.jpg', 0, '2025-11-13 20:58:06', NULL),
(6, 7, 'Milwaukee Mission', 'Milwaukee Mission', 'Un témoignage sur la vie communautaire et les initiatives locales à Milwaukee.', 'milwaukee_mission.jpg', 1, '2025-11-13 20:58:06', NULL),
(7, 8, 'Minimalist Graphics', 'Julia Schonlau', 'Un ouvrage artistique qui explore le graphisme minimaliste et ses formes épurées.', 'minimalist_graphics.jpg', 1, '2025-11-13 20:58:06', NULL),
(8, 5, 'Hygge', 'Meik Wiking', 'Un livre qui célèbre l\'art danois du bonheur et du confort, à travers rituels et atmosphères chaleureuses.', 'hygge.jpg', 1, '2025-11-13 20:58:06', NULL),
(9, 9, 'Innovation', 'Matt Ridley', 'Un essai sur l\'histoire et l\'impact des innovations qui transforment nos sociétés.', 'innovation.jpg', 1, '2025-11-13 20:58:06', NULL),
(10, 10, 'Psalms', 'Alabaster', 'Une édition artistique des Psaumes, mêlant textes bibliques et design contemporain.', 'psalms.jpg', 1, '2025-11-13 20:58:06', NULL),
(11, 3, 'Thinking, Fast & Slow', 'Daniel Kahneman', 'Un ouvrage majeur qui explique nos deux systèmes de pensée : rapide et intuitif, lent et réfléchi.', 'thinking_fast_&_slow.jpg', 0, '2025-11-13 20:58:06', NULL),
(12, 11, 'A Book Full Of Hope', 'Rupi Kaur', 'Un recueil poétique qui transmet des messages d\'espoir et de résilience.', 'a_book_full_of_hope.jpg', 1, '2025-11-13 20:58:06', NULL),
(13, 12, 'The Subtle Art Of...', 'Mark Manson', 'Un guide qui encourage à se concentrer sur l\'essentiel et à accepter les limites de la vie.', 'the_subtle_art_of.jpg', 1, '2025-11-13 20:58:06', NULL),
(14, 13, 'Narnia', 'C.S Lewis', 'Un classique de la fantasy où des enfants découvrent un monde magique et affrontent la Sorcière Blanche.', 'narnia.jpg', 0, '2025-11-13 20:58:06', NULL),
(15, 14, 'Company Of One', 'Paul Jarvis', 'Un essai qui valorise l\'indépendance et la réussite en restant une petite structure.', 'company_of_one.jpg', 1, '2025-11-13 20:58:06', NULL),
(16, 15, 'The Two Towers', 'J.R.R Tolkien', 'Le deuxième tome du Seigneur des Anneaux, où la Communauté se disperse et poursuit sa quête vers Mordor.', 'the_two_towers.jpg', 1, '2025-11-13 20:58:06', NULL),
(18, 18, 'Cheval qui es-tu?', 'Michel-Antoine Leblanc', 'Comprendre le comportement du cheval, quelle est sa vraie nature, comment il est devenu ce qu’il est aujourd’hui au fil des millénaires, quelles sont ses relations naturelles avec ses congénères, de quelles façons il interagit avec son milieu, comment l’individu se développe, quelles sont ses aptitudes, quelles conséquences cela peut avoir pour son bien-être…, autant de questions qui supposent des réponses claires, complètes et réellement documentées. C’est pour répondre à un tel intérêt, et afin de rendre accessible à un large public les connaissances scientifiques les plus récentes sur l’éthologie du cheval, que Michel-Antoine Leblanc, psychologue diplômé d’éthologie, a pris l’initiative de réaliser le présent ouvrage en collaboration avec Marie-France Bouissou, spécialiste du comportement du cheval, et Frédéric Chéhu, photographe.', '2025111911110512_1507-1.jpg', 1, '2025-11-13 20:58:06', '2025-11-19 11:05:12'),
(20, 18, 'True Unity', 'Tom Dorrance', 'Quand l\'homme et le cheval ne font plus qu\'un!\r\n\"Tout ce que je sais sur les chevaux, c\'est d\'eux que je l\'ai appris\", avait coutume de dire Tom Dorrance (1910-2003). Cet \'avocat du cheval\' est considéré aujourd\'hui comme le père de ceux que l\'on a plus tard surnommés les Chuchoteurs américains, ces cow-boys d\'un nouveau genre qui, bannissant toute violence, préfèrent persuader le cheval plutôt que le contraindre, faire de lui leur partenaire et non leur esclave. Tom Dorrance est même allé au-delà... Entré de son vivant dans la légende, cet homme d\'une grande humilité a prouvé qu\'il était possible d\'atteindre l\'idéal du Centaure accéder à la véritable unité, ne faire qu\'un avec le cheval. Ceux qui l\'ont côtoyé ont eu une chance rare. Loin de donner une simple leçon d\'équitation, c\'est toute une philosophie de vie qu\'il transmettait à ses élèves, parmi lesquels on compte de grands cavaliers tels que Ray Hunt, John Lyons, Pat Parelli, Martin Black... True Unity est l\'unique livre de Tom Dorrance. Un livre écrit avec toute la spontanéité et la sincérité d\'un homme qui au soir de sa vie poursuivait un but essentiel : partager et transmettre son savoir, pour que cavaliers et chevaux accèdent à la parfaite harmonie. Publié pour la première fois par Milly Hunt Porter en 1987 aux Etats-Unis, où il est devenu un classique, True Unity était inédit en français. Le voici traduit par Antoine Cloux, l\'un de ceux qui ont aujourd\'hui repris le flambeau et qui, s\'inscrivant dans le sillage de Tom Dorrance, contribuent à perpétuer son enseignement.', '2025111911111120_908d9dc7a23031b7f783c01ad1f9.jpg', 1, '2025-11-13 20:58:06', '2025-11-19 11:19:43'),
(21, 18, 'le chardon et le tartan', 'Diana Gabaldon', '1945. Claire passe ses vacances en Écosse, où elle s\'efforce d\'oublier la Seconde Guerre mondiale auprès de son mari, tout juste rentré du front. Au cours d\'une balade, la jeune femme est attirée par un mégalithe, auquel la population locale voue un culte étrange. Claire aura tôt fait d\'en découvrir la raison : en s\'approchant de la pierre, elle se volatilise pour atterrir au beau milieu d\'un champ de bataille. Le menhir l\'a menée tout droit en l\'an de grâce 1743, au cœur de la lutte opposant Highlanders et Anglais. Happée par ce monde inconnu et une nouvelle vie palpitante, saura-t-elle revenir à son existence d\'autrefois?Biographie de l\'auteurDiplômée d\'écologie et de biologie marine, Diana Gabaldon a enseigné pendant douze ans à l\'université d\'Arizona avant de se consacrer à la création romanesque. ', '2025111912123056_9782290142349-475x500-1.jpg', 1, '2025-11-13 20:58:06', '2025-11-19 12:30:56'),
(25, 18, 'Les 3 mousquetaires', 'Alexandre dumas', 'Duels, intrigues, panache, aventure… Replongez dans le souffle héroïque des Trois Mousquetaires, le grand classique de la littérature française, grâce à cette édition enrichie et somptueusement illustrée.\r\n\r\nDans une France du XVIIe siècle secouée par les complots et les rivalités de cour, le jeune et fougueux d’Artagnan rejoint trois mousquetaires d’élite — Athos, Portos et Aramis — pour défendre l’honneur, la justice… et le roi. Ensemble, ils affrontent trahisons, conspirations, et l’insaisissable Milady, dans une épopée de cape et d’épée devenue mythique.\r\n\r\nAlexandre Dumas, maître incontesté du roman-feuilleton, nous offre ici un chef-d’œuvre de rythme et de bravoure, peuplé de figures inoubliables où l’histoire se mêle à l’aventure avec éclat.', '2025111914142533_61dDCFEQqYL._SL1411_.jpg', 0, '2025-11-19 14:25:33', '2025-11-19 14:27:33');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `content`, `created_at`) VALUES
(1, 2, 1, 'Bonjour, peux-tu me dire si ton livre Wabi Sabi est en bon état ?', '2025-11-13 21:03:02'),
(2, 1, 2, 'Oui, il est en très bon état, presque neuf.', '2025-11-13 21:03:02'),
(3, 3, 5, 'Salut, est-ce que Milk & Honey est toujours disponible ?', '2025-11-13 21:03:02'),
(4, 5, 3, 'Oui, il est disponible. Tu veux qu’on organise un échange ?', '2025-11-13 21:03:02'),
(5, 6, 12, 'Bonjour, ton livre The Subtle Art Of... est-il complet et sans pages manquantes ?', '2025-11-13 21:03:02'),
(6, 12, 6, 'Oui, il est complet et en bon état.', '2025-11-13 21:03:02'),
(7, 1, 18, 'Bonjour, le livre est-il toujours disponible ?', '2025-11-21 15:43:00'),
(8, 18, 1, 'Oui, il est toujours disponible !', '2025-11-21 15:45:00'),
(9, 1, 18, 'Super, merci pour l\'échange !', '2025-11-21 15:46:00');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `avatar`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Alexlecture', 'alexlecture@gmail.com', 'Alexlecture.jpg', '$2y$10$54LW6w6cVM6Nu2in9qg86.mOy0FOVGErzOizkSbSqDukbNq2heVFi', '2023-11-13 20:50:21', '2025-11-18 20:03:46'),
(2, 'Nathalire', 'nathalire@gmail.com', 'Nathalire.jpg', 'nath123', '2025-11-13 20:50:21', NULL),
(3, 'Sas634', 'sas634@gmail.com', 'Sas634.jpg', 'sas123', '2025-11-13 20:50:21', NULL),
(4, 'CamilleClubLit', 'camilleclublit@gmail.com', 'user.png', 'camille123', '2025-11-13 20:51:50', '2025-11-16 14:27:10'),
(5, 'Hugo1990_12', 'hugo1990_12@gmail.com', 'user.png', 'hugo123', '2025-11-13 20:51:50', '2025-11-16 14:27:33'),
(6, 'Juju1432', 'juju1432@gmail.com', 'user.png', 'juju123', '2025-11-13 20:51:50', '2025-11-16 14:27:45'),
(7, 'Christiane75014', 'christiane75014@gmail.com', 'user.png', 'christiane123', '2025-11-13 20:51:50', '2025-11-16 14:27:55'),
(8, 'Hamzalecture', 'hamzalecture@gmail.com', 'user.png', 'hamza123', '2025-11-13 20:51:50', '2025-11-16 14:28:05'),
(9, 'Lou&Ben50', 'louben50@gmail.com', 'user.png', 'louben123', '2025-11-13 20:51:50', '2025-11-16 14:28:17'),
(10, 'Lolobzh', 'lolobzh@gmail.com', 'user.png', 'lolo123', '2025-11-13 20:51:50', '2025-11-16 14:28:34'),
(11, 'ML95', 'ml95@gmail.com', 'user.png', 'ml95123', '2025-11-13 20:51:50', '2025-11-16 14:28:43'),
(12, 'Verogo33', 'verogo33@gmail.com', 'user.png', 'verogo123', '2025-11-13 20:51:50', '2025-11-16 14:28:54'),
(13, 'AnnikaBrahms', 'annikabrahms@gmail.com', 'user.png', 'annika123', '2025-11-13 20:51:50', '2025-11-16 14:29:03'),
(14, 'Victoirefabr912', 'victoirefabr912@gmail.com', 'user.png', 'victoire123', '2025-11-13 20:51:50', '2025-11-16 14:29:10'),
(15, 'Lotrfanclub67', 'lotrfanclub67@gmail.com', 'user.png', 'lotr123', '2025-11-13 20:51:50', '2025-11-16 14:29:18'),
(16, 'Flitsou', 'Flitsou@gmail.com', NULL, '$2y$10$FvPa/wjpbaYbcSi8SquUTO.n/C3XlrymvEskS8/JuugeeUXERSTyy', '2025-11-17 13:40:05', NULL),
(18, 'Ruby', 'ruby@gmail.com', 'avatar_18_1763476366.jpg', '$2y$10$liZTd10RnKBYCI04dzMSg.JQhkvrfVdN.FD6sRW7gOxmIyzBMlLqu', '2025-11-17 15:05:59', '2025-11-18 15:32:46'),
(19, 'wobbe', 'wobbe@gmail.com', NULL, '$2y$10$2QzaZINdWKBnAwsH6ylsveaDxFXS36D9d5QVf8b7yDtSxbft2tsK6', '2025-11-17 22:23:01', NULL),
(20, 'Eelke', 'eelke@gmail.com', NULL, '$2y$10$KpZo5XeLnMHPMB3.z8/gX.7DM5EilaSJ/YPUGVhQeXQUe57hiMn4m', '2025-11-17 22:24:56', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
