-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 15 déc. 2022 à 18:13
-- Version du serveur :  10.3.37-MariaDB-0ubuntu0.20.04.1
-- Version de PHP : 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `galerie`
--

-- --------------------------------------------------------

--
-- Structure de la table `Data_Exif`
--

CREATE TABLE `Data_Exif` (
  `ID` int(11) NOT NULL,
  `Nom_image` varchar(30) NOT NULL,
  `FileDateTime` datetime DEFAULT NULL,
  `ExposureTime` varchar(30) DEFAULT NULL,
  `FNumber` varchar(30) DEFAULT NULL,
  `ISOSpeedRatings` varchar(30) DEFAULT NULL,
  `ExifVersion` varchar(30) DEFAULT NULL,
  `DateTimeOriginal` datetime DEFAULT NULL,
  `DateTimeDigitized` datetime DEFAULT NULL,
  `ComponentsConfiguration` varchar(30) DEFAULT NULL,
  `MaxApertureValue` varchar(30) DEFAULT NULL,
  `Flash` varchar(30) DEFAULT NULL,
  `FocalLength` varchar(30) DEFAULT NULL,
  `MakerNote` varchar(30) DEFAULT NULL,
  `FlashPixVersion` varchar(30) DEFAULT NULL,
  `ColorSpace` varchar(30) DEFAULT NULL,
  `ExifImageWidth` varchar(30) DEFAULT NULL,
  `ExifImageLength` varchar(30) DEFAULT NULL,
  `InteroperabilityOffset` varchar(30) DEFAULT NULL,
  `FocalPlaneYResolution` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Data_Exif`
--

INSERT INTO `Data_Exif` (`ID`, `Nom_image`, `FileDateTime`, `ExposureTime`, `FNumber`, `ISOSpeedRatings`, `ExifVersion`, `DateTimeOriginal`, `DateTimeDigitized`, `ComponentsConfiguration`, `MaxApertureValue`, `Flash`, `FocalLength`, `MakerNote`, `FlashPixVersion`, `ColorSpace`, `ExifImageWidth`, `ExifImageLength`, `InteroperabilityOffset`, `FocalPlaneYResolution`) VALUES
(17, 'photo_alex.jpeg', '2015-12-22 04:47:53', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 'etudiants2014.jpeg', '2015-12-22 05:17:18', '1/20', '20/10', '375', '0220', '2014-06-11 21:29:44', '2013-06-26 18:00:00', '\0', '20/10', '1', '378/100', '', '0100', '1', '4160', '2340', '472', '7005235/1');

-- --------------------------------------------------------

--
-- Structure de la table `Users`
--

CREATE TABLE `Users` (
  `ID` int(11) NOT NULL,
  `Pseudo` varchar(60) NOT NULL,
  `MDP` varchar(20) NOT NULL,
  `Abonnement` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Users`
--

INSERT INTO `Users` (`ID`, `Pseudo`, `MDP`, `Abonnement`) VALUES
(1, 'alex', 'caby', 0),
(2, 'leo', 'vdb', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Data_Exif`
--
ALTER TABLE `Data_Exif`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Data_Exif`
--
ALTER TABLE `Data_Exif`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `Users`
--
ALTER TABLE `Users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
