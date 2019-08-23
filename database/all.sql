-- --------------------------------------------------------
-- Host:                         5.230.148.224
-- Server Version:               10.1.38-MariaDB-0+deb9u1 - Debian 9.8
-- Server Betriebssystem:        debian-linux-gnu
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Exportiere Datenbank Struktur für 5gewinnt
CREATE DATABASE IF NOT EXISTS `5gewinnt` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `5gewinnt`;

-- Exportiere Struktur von Tabelle 5gewinnt.avatar
CREATE TABLE IF NOT EXISTS `avatar` (
  `username` varchar(50) CHARACTER SET utf16 COLLATE utf16_unicode_ci NOT NULL DEFAULT '',
  `img_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `img_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `img_size` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle 5gewinnt.game
CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player1` int(11) DEFAULT NULL COMMENT 'id of player 1',
  `player2` int(11) DEFAULT NULL COMMENT 'id of player 2',
  `color1` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `color2` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open',
  `clock1` double NOT NULL DEFAULT '180' COMMENT 'time left for player 1',
  `clock2` double NOT NULL DEFAULT '180' COMMENT 'time left for player 2',
  `last_move` double NOT NULL,
  `game_obj` varchar(3000) COLLATE utf8_unicode_ci NOT NULL COMMENT 'serialized php game object',
  `winner` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle 5gewinnt.spamcheck
CREATE TABLE IF NOT EXISTS `spamcheck` (
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `counter` tinyint(4) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt

-- Exportiere Struktur von Tabelle 5gewinnt.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf16 COLLATE utf16_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ip_v4` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `banned` tinyint(4) NOT NULL DEFAULT '0',
  `series_id` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `confirm_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmed` tinyint(4) DEFAULT '0',
  `password_request` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `age` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` varchar(17) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Daten Export vom Benutzer nicht ausgewählt

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
