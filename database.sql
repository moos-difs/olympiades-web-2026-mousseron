-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 17 oct. 2026 à 10:17
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
-- Base de données : `olympiades`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin_logs_attentes`
--

CREATE TABLE `admin_logs_attentes` (
  `id` int(11) NOT NULL,
  `attraction_id` int(11) NOT NULL,
  `ancien_temps` int(11) DEFAULT NULL,
  `nouveau_temps` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `date_modification` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin_logs_attentes`
--

INSERT INTO `admin_logs_attentes` (`id`, `attraction_id`, `ancien_temps`, `nouveau_temps`, `admin_id`, `date_modification`) VALUES
(1, 1, 15, 55, 1, '2026-10-16 17:51:55');

-- --------------------------------------------------------

--
-- Structure de la table `attractions`
--

CREATE TABLE `attractions` (
  `id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `gag` text NOT NULL,
  `caracteristiques` text NOT NULL,
  `taille_minimale` int(11) NOT NULL,
  `statut` enum('Ouvert','Ferme','Maintenance') DEFAULT 'Ouvert',
  `univers_id` int(11) NOT NULL,
  `attente` int(11) DEFAULT NULL,
  `coord_x` decimal(5,2) DEFAULT 50.00,
  `coord_y` decimal(5,2) DEFAULT 50.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `attractions`
--

INSERT INTO `attractions` (`id`, `nom`, `image`, `description`, `gag`, `caracteristiques`, `taille_minimale`, `statut`, `univers_id`, `attente`, `coord_x`, `coord_y`) VALUES
(1, 'La Falaise', 'falaise.png', 'Une tour de chute thématisée en gigantesque falaise en trompe-l\'œil style carton-pâte, surmontée d\'un faux panneau signalétique « Attention, vide ».', 'Le personnage court dans le vide, ne tombe pas immédiatement, regarde en bas, puis réalise sa chute.', 'Hauteur : 65 m | Vitesse : ~100 km/h | Durée : 2 min', 120, 'Ouvert', 1, 15, 50.00, 50.00),
(2, 'Le Trou Portable', 'trou_portable.png', 'Un parcours sombre familial à bord de véhicules de 4 places qui traversent des salles aux styles graphiques changeants (du simple crayonné aux aplats de couleurs saturées).', 'Un simple trou peint sur un mur que l\'\'on traverse comme s\'\'il s\'\'agissait d\'\'un véritable passage secret.', 'Durée : 6 min | Capacité : 1 200 pers/h | Accessible dès 0,90m (accompagné)', 90, 'Ouvert', 1, 10, 50.00, 50.00),
(3, 'Le Canon de Cirque', 'canon_cirque.png', 'Des montagnes russes à lancement dont la gare est thématisée en chapiteau de cirque avec une bouche de canon béante.', 'Le train est propulsé hors d\'un canon géant, tout droit sorti d\'un numéro de cirque burlesque.', 'Lancement : 0 à 110 km/h en 3s | Bosse : 45 m | Longueur : 700 m | Durée : 1 min 10', 130, 'Ouvert', 2, 25, 50.00, 50.00),
(4, 'L’Enclume Express', 'enclume_express.png', 'Un parcours décoiffant à travers l\'atelier des armes loufoques du studio.', 'Une énorme enclume tombe du ciel pile au moment du départ.', 'Vitesse : 80 km/h | Durée : 1 min 30', 120, 'Maintenance', 2, NULL, 50.00, 50.00);

-- --------------------------------------------------------

--
-- Structure de la table `e_billets`
--

CREATE TABLE `e_billets` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `tarif_id` int(11) NOT NULL,
  `code_unique` varchar(50) NOT NULL,
  `est_utilise` tinyint(1) DEFAULT 0,
  `date_utilisation` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `e_billets`
--

INSERT INTO `e_billets` (`id`, `reservation_id`, `tarif_id`, `code_unique`, `est_utilise`, `date_utilisation`) VALUES
(10, 9, 1, '1C02DF39', 1, '2026-10-15 15:57:44'),
(11, 9, 1, 'DF7CDFF4', 0, NULL),
(12, 9, 2, 'A721FFC9', 0, NULL),
(13, 9, 2, 'A681FC0B', 0, NULL),
(14, 9, 2, '53818134', 0, NULL),
(15, 9, 2, '5F80F98F', 0, NULL),
(16, 9, 2, '6391B82E', 0, NULL),
(17, 9, 2, '9F7373E7', 0, NULL),
(18, 9, 3, '44EFE434', 0, NULL),
(19, 10, 1, '714EF8DC', 0, NULL),
(20, 10, 1, 'D43C579A', 0, NULL),
(21, 11, 3, 'B90556FB', 0, NULL),
(22, 12, 1, '350AFFDC', 0, NULL),
(23, 12, 1, '4FCC6639', 0, NULL),
(24, 12, 1, '25DF8E2E', 0, NULL),
(25, 12, 1, '9C089C0E', 0, NULL),
(26, 12, 1, '9B3F25BE', 0, NULL),
(27, 12, 1, 'B69235FC', 0, NULL),
(28, 12, 1, '45BAED46', 0, NULL),
(29, 12, 1, '8D89FEE3', 0, NULL),
(30, 12, 1, '3E646872', 0, NULL),
(31, 12, 1, '8AC6E857', 0, NULL),
(32, 12, 2, '188890F3', 0, NULL),
(33, 12, 2, 'E570CADA', 0, NULL),
(34, 12, 2, '15A93A69', 0, NULL),
(35, 12, 2, '7D81EA9C', 0, NULL),
(36, 12, 2, '3FBAB964', 0, NULL),
(37, 12, 2, 'CE673CA8', 0, NULL),
(38, 12, 2, '647ABA62', 0, NULL),
(39, 12, 2, 'DA1DB308', 0, NULL),
(40, 12, 2, '74AF6E30', 0, NULL),
(41, 12, 2, 'F953EFE3', 0, NULL),
(42, 12, 3, 'D8429ABF', 0, NULL),
(43, 12, 3, '4E85DDF3', 0, NULL),
(44, 12, 3, 'D314982C', 0, NULL),
(45, 12, 3, 'ED64B045', 0, NULL),
(46, 12, 3, '0D07203B', 0, NULL),
(47, 12, 3, '047FC797', 0, NULL),
(48, 12, 3, '2F0C0D1E', 0, NULL),
(49, 12, 3, 'E1D3DB69', 0, NULL),
(50, 12, 3, 'CF952042', 0, NULL),
(51, 12, 3, '97BF9EA5', 0, NULL),
(52, 13, 1, '21382ADA', 0, NULL),
(53, 13, 1, '44F427ED', 0, NULL),
(54, 13, 1, 'AE3E2DF5', 0, NULL),
(55, 13, 1, '6B70ACE0', 0, NULL),
(56, 13, 1, 'F5874D93', 0, NULL),
(57, 13, 1, 'AC6CF007', 0, NULL),
(58, 13, 1, '4388D25C', 0, NULL),
(59, 13, 1, '8CA45F1A', 0, NULL),
(60, 13, 2, '930E0B9D', 0, NULL),
(61, 13, 2, '89BDCF25', 0, NULL),
(62, 13, 2, '00448FE1', 0, NULL),
(63, 13, 2, 'CD3A1858', 0, NULL),
(64, 13, 2, '2FE73069', 0, NULL),
(65, 13, 3, 'B06110BC', 0, NULL),
(66, 13, 3, '82255D9B', 0, NULL),
(67, 13, 3, 'B77E12A0', 0, NULL),
(68, 14, 1, 'CB0C017E', 0, NULL),
(69, 14, 1, '7B9F03EA', 0, NULL),
(70, 14, 1, '2B5E602F', 0, NULL),
(71, 14, 1, '79AC657C', 0, NULL),
(72, 14, 1, '935775CB', 0, NULL),
(73, 14, 1, '5A315E08', 0, NULL),
(74, 14, 1, 'B1CEEBB5', 0, NULL),
(75, 14, 2, '6B254BDB', 0, NULL),
(76, 14, 2, 'CE8AE896', 0, NULL),
(77, 14, 2, '17F42B1A', 0, NULL),
(78, 14, 2, '898D02D0', 0, NULL),
(79, 14, 2, 'A3FAAC09', 0, NULL),
(80, 14, 2, 'DCB21CC3', 0, NULL),
(81, 14, 3, '5AD9D6EA', 0, NULL),
(82, 14, 3, 'D1785541', 0, NULL),
(83, 14, 3, '36B9BB84', 0, NULL),
(84, 14, 3, '283BFFCE', 0, NULL),
(85, 14, 3, '475B9DA7', 0, NULL),
(86, 14, 3, '9F56DB36', 0, NULL),
(87, 14, 3, '3B8C12AF', 0, NULL),
(88, 14, 3, '34556AE0', 0, NULL),
(89, 15, 1, '3713D47F', 0, NULL),
(90, 15, 1, '83047FAD', 0, NULL),
(91, 15, 1, '2AC8E8DA', 0, NULL),
(92, 15, 1, '7022DADF', 0, NULL),
(93, 15, 1, '62FCFD45', 0, NULL),
(94, 15, 1, '928B5888', 0, NULL),
(95, 15, 1, '3DAB2E61', 0, NULL),
(96, 15, 1, '4869588D', 0, NULL),
(97, 15, 1, 'A3005166', 0, NULL),
(98, 15, 1, '5C1D5CED', 0, NULL),
(99, 15, 2, '669046DE', 0, NULL),
(100, 15, 2, '05CB81D8', 0, NULL),
(101, 15, 2, 'B5BA2DE9', 0, NULL),
(102, 15, 2, 'B3485C61', 0, NULL),
(103, 15, 2, '201F1908', 0, NULL),
(104, 15, 2, 'E6B19215', 0, NULL),
(105, 15, 2, '2C742861', 0, NULL),
(106, 15, 2, '44C0C5DC', 0, NULL),
(107, 15, 2, '592A3CF3', 0, NULL),
(108, 15, 2, '4AF241E0', 0, NULL),
(109, 15, 3, '064B9BE2', 0, NULL),
(110, 15, 3, 'B16A3C12', 0, NULL),
(111, 15, 3, '1E05FA3B', 0, NULL),
(112, 15, 3, 'FC4FE6F7', 0, NULL),
(113, 15, 3, '908348B3', 0, NULL),
(114, 15, 3, '15DB8DDB', 0, NULL),
(115, 15, 3, 'CD02184C', 0, NULL),
(116, 15, 3, 'EFFE9A4E', 0, NULL),
(117, 15, 3, 'A346296F', 0, NULL),
(118, 15, 3, 'D1C6C1E4', 0, NULL),
(119, 16, 1, '6DCEFA42', 0, NULL),
(120, 16, 1, '49AB59EB', 0, NULL),
(121, 16, 1, '7562E715', 0, NULL),
(122, 16, 2, '17F4B786', 0, NULL),
(123, 16, 2, '94C70D2A', 0, NULL),
(124, 16, 2, '0BE798C2', 0, NULL),
(125, 16, 2, '2583F7CC', 0, NULL),
(126, 16, 2, '74BC79C6', 0, NULL),
(127, 16, 3, '0FA41C2E', 0, NULL),
(128, 16, 3, '01A0A17F', 0, NULL),
(129, 16, 3, '5511AD8B', 0, NULL),
(130, 17, 1, 'DE97D6AE', 0, NULL),
(131, 17, 1, '75D8C37C', 0, NULL),
(132, 17, 2, '95B99FEF', 0, NULL),
(133, 17, 2, '8A835D17', 0, NULL),
(134, 17, 3, '37B82A1F', 0, NULL),
(135, 17, 3, '3D1E26AC', 0, NULL),
(136, 18, 1, '9A7086FA', 0, NULL),
(137, 18, 1, '91E20D5E', 0, NULL),
(138, 18, 1, '17CD09F0', 0, NULL),
(139, 18, 2, 'BEEEDCF2', 0, NULL),
(140, 18, 2, '43A90464', 0, NULL),
(141, 18, 2, 'CA4E7F01', 0, NULL),
(142, 18, 3, '89FDFBB0', 0, NULL),
(143, 18, 3, '45C659F4', 0, NULL),
(144, 18, 3, '96E6A77E', 0, NULL),
(145, 19, 3, '41348241', 0, NULL),
(146, 20, 3, '354448F2', 0, NULL),
(147, 20, 3, 'D57EE6D3', 0, NULL),
(148, 20, 3, 'A846A8F1', 0, NULL),
(149, 21, 1, '8F2A1C9D', 0, NULL),
(150, 21, 1, '3E7B4D1F', 0, NULL),
(151, 21, 2, '5A9C2E4B', 0, NULL),
(152, 21, 2, '1D3F8E7A', 0, NULL),
(153, 22, 1, '7C4D1A8E', 0, NULL),
(154, 22, 1, '2E9B3F5C', 0, NULL),
(155, 22, 1, '6A1D4E8B', 0, NULL),
(156, 22, 2, '9F3C7A2E', 0, NULL),
(157, 22, 2, '4B8E1D3F', 0, NULL),
(158, 22, 3, '5E2A9C7B', 0, NULL),
(159, 23, 1, '1F8D3E4A', 0, NULL),
(160, 23, 1, '7A2B9C5E', 0, NULL),
(161, 23, 2, '3C4E1F8D', 0, NULL),
(162, 24, 1, '8B5A2C9E', 0, NULL),
(163, 24, 1, '4D7E1F3A', 0, NULL),
(164, 24, 1, '2C9B8A5E', 0, NULL),
(165, 24, 1, '6F3D4E1B', 0, NULL),
(166, 24, 2, '1A8E5C2D', 0, NULL),
(167, 24, 2, '9E3F7B4A', 0, NULL),
(168, 24, 2, '5C2D8A1E', 0, NULL),
(169, 25, 1, '3D1F4E8B', 0, NULL),
(170, 25, 1, '7A5B2C9E', 0, NULL),
(171, 25, 1, '1E8D3F4A', 0, NULL),
(172, 25, 1, '9C2E7A5B', 0, NULL),
(173, 26, 1, '4E8B1D3F', 0, NULL),
(174, 26, 2, '2A9C5E7B', 0, NULL),
(175, 26, 2, '8D3F4E1A', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `date_visite` date NOT NULL,
  `prix_total` decimal(8,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `utilisateur_id`, `date_visite`, `prix_total`, `created_at`) VALUES
(9, 2, '2026-10-15', 308.00, '2026-10-15 15:51:00'),
(10, 1, '2026-10-31', 90.00, '2026-10-15 15:58:12'),
(11, 1, '2026-10-16', 38.00, '2026-10-16 09:21:29'),
(12, 1, '2026-10-16', 1130.00, '2026-10-16 09:28:11'),
(13, 1, '2026-10-16', 624.00, '2026-10-16 09:29:24'),
(14, 1, '2026-10-16', 799.00, '2026-10-16 09:29:44'),
(15, 1, '2026-10-16', 1130.00, '2026-10-16 09:30:29'),
(16, 1, '2026-10-16', 478.80, '2026-10-16 09:31:13'),
(17, 1, '2026-10-16', 395.50, '2026-10-16 09:35:51'),
(18, 2, '2026-10-16', 339.00, '2026-10-16 10:54:40'),
(19, 2, '2026-10-16', 38.00, '2026-10-16 11:32:53'),
(20, 2, '2026-10-16', 0.00, '2026-10-16 13:12:03'),
(21, 8, '2026-10-20', 150.00, '2026-10-17 09:15:00'),
(22, 9, '2026-10-22', 210.00, '2026-10-17 10:30:00'),
(23, 10, '2026-10-25', 120.00, '2026-10-17 11:00:00'),
(24, 11, '2026-10-31', 270.00, '2026-10-17 12:45:00'),
(25, 8, '2026-11-01', 180.00, '2026-10-17 14:10:00'),
(26, 9, '2026-11-05', 105.00, '2026-10-17 15:20:00');

-- --------------------------------------------------------

--
-- Structure de la table `reservation_billets`
--

CREATE TABLE `reservation_billets` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `tarif_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservation_billets`
--

INSERT INTO `reservation_billets` (`id`, `reservation_id`, `tarif_id`, `quantite`, `prix_unitaire`) VALUES
(17, 9, 1, 2, 45.00),
(18, 9, 2, 6, 30.00),
(19, 9, 3, 1, 38.00),
(20, 10, 1, 2, 45.00),
(21, 9, 3, 1000, 0.00),
(23, 11, 3, 1, 38.00),
(24, 12, 1, 10, 45.00),
(25, 12, 2, 10, 30.00),
(26, 12, 3, 130, 38.00),
(27, 13, 1, 8, 45.00),
(28, 13, 2, 5, 30.00),
(29, 13, 3, 3, 38.00),
(30, 14, 1, 7, 45.00),
(31, 14, 2, 6, 30.00),
(32, 14, 3, 8, 38.00),
(33, 15, 1, 10, 45.00),
(34, 15, 2, 10, 30.00),
(35, 15, 3, 140, 38.00),
(36, 16, 1, 150, 54.00),
(37, 16, 2, 305, 36.00),
(38, 16, 3, 3, 45.60),
(39, 17, 1, 2, 78.75),
(40, 17, 2, 2, 52.50),
(41, 17, 3, 2, 66.50),
(42, 18, 1, 3, 45.00),
(43, 18, 2, 3, 30.00),
(44, 18, 3, 3, 38.00),
(45, 19, 3, 1, 38.00),
(46, 20, 3, 3, 0.00),
(47, 21, 1, 2, 45.00),
(48, 21, 2, 2, 30.00),
(49, 22, 1, 3, 45.00),
(50, 22, 2, 2, 30.00),
(51, 22, 3, 1, 0.00),
(52, 23, 1, 2, 45.00),
(53, 23, 2, 1, 30.00),
(54, 24, 1, 4, 45.00),
(55, 24, 2, 3, 30.00),
(56, 25, 1, 4, 45.00),
(57, 26, 1, 1, 45.00),
(58, 26, 2, 2, 30.00);

-- --------------------------------------------------------

--
-- Structure de la table `tarifs`
--

CREATE TABLE `tarifs` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `prix` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tarifs`
--

INSERT INTO `tarifs` (`id`, `libelle`, `description`, `prix`) VALUES
(1, 'Adulte (12 ans et +)', 'Accès illimité à tous les univers du parc', 45.00),
(2, 'Enfant (3 à 11 ans)', 'Taille adaptées aux gags cartoon !', 30.00),
(3, 'Moins de 3 ans', 'Pour les plus petits', 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `univers`
--

CREATE TABLE `univers` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `univers`
--

INSERT INTO `univers` (`id`, `nom`, `description`) VALUES
(1, 'Studio 2D', 'L’univers des grands classiques du crayonné, des faux murs peints et des lois de la physique abolies.'),
(2, 'Le Chapiteau', 'L’espace burlesque et survolté inspiré des grands numéros de cirque et de cascades explosives.');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `reset_token`, `reset_expires_at`) VALUES
(1, 'Admin Toon', 'admin@studiotoon.com', '$argon2id$v=19$m=65536,t=4,p=1$SUkzaFRTLzRSYU5iWU01Yg$7s5S/MNb0Q/WGZ9+I2wbUVYme8GbHsYs2SIN0RtICaE', 'admin', NULL, NULL),
(2, 'Visiteur Test', 'user@studiotoon.com', '$argon2id$v=19$m=65536,t=4,p=1$blA2TjRLc0ppdEVPRGZ2Zw$zi5vOGsoPp2TrCP0vyc8LXpWkctx+IRS7B8jVef3KWk', 'user', '753c1aac6884698f3f9228cbfc35a3b7b678d01f4982f87932f123cea8842df2', '2026-10-15 16:43:51'),
(7, 'test', 'test@studiotoon.com', '$argon2id$v=19$m=65536,t=4,p=1$R0tCZ3phVENTc0dPM0pYWQ$K8CSIEknZCy8OWFd8OBw/P4LgZvSlelXmt7ZUsDdOMs', 'user', NULL, NULL),
(8, 'Jean Dupont', 'jean.dupont@email.com', '$argon2id$v=19$m=65536,t=4,p=1$ZnJTSHNmV0QuUkJtLzFQVA$gs9i6kfkZnWD074YThYotVg697jyelNFroH8YPzpM5U', 'user', NULL, NULL),
(9, 'Clara Martin', 'clara.martin@email.com', '$argon2id$v=19$m=65536,t=4,p=1$aFphbWNmMkJ2cUlEM1NMbw$jfrNDIqPRbVqTzSK/3NorQBF1lehgq3ybUDMoG1jGms', 'user', NULL, NULL),
(10, 'Lucas Bernard', 'lucas.bernard@email.com', '$argon2id$v=19$m=65536,t=4,p=1$UDZOVG5SYXlZSlJoWkNmbw$DsfUZJoyJiqOZp+SjUPVp90bLVU/hDmu3kN5wkPH3Tc', 'user', NULL, NULL),
(11, 'Chloé Durand', 'chloe.durand@email.com', '$argon2id$v=19$m=65536,t=4,p=1$d2QudThoam5WTTdBUDBXYw$j00VIvSqOggaSheW839tZCRKsWFiYdlgHy1RqLitYlE', 'user', NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin_logs_attentes`
--
ALTER TABLE `admin_logs_attentes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attraction_id` (`attraction_id`);

--
-- Index pour la table `attractions`
--
ALTER TABLE `attractions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attraction_univers` (`univers_id`);

--
-- Index pour la table `e_billets`
--
ALTER TABLE `e_billets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_unique` (`code_unique`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `tarif_id` (`tarif_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `reservation_billets`
--
ALTER TABLE `reservation_billets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`),
  ADD KEY `tarif_id` (`tarif_id`);

--
-- Index pour la table `tarifs`
--
ALTER TABLE `tarifs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `univers`
--
ALTER TABLE `univers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin_logs_attentes`
--
ALTER TABLE `admin_logs_attentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `attractions`
--
ALTER TABLE `attractions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `e_billets`
--
ALTER TABLE `e_billets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `reservation_billets`
--
ALTER TABLE `reservation_billets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `tarifs`
--
ALTER TABLE `tarifs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `univers`
--
ALTER TABLE `univers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin_logs_attentes`
--
ALTER TABLE `admin_logs_attentes`
  ADD CONSTRAINT `admin_logs_attentes_ibfk_1` FOREIGN KEY (`attraction_id`) REFERENCES `attractions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `attractions`
--
ALTER TABLE `attractions`
  ADD CONSTRAINT `fk_attraction_univers` FOREIGN KEY (`univers_id`) REFERENCES `univers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `e_billets`
--
ALTER TABLE `e_billets`
  ADD CONSTRAINT `e_billets_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `e_billets_ibfk_2` FOREIGN KEY (`tarif_id`) REFERENCES `tarifs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservation_billets`
--
ALTER TABLE `reservation_billets`
  ADD CONSTRAINT `reservation_billets_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_billets_ibfk_2` FOREIGN KEY (`tarif_id`) REFERENCES `tarifs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
