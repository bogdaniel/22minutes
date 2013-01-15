-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 27, 2012 at 03:20 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `twelve`
--

-- --------------------------------------------------------

--
-- Table structure for table `sessiondata`
--

CREATE TABLE IF NOT EXISTS `sessiondata` (
  `sessionId` varchar(32) NOT NULL DEFAULT '',
  `httpUserAgent` varchar(32) NOT NULL DEFAULT '',
  `sessionValue` blob NOT NULL,
  `sessionExpire` datetime NOT NULL,
  PRIMARY KEY (`sessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessiondata`
--

INSERT INTO `sessiondata` (`sessionId`, `httpUserAgent`, `sessionValue`, `sessionExpire`) VALUES
('kd4drmr4vhkaiif991ct9ci643', '4a94daffa9075a6711b8391a3a4020d4', 0x6c6f67696e7c733a313a2231223b, '2012-10-04 16:18:24');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
