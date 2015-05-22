-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2015 at 03:30 PM
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
  `rating` tinyint(4) DEFAULT NULL,
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
--
-- Database: `ods_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alergeni`
--

CREATE TABLE IF NOT EXISTS `alergeni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(25) COLLATE cp1250_croatian_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1250 COLLATE=cp1250_croatian_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `alergeni`
--

INSERT INTO `alergeni` (`id`, `naziv`) VALUES
(1, 'soja'),
(2, 'jaja'),
(3, 'kikiriki'),
(4, 'mlijeko'),
(5, 'rakovi'),
(6, 'školjke'),
(7, 'orašasti plodovi'),
(8, 'jagode'),
(9, 'kivi'),
(10, 'ananas'),
(11, 'med');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE IF NOT EXISTS `korisnik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `us` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_croatian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `us`, `pw`, `name`) VALUES
(1, 'luka', '123', 'Luka');

-- --------------------------------------------------------

--
-- Table structure for table `sheet1`
--

CREATE TABLE IF NOT EXISTS `sheet1` (
  `NazivProizvoda` varchar(29) DEFAULT NULL,
  `TipProizvoda` int(11) DEFAULT NULL,
  `OpisProizvoda` varchar(46) DEFAULT NULL,
  `Vegetarijanski` varchar(2) DEFAULT NULL,
  `Halal` varchar(2) DEFAULT NULL,
  `Košer` varchar(2) DEFAULT NULL,
  `Alergeni` int(11) DEFAULT NULL,
  `Cijena` float DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `TipProizvoda` (`TipProizvoda`),
  KEY `Alergeni` (`Alergeni`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `sheet1`
--

INSERT INTO `sheet1` (`NazivProizvoda`, `TipProizvoda`, `OpisProizvoda`, `Vegetarijanski`, `Halal`, `Košer`, `Alergeni`, `Cijena`, `id`) VALUES
('Gibanica', 1, 'Ovo je slani tip kolača, punjena je orasima', 'NE', 'NE', 'NE', 5, 10, 1),
('Sirnica', 1, 'Ovo je slani tip kolača, punjen sirom', 'NE', 'NE', 'NE', 2, 12, 2),
('Burek', 6, 'Ovo je slani tip kolača, punjen mesom', 'NE', 'NE', 'NE', NULL, 14, 3),
('Sacher torta', 2, 'Čokoladna torta u više slojeva', 'DA', 'DA', 'DA', 7, 16, 4),
('Mađarica', 1, 'Čokoladni tip torte u više slojeva, s korama', 'NE', 'NE', 'NE', 5, 10, 5),
('Kremšnita', 1, 'Slatki kolač', 'NE', 'NE', 'NE', 1, 15, 6),
('Šampita', 1, 'Neam pojma', 'NE', 'NE', 'NE', 10, 12, 7),
('Piškote', 3, 'valjda keksi', 'NE', 'NE', 'NE', 2, 20, 8),
('Čokoladni keksi – čisti', 3, 'Slatki kolač', 'NE', 'DA', 'DA', NULL, 35, 9),
('Čokoladni keksi – brusnice', 1, 'Slatki kolač', 'NE', 'NE', 'NE', NULL, 10, 10),
('Voćni kup', 1, 'Voćni kolač', 'NE', 'NE', 'NE', NULL, 12, 11),
('Čokolada s čilijem', 1, 'Neam pojma', 'NE', 'NE', 'NE', NULL, 14, 12),
('Čokolada s citrusnim aromama', 1, 'Nekaj čudno', 'NE', 'NE', 'NE', NULL, 16, 13),
('Belgijske praline', 4, 'praline', 'NE', 'DA', 'DA', NULL, 10, 14),
('Praline s konjakom', 4, 'praline', 'NE', 'NE', 'NE', NULL, 15, 15),
('Macarons', 3, 'nekakvi keksi', 'NE', 'NE', 'NE', 1, 12, 16),
('Croisant', 6, 'pecivo', 'NE', 'NE', 'NE', 2, 20, 17),
('Banana split', 1, 'sladoled', 'NE', 'NE', 'NE', 4, 35, 18),
('Ganache torta', 2, 'Čokoladna torta u više slojeva', 'NE', 'DA', 'DA', NULL, 10, 19),
('ZKD torta', 2, 'Čokoladna torta u više slojeva', 'NE', 'NE', 'NE', NULL, 12, 20),
('Voćna torta', 2, 'Voćna torta u više slojeva', 'NE', 'NE', 'NE', 10, 14, 21),
('Tiramisu', 1, 'Neam pojma', 'NE', 'NE', 'NE', 4, 16, 22),
('Crne kocke', 1, 'Neam pojma', 'NE', 'NE', 'NE', 1, 10, 23),
('Kesten štapić', 3, 'štapi', 'NE', 'DA', 'DA', 7, 15, 24),
('Kesten šnita', 1, 'šnita', 'NE', 'NE', 'NE', 7, 12, 25),
('Kokos štangice', 6, 'Pecivo', 'NE', 'NE', 'NE', 2, 20, 26),
('Palice', 6, 'Pendrek', 'NE', 'NE', 'NE', 4, 35, 27),
('Bananko', 4, 'Čokoladna banana', 'NE', 'NE', 'NE', 4, 10, 28),
('Breskvice', 1, 'Slatki kolač', 'NE', 'DA', 'DA', 2, 12, 29),
('Čupavci', 1, 'Slatki kolač', 'NE', 'NE', 'NE', NULL, 14, 30),
('Čokoladni mousse', 4, 'Neam pojma', 'NE', 'NE', 'NE', 4, 16, 31),
('Išler', 6, 'Neam pojma', 'NE', 'NE', 'NE', 6, 10, 32),
('Lambada', 1, 'Ples', 'NE', 'NE', 'NE', 8, 15, 33),
('Medenjaci', 1, 'Slatki kolač', 'NE', 'DA', 'DA', 11, 12, 34),
('Rafaelo kuglice', 1, 'Slatki kolač', 'NE', 'NE', 'NE', 7, 20, 35),
('Šubare', 1, 'Neam pojma', 'NE', 'NE', 'NE', 4, 35, 36),
('Iločki Traminac', 6, 'Neam pojma', 'NE', 'NE', 'NE', 5, 29, 37);

-- --------------------------------------------------------

--
-- Table structure for table `tipovi_podataka`
--

CREATE TABLE IF NOT EXISTS `tipovi_podataka` (
  `TipoviDelicija` varchar(9) NOT NULL,
  `tip_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`tip_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tipovi_podataka`
--

INSERT INTO `tipovi_podataka` (`TipoviDelicija`, `tip_id`) VALUES
('Kolač', 1),
('Torta', 2),
('Keks', 3),
('Čokolada', 4),
('Piće', 5),
('Ostalo', 6);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sheet1`
--
ALTER TABLE `sheet1`
  ADD CONSTRAINT `sheet1_ibfk_1` FOREIGN KEY (`TipProizvoda`) REFERENCES `tipovi_podataka` (`tip_id`),
  ADD CONSTRAINT `sheet1_ibfk_2` FOREIGN KEY (`Alergeni`) REFERENCES `alergeni` (`id`);
--
-- Database: `test`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
