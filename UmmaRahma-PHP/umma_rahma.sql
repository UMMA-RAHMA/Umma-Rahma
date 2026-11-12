-- Base de données Umma Rahma
-- PHP + MySQL pour InfinityFree / XAMPP

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','super_admin') DEFAULT 'admin',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mot de passe : admin123
INSERT INTO `utilisateurs` (`nom`, `email`, `mot_de_passe`, `role`) VALUES
('Administrateur', 'admin@umma-rahma.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin');

CREATE TABLE IF NOT EXISTS `evenements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `date_evenement` date NOT NULL,
  `lieu` varchar(150) DEFAULT NULL,
  `description` text NOT NULL,
  `image_couverture` varchar(255) DEFAULT NULL,
  `statut` enum('a_venir','passe') DEFAULT 'a_venir',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `medias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evenement_id` int(11) NOT NULL,
  `fichier` varchar(255) NOT NULL,
  `type` enum('image','video') NOT NULL,
  `ordre` int(11) DEFAULT 0,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `evenement_id` (`evenement_id`),
  CONSTRAINT `medias_ibfk_1` FOREIGN KEY (`evenement_id`) REFERENCES `evenements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `actualites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `contenu` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `a_la_une` tinyint(1) DEFAULT 0,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `parametres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cle` varchar(50) NOT NULL UNIQUE,
  `valeur` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `parametres` (`cle`, `valeur`) VALUES
('tel1', '+221 70 477 66 42'),
('tel2', '+221 78 455 62 69'),
('tel3', '+221 70 585 89 91'),
('email', 'rahmaoumma26@gmail.com'),
('facebook', 'https://www.facebook.com/share/197mq5wo2Y/'),
('instagram', 'https://www.instagram.com/umm.arahma'),
('tiktok', 'https://www.tiktok.com/@umma.rahma26'),
('youtube', 'https://youtube.com/@ummarahma-s4w'),
('wave', 'https://pay.wave.com/m/M_sn_-3jBgdphMdHc/c/sn/');

INSERT INTO `evenements` (`titre`, `slug`, `date_evenement`, `lieu`, `description`, `statut`) VALUES
('Événement du 09 Août 2026', 'evenement-09-aout-2026', '2026-08-09', 'Joal, Sénégal', 'Distribution de livres coraniques, vêtements et moments de partage avec les enfants talibés.', 'passe'),
('Journée de Solidarité & de Cohésion - 27 Septembre 2026', 'journee-solidarite-cohesion-27-septembre-2026', '2026-09-27', 'Joal, Sénégal', 'Une belle journée de solidarité et de cohésion avec UMMA RAHMA ! Journée riche en partage, en fraternité et en émotions.', 'passe');

COMMIT;
