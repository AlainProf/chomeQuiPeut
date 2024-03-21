-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 21 mars 2024 à 13:21
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cqp`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

DROP TABLE IF EXISTS `adresse`;
CREATE TABLE IF NOT EXISTS `adresse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_civique` varchar(20) NOT NULL,
  `rue` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `adresse`
--

INSERT INTO `adresse` (`id`, `num_civique`, `rue`, `ville`) VALUES
(1, '11', '11', '11'),
(2, '11', '11', 'st-je'),
(3, '1234', 'Cartier', 'Laval');

-- --------------------------------------------------------

--
-- Structure de la table `chomeur`
--

DROP TABLE IF EXISTS `chomeur`;
CREATE TABLE IF NOT EXISTS `chomeur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `courriel` varchar(100) NOT NULL,
  `telephone` varchar(10) NOT NULL,
  `date_naissance` date NOT NULL,
  `date_inscription` datetime NOT NULL,
  `adresse_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B3E45F1B4DE7DC5C` (`adresse_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `chomeur`
--

INSERT INTO `chomeur` (`id`, `nom`, `courriel`, `telephone`, `date_naissance`, `date_inscription`, `adresse_id`) VALUES
(1, 'albert', '11', '11', '2024-03-05', '2024-03-05 14:07:10', 1),
(2, 'bebert', '11', '11', '2024-03-05', '2024-03-05 14:07:37', 2),
(3, 'Mathieu', 'mat@mat.mat', '111', '2024-03-04', '2024-03-05 14:44:28', 3);

-- --------------------------------------------------------

--
-- Structure de la table `chomeur_offre_emploi`
--

DROP TABLE IF EXISTS `chomeur_offre_emploi`;
CREATE TABLE IF NOT EXISTS `chomeur_offre_emploi` (
  `chomeur_id` int NOT NULL,
  `offre_emploi_id` int NOT NULL,
  PRIMARY KEY (`chomeur_id`,`offre_emploi_id`),
  KEY `IDX_A67D8E63EC76EEE8` (`chomeur_id`),
  KEY `IDX_A67D8E63B08996ED` (`offre_emploi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `chomeur_offre_emploi`
--

INSERT INTO `chomeur_offre_emploi` (`chomeur_id`, `offre_emploi_id`) VALUES
(1, 1),
(2, 2),
(3, 1),
(3, 3),
(3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

DROP TABLE IF EXISTS `entreprise`;
CREATE TABLE IF NOT EXISTS `entreprise` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `contact` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`id`, `nom`, `contact`) VALUES
(1, 'coke', 'jim'),
(2, 'Hydro québec', 'René'),
(3, 'Cegep de CSTJ', 'paul');

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offre_emploi`
--

DROP TABLE IF EXISTS `offre_emploi`;
CREATE TABLE IF NOT EXISTS `offre_emploi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `salaireAnnuel` int NOT NULL,
  `date_publication` datetime NOT NULL,
  `entreprise_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_132AD0D1A4AEAFEA` (`entreprise_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `offre_emploi`
--

INSERT INTO `offre_emploi` (`id`, `titre`, `description`, `salaireAnnuel`, `date_publication`, `entreprise_id`) VALUES
(1, 'Analyste', 'bloblob', 125000, '2024-03-05 18:45:50', 2),
(2, 'Analyste', 'bloblob', 125000, '2024-03-05 18:46:20', 2),
(3, 'Convcierge', 'blablabla', 25000, '2024-03-05 19:45:38', 3),
(4, 'Enseihgnat', 'bloblbo', 80000, '2024-03-05 19:46:01', 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
