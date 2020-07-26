-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 26, 2020 at 06:44 PM
-- Server version: 8.0.20-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Ordering` int NOT NULL,
  `Visibility` tinyint NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint NOT NULL DEFAULT '0',
  `ParentID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`, `ParentID`) VALUES
(1, 'Pc', 'PC and related fields', 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `ID` int NOT NULL,
  `Comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `AddedDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Status` tinyint NOT NULL DEFAULT '0',
  `UserID` int NOT NULL,
  `ItemID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`ID`, `Comment`, `AddedDate`, `Status`, `UserID`, `ItemID`) VALUES
(2, 'Hello', '2020-07-25 21:00:00', 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `ItemID` int NOT NULL,
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Price` int NOT NULL,
  `AddDate` date NOT NULL,
  `CountryMade` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Status` int NOT NULL,
  `Approve` tinyint NOT NULL DEFAULT '0',
  `Tags` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `CatID` int NOT NULL,
  `MemberID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`ItemID`, `Name`, `Description`, `Price`, `AddDate`, `CountryMade`, `Status`, `Approve`, `Tags`, `CatID`, `MemberID`) VALUES
(1, 'Mouse', 'lenovo legion amazing', 100, '2020-07-26', 'Middle east', 1, 1, 'pc', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int NOT NULL,
  `Username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `Fullname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `GroupID` tinyint DEFAULT '0',
  `Regstatus` tinyint NOT NULL DEFAULT '0',
  `Regsdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `Fullname`, `GroupID`, `Regstatus`, `Regsdate`) VALUES
(1, 'suhaib', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'sohib@sohib.com', 'Suhaib Khater', 1, 1, '2020-07-25 21:00:00'),
(2, 'Ali', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'ali@ali.com', 'Suhaib khater', NULL, 1, '2020-07-26 15:27:59'),
(3, 'ahmad', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'ali@ali.com', NULL, 0, 0, '2020-07-25 21:00:00'),
(4, 'Mouse', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'ali@ali.com', NULL, 0, 0, '2020-07-25 21:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `itemid` (`ItemID`),
  ADD KEY `userid` (`UserID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemID`),
  ADD KEY `memberid` (`MemberID`),
  ADD KEY `catid` (`CatID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `ItemID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `itemid` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userid` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `catid` FOREIGN KEY (`CatID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `memberid` FOREIGN KEY (`MemberID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
