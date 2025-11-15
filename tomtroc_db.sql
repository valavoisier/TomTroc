-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 15 nov. 2025 à 13:08
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
(16, 15, 'The Two Towers', 'J.R.R Tolkien', 'Le deuxième tome du Seigneur des Anneaux, où la Communauté se disperse et poursuit sa quête vers Mordor.', 'the_two_towers.jpg', 1, '2025-11-13 20:58:06', NULL);

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
(6, 12, 6, 'Oui, il est complet et en bon état.', '2025-11-13 21:03:02');

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
(1, 'Alexlecture', 'alexlecture@gmail.com', 'Alexlecture.jpg', 'alex123', '2025-11-13 20:50:21', NULL),
(2, 'Nathalire', 'nathalire@gmail.com', 'Nathalire.jpg', 'nath123', '2025-11-13 20:50:21', NULL),
(3, 'Sas634', 'sas634@gmail.com', 'Sas634.jpg', 'sas123', '2025-11-13 20:50:21', NULL),
(4, 'CamilleClubLit', 'camilleclublit@gmail.com', 'CamilleClubLit.jpg', 'camille123', '2025-11-13 20:51:50', NULL),
(5, 'Hugo1990_12', 'hugo1990_12@gmail.com', 'Hugo1990_12.jpg', 'hugo123', '2025-11-13 20:51:50', NULL),
(6, 'Juju1432', 'juju1432@gmail.com', 'Juju1432.jpg', 'juju123', '2025-11-13 20:51:50', NULL),
(7, 'Christiane75014', 'christiane75014@gmail.com', 'Christiane75014.jpg', 'christiane123', '2025-11-13 20:51:50', NULL),
(8, 'Hamzalecture', 'hamzalecture@gmail.com', 'Hamzalecture.jpg', 'hamza123', '2025-11-13 20:51:50', NULL),
(9, 'Lou&Ben50', 'louben50@gmail.com', 'Lou&Ben50.jpg', 'louben123', '2025-11-13 20:51:50', NULL),
(10, 'Lolobzh', 'lolobzh@gmail.com', 'Lolobzh.jpg', 'lolo123', '2025-11-13 20:51:50', NULL),
(11, 'ML95', 'ml95@gmail.com', 'ML95.jpg', 'ml95123', '2025-11-13 20:51:50', NULL),
(12, 'Verogo33', 'verogo33@gmail.com', 'Verogo33.jpg', 'verogo123', '2025-11-13 20:51:50', NULL),
(13, 'AnnikaBrahms', 'annikabrahms@gmail.com', 'AnnikaBrahms.jpg', 'annika123', '2025-11-13 20:51:50', NULL),
(14, 'Victoirefabr912', 'victoirefabr912@gmail.com', 'Victoirefabr912.jpg', 'victoire123', '2025-11-13 20:51:50', NULL),
(15, 'Lotrfanclub67', 'lotrfanclub67@gmail.com', 'Lotrfanclub67.jpg', 'lotr123', '2025-11-13 20:51:50', NULL);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
