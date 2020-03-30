-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Gostitelj: 127.0.0.1
-- Čas nastanka: 08. nov 2019 ob 11.52
-- Različica strežnika: 10.1.34-MariaDB
-- Različica PHP: 7.2.7

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Zbirka podatkov: `semi01`
--
DROP DATABASE IF EXISTS `semi01`;
CREATE DATABASE IF NOT EXISTS `semi01` DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci;
USE `semi01`;

-- --------------------------------------------------------

--
-- Struktura tabele `dispozicija`
--
-- Ustvarjeno: 08. nov 2019 ob 10.39
--

CREATE TABLE IF NOT EXISTS `dispozicija` (
  `dispID` int(11) NOT NULL AUTO_INCREMENT,
  `nalogaID` int(11) NOT NULL,
  `tip` enum('teoretična','empirična','','') COLLATE utf8_slovenian_ci NOT NULL,
  `Naslov1` varchar(250) COLLATE utf8_slovenian_ci NOT NULL,
  `Naslov2` varchar(250) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `cilji` varchar(3000) COLLATE utf8_slovenian_ci DEFAULT NULL COMMENT 'vsaj 1500 znakov ! dovolj za 1/3 strani',
  `izhodisca` varchar(3000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `metodologija` varchar(3000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `viri` varchar(3000) COLLATE utf8_slovenian_ci DEFAULT NULL,
  `datum` int(11) NOT NULL COMMENT 'datum zadnje spremembe; revizij ni, ker je to preddokument',
  `podpisK` int(11) DEFAULT NULL,
  `zaklenjeno` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1-zaklenjeno, ne dopušča sprememb',
  PRIMARY KEY (`dispID`,`nalogaID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `kandidat`
--
-- Ustvarjeno: 08. nov 2019 ob 10.46
--

CREATE TABLE IF NOT EXISTS `kandidat` (
  `kandidatID` int(11) NOT NULL AUTO_INCREMENT,
  `ime` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `priimek` varchar(100) COLLATE utf8_slovenian_ci NOT NULL,
  `spol` int(11) NOT NULL,
  PRIMARY KEY (`kandidatID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

-- --------------------------------------------------------

--
-- Struktura tabele `naloga`
--
-- Ustvarjeno: 08. nov 2019 ob 10.51
--

CREATE TABLE IF NOT EXISTS `naloga` (
  `nalogaID` int(11) NOT NULL AUTO_INCREMENT,
  `klandidatID` int(11) NOT NULL,
  `naslov1` varchar(250) COLLATE utf8_slovenian_ci NOT NULL,
  `naslov2` varchar(250) COLLATE utf8_slovenian_ci DEFAULT NULL,
  PRIMARY KEY (`nalogaID`,`klandidatID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
