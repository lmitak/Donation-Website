-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2015 at 08:50 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `donationdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `antispamming`
--

CREATE TABLE IF NOT EXISTS `antispamming` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cookie` varchar(65) NOT NULL,
  `commented` tinyint(1) NOT NULL DEFAULT '0',
  `rated` tinyint(1) NOT NULL DEFAULT '0',
  `idTvrtke` int(11) NOT NULL,
  `rateAmount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `cookie` (`cookie`),
  KEY `idTvrtke` (`idTvrtke`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `antispamming`
--

INSERT INTO `antispamming` (`id`, `cookie`, `commented`, `rated`, `idTvrtke`, `rateAmount`) VALUES
(21, '$2y$10$UxpRWa6Xpk2fEUtjtEupSOMDEf3KlXv8QpUuuFKcCQMK2c3pu4Aga', 1, 1, 1, 2),
(22, '$2y$10$1q2iFJDaL5G3QJRqQ8RLmOPChtgKeVQhpoG8.phTWZUbxyKwvL5nK', 1, 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `komentari`
--

CREATE TABLE IF NOT EXISTS `komentari` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idTvrtke` int(11) NOT NULL,
  `komentar` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idTvrtke` (`idTvrtke`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `komentari`
--

INSERT INTO `komentari` (`id`, `idTvrtke`, `komentar`) VALUES
(1, 1, 'Jako sam zadovoljna. '),
(2, 1, 'Kruh je bio fenomenalan. Vrlo su pristojni'),
(8, 1, 'decki super ste'),
(9, 1, 'ocjena 5'),
(10, 1, 'fan'),
(11, 1, 'kula'),
(12, 1, 'Ä‘orÄ‘'),
(13, 1, 'Pipak');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE IF NOT EXISTS `korisnik` (
  `idKorisnika` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(160) NOT NULL,
  `password` varchar(160) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`idKorisnika`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`idKorisnika`, `username`, `password`, `email`) VALUES
(1, 'stipe', '123', 'stipe_predanic@tvz.com'),
(2, 'luka', '$2y$10$IGnAlRGsfNgo3CIBBZ51jOpWeRsZuvF8DYdlASNBwoQvCBJdh3u72', 'luka_mitak@hotmail.com'),
(3, 'boro', '$2y$10$d6KfuAA.IhMnEsBFPsCXgeLpSnor04VAIfFvSnKpEsLfJQITuLwJe', 'luka@bro.com');

-- --------------------------------------------------------

--
-- Table structure for table `tiptvrtke`
--

CREATE TABLE IF NOT EXISTS `tiptvrtke` (
  `idTipa` int(11) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(255) NOT NULL,
  `ikona` varchar(255) NOT NULL,
  PRIMARY KEY (`idTipa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tiptvrtke`
--

INSERT INTO `tiptvrtke` (`idTipa`, `naziv`, `ikona`) VALUES
(1, 'Pekara', 'kru.png'),
(2, 'Mesnica', 'meat.png'),
(3, 'Voćarna', 'kruska.png'),
(4, 'Trgovina', 'kolica.png');

-- --------------------------------------------------------

--
-- Table structure for table `tvrtka`
--

CREATE TABLE IF NOT EXISTS `tvrtka` (
  `idTvrtke` int(11) NOT NULL AUTO_INCREMENT,
  `idKorisnika` int(11) NOT NULL,
  `naziv` varchar(80) NOT NULL,
  `tip` int(11) NOT NULL,
  `adresa` varchar(255) NOT NULL,
  `rating` double DEFAULT '0',
  `kolPopusta` int(11) DEFAULT NULL,
  `vrijemePopusta` time DEFAULT NULL,
  `dostupno` tinyint(1) DEFAULT '0',
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `datumPrijave` date NOT NULL,
  `web` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idTvrtke`),
  KEY `idKorisnika` (`idKorisnika`,`tip`),
  KEY `tip` (`tip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tvrtka`
--

INSERT INTO `tvrtka` (`idTvrtke`, `idKorisnika`, `naziv`, `tip`, `adresa`, `rating`, `kolPopusta`, `vrijemePopusta`, `dostupno`, `latitude`, `longitude`, `datumPrijave`, `web`) VALUES
(1, 1, 'Pekara Dolac', 1, 'Dolac 8', 2.91778564453125, 80, '12:20:00', 1, 45.814223, 15.977251, '0000-00-00', NULL),
(2, 1, 'Kim''s coffee', 3, 'Petrova 21', 3, 60, '22:20:00', 0, 45.81684, 15.99549, '0000-00-00', NULL),
(4, 1, 'Mesnica Velje', 2, 'Zigice 2', 3, 20, '13:56:47', 1, 45.861479, 15.780997, '0000-00-00', NULL),
(5, 1, 'Ilička kakica', 2, 'Mlinovi ulica 148A, Zagreb, Hrvatska', 0, NULL, NULL, 0, 45.8526904, 15.958590700000059, '2015-06-09', '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antispamming`
--
ALTER TABLE `antispamming`
  ADD CONSTRAINT `antispamming_ibfk_1` FOREIGN KEY (`idTvrtke`) REFERENCES `tvrtka` (`idTvrtke`) ON DELETE CASCADE;

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
  ADD CONSTRAINT `komentari_ibfk_1` FOREIGN KEY (`idTvrtke`) REFERENCES `tvrtka` (`idTvrtke`) ON DELETE CASCADE;

--
-- Constraints for table `tvrtka`
--
ALTER TABLE `tvrtka`
  ADD CONSTRAINT `tvrtka_ibfk_1` FOREIGN KEY (`idKorisnika`) REFERENCES `korisnik` (`idKorisnika`) ON DELETE CASCADE,
  ADD CONSTRAINT `tvrtka_ibfk_2` FOREIGN KEY (`tip`) REFERENCES `tiptvrtke` (`idTipa`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
