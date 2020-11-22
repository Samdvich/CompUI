SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE IF NOT EXISTS `spage65_CompUI` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `spage65_CompUI`;

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `house` varchar(12) NOT NULL,
  `type` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `password` binary(60) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `competitions`;
CREATE TABLE IF NOT EXISTS `competitions` (
  `eventID` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(50) DEFAULT NULL,
  `bilin bilin` int(11) NOT NULL,
  `barnes` int(11) NOT NULL,
  `francis` int(11) NOT NULL,
  `karle` int(11) NOT NULL,
  `visible` varchar(30) NOT NULL DEFAULT 'TRUE',
  PRIMARY KEY (`eventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

DROP TABLE IF EXISTS `competition_results`;
CREATE TABLE IF NOT EXISTS `competition_results` (
  `eventID` int(11) DEFAULT NULL,
  `student_name` varchar(60) NOT NULL,
  `house` varchar(11) NOT NULL,
  `time` time NOT NULL,
  KEY `fk_competition_results_eventID` (`eventID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `houses`;
CREATE TABLE IF NOT EXISTS `houses` (
  `house` varchar(11) NOT NULL,
  `members` int(11) NOT NULL,
  `colour` varchar(10) NOT NULL,
  PRIMARY KEY (`house`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `competition_results`
  ADD CONSTRAINT `fk_competition_results_eventID` FOREIGN KEY (`eventID`) REFERENCES `competitions` (`eventID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
