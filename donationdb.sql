-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 29, 2015 at 12:49 PM
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
-- Table structure for table `korisnik`
--

CREATE TABLE IF NOT EXISTS `korisnik` (
  `idKorisnika` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(160) NOT NULL,
  `password` varchar(160) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`idKorisnika`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tiptvrtke`
--

CREATE TABLE IF NOT EXISTS `tiptvrtke` (
  `idTipa` int(11) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(255) NOT NULL,
  `ikona` varchar(255) NOT NULL,
  PRIMARY KEY (`idTipa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `rating` double DEFAULT NULL,
  `kolPopusta` int(11) DEFAULT NULL,
  `vrijemePopusta` time DEFAULT NULL,
  `dostupno` tinyint(1) DEFAULT '0',
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  PRIMARY KEY (`idTvrtke`),
  KEY `idKorisnika` (`idKorisnika`,`tip`),
  KEY `tip` (`tip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tvrtka`
--
ALTER TABLE `tvrtka`
  ADD CONSTRAINT `tvrtka_ibfk_1` FOREIGN KEY (`idKorisnika`) REFERENCES `korisnik` (`idKorisnika`),
  ADD CONSTRAINT `tvrtka_ibfk_2` FOREIGN KEY (`tip`) REFERENCES `tiptvrtke` (`idTipa`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
