-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 18 déc. 2025 à 18:42
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
(1, 15, 'The Two Towers', 'J.R.R Tolkien', 'Le deuxième tome du Seigneur des Anneaux, où la Communauté se disperse et poursuit sa quête vers Mordor.', 'the_two_towers.jpg', 1, '2025-11-13 20:58:06', '2025-12-17 22:56:35'),
(2, 14, 'Company Of One', 'Paul Jarvis', 'Un essai qui valorise l\'indépendance et la réussite en restant une petite structure.', 'company_of_one.jpg', 1, '2025-11-13 20:58:07', '2025-12-17 21:59:07'),
(3, 13, 'Narnia', 'C.S Lewis', 'Un classique de la fantasy où des enfants découvrent un monde magique et affrontent la Sorcière Blanche.', 'narnia.jpg', 0, '2025-11-13 20:58:08', '2025-12-17 22:56:35'),
(4, 12, 'The Subtle Art Of Not Giving A F*ck', 'Mark Manson', 'Un guide qui encourage à se concentrer sur l\'essentiel et à accepter les limites de la vie', 'the_subtle_art_of.jpg', 1, '2025-11-13 20:58:09', '2025-12-17 23:23:38'),
(5, 11, 'A Book Full Of Hope', 'Rupi Kaur', 'Un recueil poétique qui transmet des messages d\'espoir et de résilience.', 'a_book_full_of_hope.jpg', 1, '2025-11-13 20:58:10', '2025-12-17 22:56:35'),
(6, 3, 'Thinking, Fast & Slow', 'Daniel Kahneman', 'Un ouvrage majeur qui explique nos deux systèmes de pensée : rapide et intuitif, lent et réfléchi.', 'thinking_fast_&_slow.jpg', 0, '2025-11-13 20:58:11', '2025-12-17 22:56:35'),
(7, 10, 'Psalms', 'Alabaster', 'Une édition artistique des Psaumes, mêlant textes bibliques et design contemporain.', 'psalms.jpg', 1, '2025-11-13 20:58:12', '2025-12-17 22:56:35'),
(8, 9, 'Innovation', 'Matt Ridley', 'Un essai sur l\'histoire et l\'impact des innovations qui transforment nos sociétés.', 'innovation.jpg', 1, '2025-11-13 20:58:13', '2025-12-17 22:56:35'),
(9, 5, 'Hygge', 'Meik Wiking', 'Un livre qui célèbre l\'art danois du bonheur et du confort, à travers rituels et atmosphères chaleureuses.', 'hygge.jpg', 1, '2025-11-13 20:58:14', '2025-12-17 22:56:35'),
(10, 8, 'Minimalist Graphics', 'Julia Schonlau', 'Un ouvrage artistique qui explore le graphisme minimaliste et ses formes épurées.', 'minimalist_graphics.jpg', 1, '2025-11-13 20:58:15', '2025-12-17 22:56:35'),
(11, 7, 'Milwaukee Mission', 'Elder Cooper low', 'Un témoignage sur la vie communautaire et les initiatives locales à Milwaukee.', 'milwaukee_mission.jpg', 1, '2025-11-13 20:58:16', '2025-12-17 22:56:35'),
(12, 6, 'Delight!', 'Justin Rossow', 'Un livre qui met en avant la joie et la gratitude au quotidien, à travers réflexions et anecdotes.', 'delight.jpg', 0, '2025-11-13 20:58:17', '2025-12-18 18:39:57'),
(13, 5, 'Milk & Honey', 'Rupi Kaur', 'Recueil de poèmes intimes sur l\'amour, la douleur et la guérison, empreint de force et de douceur.', 'milk_honey.jpg', 1, '2025-11-13 20:58:18', '2025-12-17 22:56:35'),
(14, 1, 'Wabi Sabi', 'Beth Kempton', 'Un guide inspirant qui invite à apprécier la beauté de l\'imperfection et de la simplicité japonaise.', 'wabi_sabi.jpg', 1, '2025-11-13 20:58:19', '2025-12-18 14:55:14'),
(15, 2, 'The Kinfolk Table', 'Nathan Williams', 'J\'ai récemment plongé dans les pages de \'The Kinfolk Table\' et j\'ai été enchanté par cette œuvre captivante. Ce livre va bien au-delà d\'une simple collection de recettes ; il célèbre l\'art de partager des moments authentiques autour de la table.\r\n\r\nLes photographies magnifiques et le ton chaleureux captivent dès le départ, transportant le lecteur dans un voyage à travers des recettes et des histoires qui mettent en avant la beauté de la simplicité et de la convivialité.\r\n\r\nChaque page est une invitation à ralentir, à savourer et à créer des souvenirs durables avec les êtres chers.\r\n\r\n\'The Kinfolk Table\' incarne parfaitement l\'esprit de la cuisine et de la camaraderie, et il est certain que ce livre trouvera une place spéciale dans le cœur de tout amoureux de la cuisine et des rencontres inspirantes.', 'kinfolk_table.jpg', 1, '2025-11-13 20:58:20', '2025-12-17 22:56:35'),
(16, 4, 'Esther', 'Alabaster', 'Un récit poétique et visuel qui explore la spiritualité et l\'art à travers des images modernes.', 'esther.jpg', 1, '2025-11-13 20:58:21', '2025-12-18 18:19:11');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `receiver_id` int(10) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `content`, `is_read`, `created_at`) VALUES
(1, 2, 1, 'Bonjour, peux-tu me dire si ton livre Wabi Sabi est en bon état ?', 1, '2025-11-13 21:03:02'),
(2, 1, 2, 'Oui, il est en très bon état, presque neuf.', 1, '2025-11-13 21:03:02'),
(3, 3, 5, 'Salut, est-ce que Milk & Honey est toujours disponible ?', 1, '2025-11-13 21:03:02'),
(4, 5, 3, 'Oui, il est disponible. Tu veux qu’on organise un échange ?', 1, '2025-11-13 21:03:02'),
(5, 6, 12, 'Bonjour, ton livre The Subtle Art Of... est-il complet et sans pages manquantes ?', 1, '2025-11-13 21:03:02'),
(6, 12, 6, 'Oui, il est complet et en bon état.', 1, '2025-11-13 21:03:02'),
(31, 3, 1, 'j\'aimerai des renseignements sur wabi sabi', 1, '2025-11-28 16:37:50'),
(32, 3, 1, 'quel prix?', 1, '2025-11-28 16:38:29'),
(71, 1, 2, '5 euros', 0, '2025-12-18 14:05:36'),
(72, 1, 3, '7 euros avec frais d\'envoi', 1, '2025-12-18 15:57:27');

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
(2, 'Nathalire', 'nathalire@gmail.com', 'Nathalire.jpg', '$2a$12$fcn.KS5lub61Q7l.d759vup05fEBPHzCe9/bJnD6j59sDNDZi2ZMi', '2025-11-13 20:50:21', '2025-11-22 14:13:41'),
(3, 'Sas634', 'sas634@gmail.com', 'Sas634.jpg', '$2y$10$CUicGnzJuRP7R9d1jSNoxeCw/sK5Q7jy.I/MLNgHxjqOBgEADFhsW', '2025-11-13 20:50:21', '2025-11-28 16:28:27'),
(4, 'CamilleClubLit', 'camilleclublit@gmail.com', 'avatar_4_1765634790.jpg', '$2y$10$e8mFgryZRKBL2tMsYal.EuRrGvd6sfOr0Pb.WCmnfxFocPTwa6LkO', '2025-11-13 20:51:50', '2025-12-13 15:06:30'),
(5, 'Hugo1990_12', 'hugo1990_12@gmail.com', 'user.png', '$2y$10$lQzDC7F72ztnhD31t0Bo5ur9SmqCRqhjq58CSBRcQ8OaEQXPFtQ.C', '2025-11-13 20:51:50', '2025-12-13 12:15:21'),
(6, 'Juju1432', 'juju1432@gmail.com', 'user.png', '$2y$10$FdsoyiW8c5steZuuj7iEnObVit18e8cHxUHFyYOOOIzrpBpseHUki', '2025-11-13 20:51:50', '2025-12-13 12:16:28'),
(7, 'Christiane75014', 'christiane75014@gmail.com', 'user.png', '$2y$10$U2Kj9OtNYNnAdu2/cN9dhO3IfgB3Vd8ljxfmS/maYgPQlc8Bt.376', '2025-11-13 20:51:50', '2025-12-13 12:16:55'),
(8, 'Hamzalecture', 'hamzalecture@gmail.com', 'user.png', '$2y$10$md6.keFttIB3doKuK0vcwOVDLDCoku6AP7iUgapKFcW9aqhZ9JwRe', '2025-11-13 20:51:50', '2025-12-13 12:17:53'),
(9, 'Lou&Ben50', 'louben50@gmail.com', 'user.png', '$2y$10$oOkAq7AMtDPWYibLlAf5qe7ziwBDMkAHhk9v7lasOqNhFDzCCtJoS', '2025-11-13 20:51:50', '2025-12-13 12:18:42'),
(10, 'Lolobzh', 'lolobzh@gmail.com', 'user.png', '$2y$10$DZ1bvoA3sDw7PVe1y/K8POP/RlzwQCBm67VKVolWTWBrwG5OKqzFq', '2025-11-13 20:51:50', '2025-12-13 12:19:58'),
(11, 'ML95', 'ml95@gmail.com', 'user.png', '$2y$10$VWl/zBB.1ZxWWjWPtypyEOZdRXFYDzVZes/vKcl9qPlUU9Iz5gOie', '2025-11-13 20:51:50', '2025-12-13 12:21:31'),
(12, 'Verogo33', 'verogo33@gmail.com', 'user.png', '$2y$10$wBzDKtH54LFuBGiMJH21uuFd12nO.x.kXPxcWgIfefw3FDhQhB2ea', '2025-11-13 20:51:50', '2025-12-13 12:22:19'),
(13, 'AnnikaBrahms', 'annikabrahms@gmail.com', 'user.png', '$2y$10$Bpk1jOxZAY0VX4jXbvgH6uEXiWHM5wtHmtBdgCd3cZ/JdTMg2BB3u', '2025-11-13 20:51:50', '2025-12-13 12:23:05'),
(14, 'Victoirefabr912', 'victoirefabr912@gmail.com', 'user.png', '$2y$10$gjKBlfbiL7jPepNAvWAWy.o0zFrg.JVIS3O6jw4Ru7JxHkYWotmKe', '2025-11-13 20:51:50', '2025-12-13 12:23:39'),
(15, 'Lotrfanclub67', 'lotrfanclub67@gmail.com', 'user.png', '$2y$10$Lc67eAFYahQaGuTSRIcz6OJQ8ATZ8CxbAugLfcAQEo5IZd/yr01jO', '2025-11-13 20:51:50', '2025-12-13 12:25:19');

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
