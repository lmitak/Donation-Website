-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2015 at 11:30 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

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
`id` int(11) NOT NULL,
  `cookie` varchar(65) NOT NULL,
  `commented` tinyint(1) NOT NULL DEFAULT '0',
  `rated` tinyint(1) NOT NULL DEFAULT '0',
  `idTvrtke` int(11) NOT NULL,
  `rateAmount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `antispamming`
--

INSERT INTO `antispamming` (`id`, `cookie`, `commented`, `rated`, `idTvrtke`, `rateAmount`) VALUES
(1, '$2y$10$ykzVrh78D/SayV/agWWXDONQJzhgFtb69aM1Sfh0w.frPhXc010pK', 0, 1, 24, 2),
(2, '$2y$10$K/XQO1IhVKia1il70U.PwOgVU.rwyTSR8fbQSRHzpnrOffBAcFMUG', 0, 1, 20, 3),
(3, '$2y$10$lpzhIlXKI8YuIXSCohLJZe0jYkbPrYskbpJ/MVq/GX4z9PDpskH7C', 0, 1, 19, 3),
(4, '$2y$10$rwpnlu14cCoDbmeySigDR.00lgYn..zYM5nCyZz8AfQLfqMp06tGq', 1, 1, 22, 5),
(5, '$2y$10$8eFwedxZrkLxNpI41FnCP.7TFIiNpAlD.jco1ag/M1ZVNmflPuE4m', 1, 1, 19, 4),
(6, '$2y$10$TDhjxbWwxnDOqpdexz.CuOVjtkr/xzhOu/y06Mhc8sPFt0L949ZC2', 1, 1, 18, 3),
(7, '$2y$10$wjDPtrX44vJP6o9iDaifm..e6J4qXBL0mrysRSCGBp3QaBHvPQLhS', 1, 1, 20, 4),
(8, '$2y$10$.BuaN9Se9t1u71/29bRwpORq1ZLX8vB9JtT/QisimJ.4K/KHKgzWm', 0, 1, 23, 3),
(9, '$2y$10$zsljgwLTDmjw4mOtJL2yBe8Y4gr6TVPYUVG1OUXIyl9G5dDxVwRBS', 1, 0, 19, 0),
(10, '$2y$10$8tY.GO/IANhmd.ufFp1Aje9pVRLxw1m0QdB/SwUDk5yRJKgqCSYOC', 1, 1, 15, 1),
(11, '$2y$10$HvUWjZkHeajf1BSzo0ZEhuForbsIP40AyHHjiH9FmNICDdk.YG9Qm', 1, 0, 24, 0),
(12, '$2y$10$5JS8S0Wt6ZpmDe7GCV8x3eRo8mptGSeTJNAF6iBms30x6l9Xn0yMK', 0, 1, 16, 5),
(13, '$2y$10$pbkY8VGjQb9b6prSCqR4o.Ypb0T9FCaD/dwk.Jk8N.s2.uDgZpTQK', 0, 1, 18, 2);

-- --------------------------------------------------------

--
-- Table structure for table `komentari`
--

CREATE TABLE IF NOT EXISTS `komentari` (
`id` int(11) NOT NULL,
  `idTvrtke` int(11) NOT NULL,
  `komentar` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `komentari`
--

INSERT INTO `komentari` (`id`, `idTvrtke`, `komentar`) VALUES
(1, 22, 'Hvala Vam! Izvrsni ste!'),
(2, 19, 'Hvala na skoro besplatnim pecivima! :)'),
(3, 18, 'Kupujte u konzumu! Isplati se !'),
(4, 20, 'Popusti skoro svaki dan, jako lijepo od vas'),
(5, 19, 'Mislim da je jako lijepo od vas što darujete hranu! Marica'),
(6, 15, 'Meso je puno trakavica. FUJ !'),
(7, 24, 'Veoma povoljno i kvalitetno voće i povrće');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE IF NOT EXISTS `korisnik` (
`id` int(11) NOT NULL,
  `username` varchar(160) NOT NULL,
  `password` varchar(160) NOT NULL,
  `email` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `username`, `password`, `email`, `isAdmin`) VALUES
(2, 'ivan', '$2y$10$IGnAlRGsfNgo3CIBBZ51jOpWeRsZuvF8DYdlASNBwoQvCBJdh3u72', 'ivan_nikolic@net.hr', 1),
(16, 'marko', '$2y$10$amq4iSOWa7F0PDZGo7BOzehS.lXAu6VS9x0.vPW7aIzF2R.x/iq.i', 'bjferg2@att.net', 0),
(17, 'petar221', '$2y$10$.0DZ5NQ1bJnOW/gFefEA1O8Wfsy5ibwLQrs2bXEFqDL3DivjmsxBO', 'ivan.nikolic511@gmail.com', 0),
(18, 'matija', '$2y$10$4qNYKu0dF5cogRqiAeoPUePhJ3S1jLodO/nzK7A7PmchpiPS/AXdS', 'dadsidea@verizon.net', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tiptvrtke`
--

