-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 22, 2023 at 02:27 PM
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
  `partener_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eveniment_id` (`eveniment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `agenda`
--

INSERT INTO `agenda` (`id`, `titlu_activitate`, `descriere`, `ora_start`, `ora_final`, `eveniment_id`, `partener_id`) VALUES
(14, 'Conferința Internațională de Inovare în Tehnologie 2023', 'Networking și sesiuni de întrebări și răspunsuri cu experți în tehnologie', '00:00:00', '14:00:00', 8, 4),
(15, 'Conferința Internațională de Inovare în Tehnologie 2023', 'Concurs de idei de start-up, cu premii pentru cele mai promițătoare proiecte', '20:00:00', '22:00:00', 8, 3);

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
  `mail` varchar(255) DEFAULT NULL,
  `pret_bilet` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creat_de` (`creat_de`),
  KEY `speaker_id` (`speaker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `evenimente`
--

INSERT INTO `evenimente` (`id`, `titlu`, `descriere`, `data`, `ora`, `locatie`, `creat_de`, `speaker_id`, `mail`, `pret_bilet`) VALUES
(8, 'Conferința Internațională de Inovare în Tehnologie 2023', 'Eveniment de prestigiu, reunind experți și pasionați din lumea tehnologiei pentru a explora cele mai recente inovații în AI, securitate cibernetică și dezvoltare sustenabilă.', '2023-04-12', '14:00:00', 'Universitatea Tehnica', 2, 3, 'organizare@techconf23.org', '10.00'),
(9, 'Summitul Global al Liderilor în Energie Regenerabilă 2024', 'Un eveniment cheie pentru liderii industriei de energie regenerabilă, axat pe ultimele tendințe și inovații în energie solară, eoliană și hidroelectrică.', '2024-03-02', '16:00:00', 'Cluj Innovation Park', 2, 6, 'energie@summitglobal.com', '5.00'),
(10, 'Forumul Anual de Marketing Digital și Inovație 2023', 'O platformă pentru profesioniștii în marketing digital, concentrându-se pe strategii inovatoare, optimizarea SEO și noile abordări în publicitatea online.', '2023-11-28', '18:00:00', 'FSEGA', 2, 5, 'contact@mkforum23.com', '5.00');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `parteneri`
--

INSERT INTO `parteneri` (`id`, `nume`, `descriere`, `logo`) VALUES
(3, 'Asociația Dezvoltatorilor de Software', 'Asociația Dezvoltatorilor de Software', NULL),
(4, 'Centrul pentru Inovare și Tehnologie', 'Centrul pentru Inovare și Tehnologie', NULL),
(5, 'Universitatea Babes-Bolyai', 'Universitatea Babes-Bolyai', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `parteneri_activitati`
--

DROP TABLE IF EXISTS `parteneri_activitati`;
CREATE TABLE IF NOT EXISTS `parteneri_activitati` (
  `id` int NOT NULL AUTO_INCREMENT,
  `eveniment_id` int DEFAULT NULL,
  `partener_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eveniment_id` (`eveniment_id`),
  KEY `partener_id` (`partener_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `parteneri_activitati`
--

INSERT INTO `parteneri_activitati` (`id`, `eveniment_id`, `partener_id`) VALUES
(19, NULL, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `speakeri`
--

INSERT INTO `speakeri` (`id`, `nume`, `descriere`) VALUES
(3, 'Dr. Ana Popescu', 'Expert în inteligență artificială, Universitatea Politehnică'),
(4, 'Ing. Mihai Ionescu', 'CEO al Michaelsoft, pionier în software'),
(5, 'Dr. Laura Vasilescu', 'Specialist în marketing si SEO'),
(6, 'Dr. Albert Anghelescu', 'Biolog si specialist in prezervarea naturii');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `utilizatori`
--

INSERT INTO `utilizatori` (`id`, `username`, `email`, `parola`, `esteAdministrator`) VALUES
(1, 'a', 'a@b.c', '900150983cd24fb0d6963f7d28e17f72', 1),
(2, 'bbb', 'b@b.b', '08f8e0260c64418510cefb2b06eee5cd', 1),
(3, 'ccc', 'c@c.c', '9df62e693988eb4e1e1444ece0578579', 0);

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
-- Constraints for table `parteneri_activitati`
--
ALTER TABLE `parteneri_activitati`
  ADD CONSTRAINT `eveniment_id_fk` FOREIGN KEY (`eveniment_id`) REFERENCES `evenimente` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `partener_id_fk` FOREIGN KEY (`partener_id`) REFERENCES `parteneri` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
