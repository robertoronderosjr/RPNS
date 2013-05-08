-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 08, 2013 at 03:19 AM
-- Server version: 5.1.42
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `RPNS`
--

-- --------------------------------------------------------

--
-- Table structure for table `Course`
--

CREATE TABLE IF NOT EXISTS `Course` (
  `C_ID` int(11) NOT NULL AUTO_INCREMENT,
  `M_ID` int(11) DEFAULT NULL,
  `Name` varchar(45) NOT NULL,
  `Managed_By` varchar(11) DEFAULT NULL,
  `Creation_Date` date NOT NULL,
  PRIMARY KEY (`C_ID`),
  KEY `Makor ID C_idx` (`M_ID`),
  KEY `Managed_By` (`Managed_By`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=147 ;

-- --------------------------------------------------------

--
-- Table structure for table `Course_Offering`
--

CREATE TABLE IF NOT EXISTS `Course_Offering` (
  `CO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `C_ID` int(11) NOT NULL,
  `Semester` varchar(50) NOT NULL,
  `MAX_SIZE` int(11) NOT NULL,
  `CurrentSize` int(11) NOT NULL,
  PRIMARY KEY (`CO_ID`),
  UNIQUE KEY `C_ID_UNIQUE` (`C_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=118 ;

-- --------------------------------------------------------

--
-- Table structure for table `Course_Requirements`
--

CREATE TABLE IF NOT EXISTS `Course_Requirements` (
  `Requirer_ID` int(11) NOT NULL,
  `Requirement_ID` int(11) NOT NULL,
  PRIMARY KEY (`Requirer_ID`,`Requirement_ID`),
  KEY `Requirer ID_idx` (`Requirer_ID`),
  KEY `Requirement ID_idx` (`Requirement_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Course_Section`
--

CREATE TABLE IF NOT EXISTS `Course_Section` (
  `CS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CO_ID` int(11) NOT NULL,
  `Section_Number` int(11) NOT NULL,
  `Teached_By` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`CS_ID`),
  KEY `Course Offering CS_idx` (`CO_ID`),
  KEY `Teached_By_Prof` (`Teached_By`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=291 ;

-- --------------------------------------------------------

--
-- Table structure for table `Department`
--

CREATE TABLE IF NOT EXISTS `Department` (
  `D_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_ID` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`D_ID`),
  KEY `university ID D_idx` (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `Department`
--

INSERT INTO `Department` (`D_ID`, `U_ID`, `Name`) VALUES
(1, 1, 'School of Arts and Sciences'),
(3, 1, 'Animal Science');

-- --------------------------------------------------------

--
-- Table structure for table `Enrolls`
--

CREATE TABLE IF NOT EXISTS `Enrolls` (
  `User_ID` int(11) NOT NULL,
  `University_ID` int(11) NOT NULL,
  `Start_Date` date NOT NULL,
  PRIMARY KEY (`User_ID`),
  KEY `University ID E_idx` (`University_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Majors`
--

CREATE TABLE IF NOT EXISTS `Majors` (
  `M_ID` int(11) NOT NULL AUTO_INCREMENT,
  `D_ID` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL,
  PRIMARY KEY (`M_ID`),
  UNIQUE KEY `Name` (`Name`),
  KEY `Department ID_idx` (`D_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `Majors`
--

INSERT INTO `Majors` (`M_ID`, `D_ID`, `Name`) VALUES
(1, 1, 'Computer Science'),
(2, 1, 'Electrical Engineer'),
(16, 3, 'Defense'),
(18, 1, 'Graphics'),
(20, 1, 'Computer Programming');

-- --------------------------------------------------------

--
-- Table structure for table `Majors_In`
--

CREATE TABLE IF NOT EXISTS `Majors_In` (
  `U_ID` int(11) NOT NULL,
  `M_ID` int(11) NOT NULL,
  `Start_Date` date NOT NULL,
  PRIMARY KEY (`U_ID`,`M_ID`),
  KEY `User ID MI_idx` (`U_ID`),
  KEY `Major ID MI_idx` (`M_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Minors_In`
--

CREATE TABLE IF NOT EXISTS `Minors_In` (
  `U_ID` int(11) NOT NULL,
  `M_ID` int(11) NOT NULL,
  `Start_Date` date NOT NULL,
  PRIMARY KEY (`U_ID`,`M_ID`),
  KEY `User ID MinorsIN_idx` (`U_ID`),
  KEY `Major ID MinorsIN_idx` (`M_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Notifications`
--

CREATE TABLE IF NOT EXISTS `Notifications` (
  `N_ID` int(11) NOT NULL AUTO_INCREMENT,
  `C_ID` int(11) DEFAULT NULL,
  `CO_ID` int(11) DEFAULT NULL,
  `CS_ID` int(11) DEFAULT NULL,
  `To_NetID` varchar(11) NOT NULL,
  `Message` varchar(255) NOT NULL,
  `Action` int(10) NOT NULL,
  `Datestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`N_ID`),
  KEY `NetIDNotf` (`To_NetID`),
  KEY `C_ID` (`C_ID`),
  KEY `CO_ID` (`CO_ID`),
  KEY `CS_ID` (`CS_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=174 ;

-- --------------------------------------------------------

--
-- Table structure for table `Permissions`
--

CREATE TABLE IF NOT EXISTS `Permissions` (
  `PN_ID` int(11) NOT NULL AUTO_INCREMENT,
  `CS_ID` int(11) NOT NULL,
  `P_Number` int(11) NOT NULL,
  `Available` char(1) NOT NULL,
  PRIMARY KEY (`PN_ID`),
  KEY `Course_SectionID` (`CS_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Table structure for table `Prof_Course_Requirements`
--

CREATE TABLE IF NOT EXISTS `Prof_Course_Requirements` (
  `PCR_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(45) NOT NULL,
  `Rank` int(255) NOT NULL,
  `Values` varchar(21844) DEFAULT NULL,
  `CO_ID` int(11) NOT NULL,
  `Prof_ID` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`PCR_ID`),
  KEY `Profesor ID PCR_idx` (`Prof_ID`),
  KEY `CO_ID` (`CO_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=321 ;

-- --------------------------------------------------------

--
-- Table structure for table `Prof_P#_Assigns`
--

CREATE TABLE IF NOT EXISTS `Prof_P#_Assigns` (
  `PA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Assigner` varchar(11) NOT NULL,
  `Assignee` varchar(11) NOT NULL,
  `CO_ID` int(11) NOT NULL,
  `CS_ID` int(11) NOT NULL,
  `Permission_N` int(20) NOT NULL,
  `Comments` text,
  `Assignation_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Expiration_Date` date NOT NULL,
  PRIMARY KEY (`PA_ID`),
  KEY `Assigner` (`Assigner`),
  KEY `Assignee` (`Assignee`),
  KEY `CO_ID` (`CO_ID`),
  KEY `CS_ID` (`CS_ID`),
  KEY `Permission_N` (`Permission_N`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `Student_Course_Sections`
--

CREATE TABLE IF NOT EXISTS `Student_Course_Sections` (
  `U_ID` int(11) NOT NULL,
  `CS_ID` int(11) NOT NULL,
  PRIMARY KEY (`U_ID`,`CS_ID`),
  KEY `User ID SCS_idx` (`U_ID`),
  KEY `Course Section SCS_idx` (`CS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Student_P#_Request`
--

CREATE TABLE IF NOT EXISTS `Student_P#_Request` (
  `PR_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NetID` varchar(11) NOT NULL,
  `CO_ID` int(11) NOT NULL,
  `CS_ID` int(11) NOT NULL,
  `Score` varchar(255) NOT NULL,
  `Responses` text NOT NULL,
  `Date` date NOT NULL,
  `Status` varchar(45) NOT NULL,
  `Active` char(1) NOT NULL,
  PRIMARY KEY (`PR_ID`),
  KEY `NetID` (`NetID`),
  KEY `CO_ID` (`CO_ID`),
  KEY `CS_ID` (`CS_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Table structure for table `Universities`
--

CREATE TABLE IF NOT EXISTS `Universities` (
  `U_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Address` varchar(45) NOT NULL,
  `Phone_Number` varchar(15) NOT NULL,
  PRIMARY KEY (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `Universities`
--

INSERT INTO `Universities` (`U_ID`, `Name`, `Address`, `Phone_Number`) VALUES
(1, 'Rutgers, The State University of New Jersey', '83 Somerset St, New Brunswick, NJ 08901', '(732) 445-4636');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
  `U_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(45) NOT NULL,
  `Last_Name` varchar(45) NOT NULL,
  `Gender` char(1) DEFAULT NULL,
  `Email` varchar(45) NOT NULL,
  `DOB` date DEFAULT NULL,
  `NetID` varchar(10) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `User_Type` char(1) NOT NULL,
  `GPA` decimal(2,1) DEFAULT NULL,
  `Expected_Grad_Date` date DEFAULT NULL,
  PRIMARY KEY (`U_ID`),
  UNIQUE KEY `NetID` (`NetID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`U_ID`, `Name`, `Last_Name`, `Gender`, `Email`, `DOB`, `NetID`, `Password`, `User_Type`, `GPA`, `Expected_Grad_Date`) VALUES
(22, 'student', 'student', 'm', 'noreply@noreply.com', '2013-05-07', 'student', 'students', '1', 4.0, '2013-05-31'),
(25, 'Catalina', 'Laverde', 'f', 'catalavdu@gmail.com', '1991-11-08', 'cl750', '$2a$08$dG12nc641cagihdqGyNSCufvw3pmSDOiZWF4WEntCSHXfrQdPzz5q', '2', NULL, NULL),
(26, 'Roberto ', 'Ronderos', 'm', 'robertoronderosjr@msn.com', '1989-11-06', 'rrr82', '$2a$08$IcX0i.yw9Ih/ezFVdeau0eZtNfcFGPrYa/8sTP30ZY2kR03/E5p2e', '2', NULL, NULL),
(27, 'Molly', 'Ronderos', 'f', 'catalavdu@gmail.com', '2011-08-26', 'mrl', '$2a$08$r/r8j6LPIbZCpdahBGBYSOJuhZMZ1qiAbkkq1bVJY39hOTZ.KRAEa', '1', NULL, NULL),
(28, 'Doddy', 'Ronderos', 'm', 'robertoronderosjr@msn.com', '2010-02-09', 'doddy', '$2a$08$50wA/QaTzqIjsHm8n7smEuc30q6HN2bp6v22QzGd3L77YZxgpjmua', '1', NULL, NULL),
(29, 'Lulu', 'Duarte', 'f', 'catalavdu@gmail.com', '2011-03-24', 'lulu', '$2a$08$WNbbDUhtrH7vo27bMHs/Ou9zDcQPGV3/myv30v2I8b/Gr9GIxjeBq', '1', NULL, NULL),
(30, 'professor', 'x', 'm', 'robertoronderosjr@msn.com', '2010-02-02', 'rrr', '$2a$08$wvpZ.DWkdj5rcxrTxy8KA./rMcguSMdS.gsmOHdhjBVjNKbV7e11y', '2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Works_In`
--

CREATE TABLE IF NOT EXISTS `Works_In` (
  `User_ID` int(11) NOT NULL,
  `University_ID` int(11) NOT NULL,
  `Start_Date` date NOT NULL,
  PRIMARY KEY (`User_ID`),
  KEY `User ID WI_idx` (`User_ID`),
  KEY `University ID_idx` (`University_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Course`
--
ALTER TABLE `Course`
  ADD CONSTRAINT `Course_ibfk_1` FOREIGN KEY (`Managed_By`) REFERENCES `User` (`NetID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Major ID C` FOREIGN KEY (`M_ID`) REFERENCES `Majors` (`M_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Course_Offering`
--
ALTER TABLE `Course_Offering`
  ADD CONSTRAINT `Course ID CO` FOREIGN KEY (`C_ID`) REFERENCES `Course` (`C_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Course_Requirements`
--
ALTER TABLE `Course_Requirements`
  ADD CONSTRAINT `Course_Requirements_ibfk_3` FOREIGN KEY (`Requirement_ID`) REFERENCES `Course` (`C_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Requirer ID` FOREIGN KEY (`Requirer_ID`) REFERENCES `Course` (`C_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Course_Section`
--
ALTER TABLE `Course_Section`
  ADD CONSTRAINT `Course Offering CS` FOREIGN KEY (`CO_ID`) REFERENCES `Course_Offering` (`CO_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Teached_By_Prof` FOREIGN KEY (`Teached_By`) REFERENCES `User` (`NetID`);

--
-- Constraints for table `Department`
--
ALTER TABLE `Department`
  ADD CONSTRAINT `university ID D` FOREIGN KEY (`U_ID`) REFERENCES `Universities` (`U_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Enrolls`
--
ALTER TABLE `Enrolls`
  ADD CONSTRAINT `User ID E` FOREIGN KEY (`User_ID`) REFERENCES `User` (`U_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `University ID E` FOREIGN KEY (`University_ID`) REFERENCES `Universities` (`U_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Majors`
--
ALTER TABLE `Majors`
  ADD CONSTRAINT `Department ID` FOREIGN KEY (`D_ID`) REFERENCES `Department` (`D_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Majors_In`
--
ALTER TABLE `Majors_In`
  ADD CONSTRAINT `User ID MI` FOREIGN KEY (`U_ID`) REFERENCES `User` (`U_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Major ID MI` FOREIGN KEY (`M_ID`) REFERENCES `Majors` (`M_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Minors_In`
--
ALTER TABLE `Minors_In`
  ADD CONSTRAINT `User ID MinorsIN` FOREIGN KEY (`U_ID`) REFERENCES `User` (`U_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Major ID MinorsIN` FOREIGN KEY (`M_ID`) REFERENCES `Majors` (`M_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Notifications`
--
ALTER TABLE `Notifications`
  ADD CONSTRAINT `Notifications_ibfk_1` FOREIGN KEY (`To_NetID`) REFERENCES `User` (`NetID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Notifications_ibfk_2` FOREIGN KEY (`C_ID`) REFERENCES `Course` (`C_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Notifications_ibfk_3` FOREIGN KEY (`CO_ID`) REFERENCES `Course_Offering` (`CO_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Notifications_ibfk_4` FOREIGN KEY (`CS_ID`) REFERENCES `Course_Section` (`CS_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Permissions`
--
ALTER TABLE `Permissions`
  ADD CONSTRAINT `Permissions_ibfk_1` FOREIGN KEY (`CS_ID`) REFERENCES `Course_Section` (`CS_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Prof_Course_Requirements`
--
ALTER TABLE `Prof_Course_Requirements`
  ADD CONSTRAINT `Prof_Course_Requirements_ibfk_2` FOREIGN KEY (`CO_ID`) REFERENCES `Course_Section` (`CO_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Prof_Course_Requirements_ibfk_1` FOREIGN KEY (`Prof_ID`) REFERENCES `User` (`NetID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Prof_P#_Assigns`
--
ALTER TABLE `Prof_P#_Assigns`
  ADD CONSTRAINT `Prof_P@0023_Assigns_ibfk_1` FOREIGN KEY (`Assigner`) REFERENCES `User` (`NetID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Prof_P@0023_Assigns_ibfk_2` FOREIGN KEY (`Assignee`) REFERENCES `User` (`NetID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Prof_P@0023_Assigns_ibfk_3` FOREIGN KEY (`CO_ID`) REFERENCES `Course_Offering` (`CO_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Prof_P@0023_Assigns_ibfk_4` FOREIGN KEY (`CS_ID`) REFERENCES `Course_Section` (`CS_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Student_Course_Sections`
--
ALTER TABLE `Student_Course_Sections`
  ADD CONSTRAINT `User ID SCS` FOREIGN KEY (`U_ID`) REFERENCES `User` (`U_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Course Section SCS` FOREIGN KEY (`CS_ID`) REFERENCES `Course_Section` (`CS_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Student_P#_Request`
--
ALTER TABLE `Student_P#_Request`
  ADD CONSTRAINT `Student_P@0023_Request_ibfk_1` FOREIGN KEY (`NetID`) REFERENCES `User` (`NetID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Student_P@0023_Request_ibfk_2` FOREIGN KEY (`CO_ID`) REFERENCES `Course_Offering` (`CO_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Student_P@0023_Request_ibfk_3` FOREIGN KEY (`CS_ID`) REFERENCES `Course_Section` (`CS_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Works_In`
--
ALTER TABLE `Works_In`
  ADD CONSTRAINT `User ID WI` FOREIGN KEY (`User_ID`) REFERENCES `User` (`U_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `University ID` FOREIGN KEY (`University_ID`) REFERENCES `Universities` (`U_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
