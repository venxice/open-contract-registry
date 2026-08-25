-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 24, 2026 at 05:56 AM
-- Server version: 9.7.1
-- PHP Version: 8.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblbidding`
--

CREATE TABLE `tblbidding` (
  `bidding_id` int NOT NULL,
  `contractor` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `notice_date` date DEFAULT NULL,
  `contract_number` varchar(100) DEFAULT NULL,
  `contract_date` date DEFAULT NULL,
  `notice_proceed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblbidding_project`
--

CREATE TABLE `tblbidding_project` (
  `project_id` int NOT NULL,
  `bidding_id` int NOT NULL,
  `project_title` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblbidding_project_description`
--

CREATE TABLE `tblbidding_project_description` (
  `description_id` int NOT NULL,
  `project_id` int NOT NULL,
  `project_description` text NOT NULL,
  `date_posted` date DEFAULT NULL,
  `project_attachment` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_initial` varchar(255) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblbidding`
--
ALTER TABLE `tblbidding`
  ADD PRIMARY KEY (`bidding_id`);

--
-- Indexes for table `tblbidding_project`
--
ALTER TABLE `tblbidding_project`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `fk_project_bidding` (`bidding_id`);

--
-- Indexes for table `tblbidding_project_description`
--
ALTER TABLE `tblbidding_project_description`
  ADD PRIMARY KEY (`description_id`),
  ADD KEY `fk_description_project` (`project_id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblbidding`
--
ALTER TABLE `tblbidding`
  MODIFY `bidding_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblbidding_project`
--
ALTER TABLE `tblbidding_project`
  MODIFY `project_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblbidding_project_description`
--
ALTER TABLE `tblbidding_project_description`
  MODIFY `description_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblbidding_project`
--
ALTER TABLE `tblbidding_project`
  ADD CONSTRAINT `fk_project_bidding` FOREIGN KEY (`bidding_id`) REFERENCES `tblbidding` (`bidding_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tblbidding_project_description`
--
ALTER TABLE `tblbidding_project_description`
  ADD CONSTRAINT `fk_description_project` FOREIGN KEY (`project_id`) REFERENCES `tblbidding_project` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
