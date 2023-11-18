-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 18, 2023 at 08:27 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `events`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

DROP TABLE IF EXISTS `agenda`;
CREATE TABLE IF NOT EXISTS `agenda` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titlu_activitate` varchar(255) DEFAULT NULL,
  `descriere` text,
  `ora_start` time DEFAULT NULL,
  `ora_final` time DEFAULT NULL,
  `eveniment_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eveniment_id` (`eveniment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bilete`
--

DROP TABLE IF EXISTS `bilete`;
CREATE TABLE IF NOT EXISTS `bilete` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tip_bilet` varchar(255) NOT NULL,
  `pret` decimal(10,2) DEFAULT NULL,
  `eveniment_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eveniment_id` (`eveniment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `evenimente`
--

DROP TABLE IF EXISTS `evenimente`;
CREATE TABLE IF NOT EXISTS `evenimente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titlu` varchar(255) NOT NULL,
  `descriere` text,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `locatie` varchar(255) NOT NULL,
  `creat_de` int DEFAULT NULL,
  `speaker_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creat_de` (`creat_de`),
  KEY `speaker_id` (`speaker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `evenimente`
--

INSERT INTO `evenimente` (`id`, `titlu`, `descriere`, `data`, `ora`, `locatie`, `creat_de`, `speaker_id`) VALUES
(4, 'Concert Ian', 'concert fortza rau', '2015-02-01', '00:39:00', 'form', 2, 1),
(5, 'Concert Azteca', 'concert tare rau jur', '2020-11-23', '03:06:00', 'Euphoria', 2, 1),
(6, 'Concert Ian si Azteca', 'esti nebun s au impacat', '3000-02-02', '06:06:00', 'Cluj Arena', 2, 1),
(7, 'asdasdad', 'oasfjoisjfisdajf', '2023-11-28', '15:35:00', 'arges', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `eveniment_partener`
--

DROP TABLE IF EXISTS `eveniment_partener`;
CREATE TABLE IF NOT EXISTS `eveniment_partener` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eveniment_id` int DEFAULT NULL,
  `partener_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eveniment_id` (`eveniment_id`),
  KEY `partener_id` (`partener_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parteneri`
--

DROP TABLE IF EXISTS `parteneri`;
CREATE TABLE IF NOT EXISTS `parteneri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nume` varchar(255) NOT NULL,
  `descriere` text,
  `logo` varchar(255) DEFAULT NULL,
  `eveniment_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eveniment_id` (`eveniment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `speakeri`
--

DROP TABLE IF EXISTS `speakeri`;
CREATE TABLE IF NOT EXISTS `speakeri` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nume` varchar(255) NOT NULL,
  `descriere` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `speakeri`
--

INSERT INTO `speakeri` (`id`, `nume`, `descriere`) VALUES
(1, 'awfwsf', 'afef');

-- --------------------------------------------------------

--
-- Table structure for table `utilizatori`
--

DROP TABLE IF EXISTS `utilizatori`;
CREATE TABLE IF NOT EXISTS `utilizatori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL,
  `parola` varchar(64) NOT NULL,
  `esteAdministrator` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `utilizatori`
--

INSERT INTO `utilizatori` (`id`, `username`, `email`, `parola`, `esteAdministrator`) VALUES
(1, 'a', 'a@b.c', '900150983cd24fb0d6963f7d28e17f72', 1),
(2, 'bbb', 'b@b.b', '08f8e0260c64418510cefb2b06eee5cd', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`eveniment_id`) REFERENCES `evenimente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bilete`
--
ALTER TABLE `bilete`
  ADD CONSTRAINT `bilete_ibfk_1` FOREIGN KEY (`eveniment_id`) REFERENCES `evenimente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `evenimente`
--
ALTER TABLE `evenimente`
  ADD CONSTRAINT `evenimente_ibfk_1` FOREIGN KEY (`creat_de`) REFERENCES `utilizatori` (`id`),
  ADD CONSTRAINT `spk_id_fk` FOREIGN KEY (`speaker_id`) REFERENCES `speakeri` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `eveniment_partener`
--
ALTER TABLE `eveniment_partener`
  ADD CONSTRAINT `eveniment_id_fk` FOREIGN KEY (`eveniment_id`) REFERENCES `evenimente` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `partener_id_fk` FOREIGN KEY (`partener_id`) REFERENCES `parteneri` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
