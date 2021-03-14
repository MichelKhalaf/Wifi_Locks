-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 27, 2020 at 07:10 AM
-- Server version: 5.7.24
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wifi_locks`
--
CREATE DATABASE IF NOT EXISTS `wifi_locks` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `wifi_locks`;

-- --------------------------------------------------------

--
-- Table structure for table `appartenancegrp`
--

DROP TABLE IF EXISTS `appartenancegrp`;
CREATE TABLE IF NOT EXISTS `appartenancegrp` (
  `Groupe` int(8) NOT NULL,
  `Porte` varchar(16) NOT NULL,
  PRIMARY KEY (`Groupe`,`Porte`),
  KEY `CodeGrp` (`Groupe`),
  KEY `CodePorte` (`Porte`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `appartenancegrp`
--

INSERT INTO `appartenancegrp` (`Groupe`, `Porte`) VALUES
(1, 'SCC01'),
(1, 'SCC02'),
(1, 'SCI01'),
(1, 'SCI02'),
(1, 'SCI03'),
(1, 'SCI04'),
(2, 'SCO01'),
(2, 'SCO02'),
(2, 'SCO03'),
(3, 'SCI01'),
(3, 'SCI02'),
(3, 'SCI03'),
(3, 'SCI04'),
(3, 'SCO01'),
(3, 'SCO02'),
(3, 'SCO03'),
(4, 'SCC01'),
(4, 'SCC02'),
(5, 'SCC01'),
(5, 'SCC02'),
(5, 'SCC03'),
(5, 'SCI01'),
(5, 'SCI02'),
(5, 'SCI03'),
(5, 'SCI04'),
(5, 'SCO01'),
(5, 'SCO02'),
(5, 'SCO03'),
(6, 'SCC03');

-- --------------------------------------------------------

--
-- Table structure for table `batiments`
--

DROP TABLE IF EXISTS `batiments`;
CREATE TABLE IF NOT EXISTS `batiments` (
  `NumBat` int(4) NOT NULL AUTO_INCREMENT,
  `NomBat` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`NumBat`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `batiments`
--

INSERT INTO `batiments` (`NumBat`, `NomBat`) VALUES
(1, 'ESIB'),
(2, 'INCI'),
(3, 'SCP'),
(4, 'FS'),
(5, 'IGE'),
(6, 'Berytech');

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `IDCmd` int(4) NOT NULL AUTO_INCREMENT,
  `NomCmd` varchar(64) DEFAULT NULL,
  `DescCmd` varchar(256) DEFAULT NULL,
  `Commande` tinyint(1) NOT NULL,
  `CodeGrp` int(8) NOT NULL,
  `DateExec` datetime NOT NULL,
  `Repetition` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IDCmd`),
  KEY `CodeGrp` (`CodeGrp`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `commandes`
--

INSERT INTO `commandes` (`IDCmd`, `NomCmd`, `DescCmd`, `Commande`, `CodeGrp`, `DateExec`, `Repetition`) VALUES
(3, 'Routine Matin', 'Ouverture des salles de cours chaque jour Ã  6h', 1, 1, '2020-05-27 06:00:00', 1),
(4, 'Routine Soir', 'Fermeture des salles de cours chaque jour Ã  22h', 0, 1, '2020-05-27 22:00:00', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `detailsappartenancegrp`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `detailsappartenancegrp`;
CREATE TABLE IF NOT EXISTS `detailsappartenancegrp` (
`porte` varchar(16)
,`Groupe` int(8)
,`NomGrp` varchar(64)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `detailsprivileges`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `detailsprivileges`;
CREATE TABLE IF NOT EXISTS `detailsprivileges` (
`Matricule` int(8) unsigned
,`Nom` varchar(65)
,`CodeGrp` int(8)
,`NomGrp` varchar(64)
);

-- --------------------------------------------------------

--
-- Table structure for table `groupes`
--

DROP TABLE IF EXISTS `groupes`;
CREATE TABLE IF NOT EXISTS `groupes` (
  `CodeGrp` int(8) NOT NULL AUTO_INCREMENT,
  `NomGrp` varchar(64) DEFAULT NULL,
  `Descriptif` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`CodeGrp`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groupes`
--

INSERT INTO `groupes` (`CodeGrp`, `NomGrp`, `Descriptif`) VALUES
(1, 'Classes', 'Salles de Classe'),
(2, 'Examens', 'Salles d\'Examen'),
(3, 'ESIB', 'Salles Ã  l\'ESIB'),
(4, 'INCI', 'Salles Ã  l\'INCI'),
(5, 'Toutes les portes', 'CST'),
(6, 'Christopher', 'Portes exclusives Ã  Christopher Habib-RahmÃ©');

-- --------------------------------------------------------

--
-- Table structure for table `historique`
--

DROP TABLE IF EXISTS `historique`;
CREATE TABLE IF NOT EXISTS `historique` (
  `Date` datetime NOT NULL,
  `Utilisateur` int(8) UNSIGNED NOT NULL,
  `Action` tinyint(1) NOT NULL,
  `Porte` varchar(16) NOT NULL,
  PRIMARY KEY (`Date`,`Utilisateur`,`Porte`),
  KEY `fk_historique_porte` (`Porte`),
  KEY `fk_historique_utilisateur` (`Utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manipulation`
--

DROP TABLE IF EXISTS `manipulation`;
CREATE TABLE IF NOT EXISTS `manipulation` (
  `IDOuv` int(9) NOT NULL AUTO_INCREMENT,
  `Matricule` int(8) UNSIGNED NOT NULL,
  `Action` tinyint(1) NOT NULL,
  `CodePorte` varchar(16) NOT NULL,
  PRIMARY KEY (`IDOuv`),
  KEY `Matricule` (`Matricule`),
  KEY `CodePorte` (`CodePorte`)
) ENGINE=InnoDB AUTO_INCREMENT=416 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `manipulation`
--

INSERT INTO `manipulation` (`IDOuv`, `Matricule`, `Action`, `CodePorte`) VALUES
(156, 172417, 1, 'SCO02');

-- --------------------------------------------------------

--
-- Table structure for table `portes`
--

DROP TABLE IF EXISTS `portes`;
CREATE TABLE IF NOT EXISTS `portes` (
  `CodePorte` varchar(16) NOT NULL,
  `Nature` varchar(128) NOT NULL,
  `NumBat` int(4) NOT NULL,
  `Etage` int(4) DEFAULT NULL,
  `EtatPorte` tinyint(1) NOT NULL,
  `EtatSerrure` tinyint(1) NOT NULL,
  PRIMARY KEY (`CodePorte`),
  KEY `NumBat` (`NumBat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `portes`
--

INSERT INTO `portes` (`CodePorte`, `Nature`, `NumBat`, `Etage`, `EtatPorte`, `EtatSerrure`) VALUES
('SCC01', 'Classe', 2, 1, 0, 0),
('SCC02', 'Classe', 2, 1, 0, 0),
('SCC03', 'Bureau Professeur', 2, 2, 0, 0),
('SCI01', 'Classe', 1, 1, 0, 0),
('SCI02', 'Classe', 1, 1, 0, 0),
('SCI03', 'Classe', 1, 1, 0, 0),
('SCI04', 'Classe', 1, 1, 0, 0),
('SCO01', 'Examen', 1, 2, 0, 0),
('SCO02', 'Examen', 1, 2, 0, 0),
('SCO03', 'Examen', 1, 2, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

DROP TABLE IF EXISTS `privileges`;
CREATE TABLE IF NOT EXISTS `privileges` (
  `Matricule` int(8) UNSIGNED NOT NULL,
  `CodeGrp` int(8) NOT NULL,
  PRIMARY KEY (`Matricule`,`CodeGrp`),
  KEY `Matricule` (`Matricule`),
  KEY `CodeGrp` (`CodeGrp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`Matricule`, `CodeGrp`) VALUES
(170378, 3),
(170378, 4),
(171417, 3),
(172077, 1),
(172077, 2),
(172262, 1),
(172417, 1),
(172417, 2),
(172417, 6),
(702782, 5);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `Matricule` int(8) UNSIGNED NOT NULL,
  `Nom` varchar(32) NOT NULL,
  `Prenom` varchar(32) NOT NULL,
  `Fonction` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`Matricule`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`Matricule`, `Nom`, `Prenom`, `Fonction`) VALUES
(170378, 'Nohra', 'Jean-Paul', 'Portier'),
(171417, 'Murr (El)', 'Richard', 'Directeur ESIB'),
(172077, 'Khalaf', 'Michel', 'Doyen ESIB'),
(172262, 'Milan El Chartouny', 'Sylvio', 'Professeur ESIB'),
(172417, 'Habib-RahmÃ©', 'Christopher', 'Doyen INCI'),
(702782, 'Renno', 'Jihad', 'Administrateur');

-- --------------------------------------------------------

--
-- Structure for view `detailsappartenancegrp`
--
DROP TABLE IF EXISTS `detailsappartenancegrp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detailsappartenancegrp`  AS  select `appartenancegrp`.`Porte` AS `porte`,`appartenancegrp`.`Groupe` AS `Groupe`,`groupes`.`NomGrp` AS `NomGrp` from (`appartenancegrp` join `groupes`) where (`appartenancegrp`.`Groupe` = `groupes`.`CodeGrp`) ;

-- --------------------------------------------------------

--
-- Structure for view `detailsprivileges`
--
DROP TABLE IF EXISTS `detailsprivileges`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `detailsprivileges`  AS  select `privileges`.`Matricule` AS `Matricule`,concat_ws(' ',`utilisateurs`.`Nom`,`utilisateurs`.`Prenom`) AS `Nom`,`privileges`.`CodeGrp` AS `CodeGrp`,`groupes`.`NomGrp` AS `NomGrp` from ((`privileges` join `utilisateurs`) join `groupes`) where ((`privileges`.`Matricule` = `utilisateurs`.`Matricule`) and (`privileges`.`CodeGrp` = `groupes`.`CodeGrp`)) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appartenancegrp`
--
ALTER TABLE `appartenancegrp`
  ADD CONSTRAINT `fk_groupe` FOREIGN KEY (`Groupe`) REFERENCES `groupes` (`CodeGrp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_porte` FOREIGN KEY (`Porte`) REFERENCES `portes` (`CodePorte`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `fk_cmd_grp` FOREIGN KEY (`CodeGrp`) REFERENCES `groupes` (`CodeGrp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `fk_historique_porte` FOREIGN KEY (`Porte`) REFERENCES `portes` (`CodePorte`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_historique_utilisateur` FOREIGN KEY (`Utilisateur`) REFERENCES `utilisateurs` (`Matricule`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `manipulation`
--
ALTER TABLE `manipulation`
  ADD CONSTRAINT `fk_ouv_porte` FOREIGN KEY (`CodePorte`) REFERENCES `portes` (`CodePorte`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ouv_util` FOREIGN KEY (`Matricule`) REFERENCES `utilisateurs` (`Matricule`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `portes`
--
ALTER TABLE `portes`
  ADD CONSTRAINT `fk_portes_bat` FOREIGN KEY (`NumBat`) REFERENCES `batiments` (`NumBat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `privileges`
--
ALTER TABLE `privileges`
  ADD CONSTRAINT `fk_privil_grp` FOREIGN KEY (`CodeGrp`) REFERENCES `groupes` (`CodeGrp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_privil_util` FOREIGN KEY (`Matricule`) REFERENCES `utilisateurs` (`Matricule`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