CREATE TABLE IF NOT EXISTS `tiptvrtke` (
`idTipa` int(11) NOT NULL,
  `naziv` varchar(255) NOT NULL,
  `ikona` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

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
`id` int(11) NOT NULL,
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
  `web` varchar(255) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tvrtka`
--

INSERT INTO `tvrtka` (`id`, `idKorisnika`, `naziv`, `tip`, `adresa`, `rating`, `kolPopusta`, `vrijemePopusta`, `dostupno`, `latitude`, `longitude`, `datumPrijave`, `web`) VALUES
(15, 16, 'Mesnica Tvrtko', 2, 'Trg bana Josipa Jelačića, Zagreb, Hrvatska', 0.5, 20, '20:00:00', 0, 45.8128451, 15.97749490000001, '2015-06-14', ''),
(16, 16, 'Voćko', 3, 'Klaićeva ulica 2, Zagreb, Hrvatska', 2.5, 99, '22:00:00', 0, 45.8092936, 15.961933799999997, '2015-06-14', ''),
(17, 16, 'Klas', 1, 'Frankopanska ulica 122, Zagreb, Hrvatska', 0, 99, '11:00:00', 0, 45.8116685, 15.969426999999996, '2015-06-14', ''),
(18, 16, 'Konzum', 4, 'Šalata ulica 2, Zagreb, Hrvatska', 1.75, 50, '20:00:00', 0, 45.8175687, 15.984410199999957, '2015-06-14', ''),
(19, 16, 'Klas', 1, 'Vlaška ulica 5, Zagreb, Hrvatska', 2.75, 50, '19:30:00', 0, 45.81375250000001, 15.98758799999996, '2015-06-14', ''),
(20, 16, 'Interspar', 4, 'Kaptol ulica 10, Zagreb, Hrvatska', 2.75, 25, '20:00:00', 0, 45.8163883, 15.978343600000017, '2015-06-14', ''),
(21, 18, 'Jabuka', 3, 'Draškovićeva ulica 10, Zagreb, Hrvatska', 0, 50, '23:00:00', 0, 45.8098501, 15.983681599999954, '2015-06-14', ''),
(22, 18, 'Bulls', 2, 'Ulica Nikole Jurišića 5, Zagreb, Hrvatska', 2.5, 50, '20:00:00', 0, 45.8123241, 15.980974199999991, '2015-06-14', ''),
(23, 18, 'Plodine', 4, 'Tkalčićeva ulica 1, Zagreb, Hrvatska', 1.5, 90, '21:00:00', 0, 45.8177049, 15.97625800000003, '2015-06-14', ''),
(24, 18, 'Dolac', 3, 'Dolac, Zagreb, Hrvatska', 1, 70, '16:00:00', 0, 45.8139846, 15.977510400000028, '2015-06-14', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antispamming`
--
ALTER TABLE `antispamming`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `cookie` (`cookie`), ADD KEY `idTvrtke` (`idTvrtke`);

--
-- Indexes for table `komentari`
--
ALTER TABLE `komentari`
 ADD PRIMARY KEY (`id`), ADD KEY `idTvrtke` (`idTvrtke`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tiptvrtke`
--
ALTER TABLE `tiptvrtke`
 ADD PRIMARY KEY (`idTipa`);

--
-- Indexes for table `tvrtka`
--
ALTER TABLE `tvrtka`
 ADD PRIMARY KEY (`id`), ADD KEY `idKorisnika` (`idKorisnika`,`tip`), ADD KEY `tip` (`tip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `antispamming`
--
ALTER TABLE `antispamming`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `komentari`
--
ALTER TABLE `komentari`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `tiptvrtke`
--
ALTER TABLE `tiptvrtke`
MODIFY `idTipa` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tvrtka`
--
ALTER TABLE `tvrtka`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `antispamming`
--
ALTER TABLE `antispamming`
ADD CONSTRAINT `antispamming_ibfk_1` FOREIGN KEY (`idTvrtke`) REFERENCES `tvrtka` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `komentari`
--
ALTER TABLE `komentari`
ADD CONSTRAINT `komentari_ibfk_1` FOREIGN KEY (`idTvrtke`) REFERENCES `tvrtka` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tvrtka`
--
ALTER TABLE `tvrtka`
ADD CONSTRAINT `tvrtka_ibfk_1` FOREIGN KEY (`idKorisnika`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `tvrtka_ibfk_2` FOREIGN KEY (`tip`) REFERENCES `tiptvrtke` (`idTipa`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
