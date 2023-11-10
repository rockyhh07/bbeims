-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2023 at 02:05 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bbeims_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `evacuee`
--

CREATE TABLE `evacuee` (
  `id` int(6) NOT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `mname` varchar(50) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(1) DEFAULT 'N',
  `civil_status` varchar(20) DEFAULT 'SINGLE',
  `address` varchar(255) DEFAULT NULL,
  `representative` int(6) DEFAULT NULL,
  `evac_id` int(6) DEFAULT NULL,
  `incident_id` int(6) DEFAULT NULL,
  `incident_date` datetime DEFAULT NULL,
  `deletedflag` int(1) DEFAULT 0,
  `updated_by` int(6) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evacuee`
--

INSERT INTO `evacuee` (`id`, `lname`, `fname`, `mname`, `contact`, `birthday`, `gender`, `civil_status`, `address`, `representative`, `evac_id`, `incident_id`, `incident_date`, `deletedflag`, `updated_by`, `updated_date`, `created_by`, `created_date`) VALUES
(1, 'AZARRAGA', 'GEMMA', 'DELGADO', '09184975699', '1974-08-15', 'F', 'M', '949 MRR TRACK ST., BRGY. BARANGKA IBABA', 1, 1, 1, '2023-11-03 22:33:00', 0, 1, '2023-11-07 21:11:50', 1, '2023-11-03 20:23:38'),
(2, 'AZARRAGA', 'JEFFREY', 'GOMID', '09101448553', '1970-09-29', 'M', 'M', '949 MRR TRACK ST., BRGY. BARANGKA IBABA, MANDALUYONG CITY', 1, 1, 1, '2023-11-03 22:33:00', 1, 1, '2023-11-09 21:00:29', 1, '2023-11-03 20:24:43'),
(3, 'AZARRAGA', 'ANGEL', 'DELGADO', '09772177092', '2001-07-30', 'F', 'W', '949 MRR TRACK ST., BRGY. BARANGKA IBABA, MANDALUYONG CITY', 1, 1, 1, '2023-11-03 22:33:00', 1, 1, '2023-11-09 21:00:28', 1, '2023-11-03 20:25:16'),
(4, 'AZARRAGA', 'ANDREA', 'DELGADO', '09123456789', '2004-10-15', 'F', 'S', '949 MRR TRACK ST., BRGY. BARANGKA IBABA, MANDALUYONG CITY', 1, 1, 1, '2023-11-03 22:33:00', 1, 1, '2023-11-09 21:00:26', 1, '2023-11-03 20:25:51'),
(5, 'AZARRAGA', 'ALDEN', 'DELGADO', '09184975699', '2007-05-27', 'M', 'S', '949 MRR TRACK ST., BRGY. BARANGKA IBABA, MANDALUYONG CITY', 1, 1, 1, '2023-11-03 22:33:00', 1, 1, '2023-11-09 21:00:24', 1, '2023-11-03 20:26:26'),
(6, 'AZARRAGA', 'ALLONAH', 'DELGADO', '091844975699', '2009-01-06', 'F', 'S', '949 MRR TRACK ST., BRGY. BARANGKA IBABA, MANDALUYONG CITY', 1, 1, 1, '2023-11-03 22:33:00', 1, 1, '2023-11-09 21:00:22', 1, '2023-11-03 20:27:14'),
(7, 'DELGADO', 'LUCIA', 'TAGNAWA', '09184975699', '1948-03-14', 'F', 'W', '949 MRR TRACK ST., BRGY. BARANGKA IBABA, MANDALUYONG CITY', 1, 1, 1, '2023-11-03 22:33:00', 1, 1, '2023-11-09 21:00:21', 1, '2023-11-03 20:28:17'),
(8, 'ABAD', 'MARLYN', 'BRONDIAL', '640-7573', '1954-06-12', 'F', 'M', '939 MRR TRACK ST., BRGY. BARANGKA IBABA', 8, NULL, NULL, NULL, 0, NULL, NULL, 1, '2023-11-07 21:11:24'),
(9, 'ABASTILLAS', 'ANTONIO', 'QUIJANO', '0919-397-8925', '1960-01-16', 'M', 'M', '943 MRR TRACK ST., BRGY. BARANGKA IBABA', 9, NULL, NULL, NULL, 0, NULL, NULL, 1, '2023-11-07 21:13:48'),
(10, 'ADONA', 'DAISY', 'NUESTRO', '09266163420', '1963-10-15', 'F', 'W', '933 MRR TRACK ST., BRGY. BARANGKA IBABA', 10, NULL, NULL, NULL, 0, NULL, NULL, 1, '2023-11-09 21:00:01');

-- --------------------------------------------------------

--
-- Table structure for table `evac_center`
--

CREATE TABLE `evac_center` (
  `id` int(6) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `deletedflag` int(1) DEFAULT 0,
  `updated_by` int(6) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evac_center`
--

INSERT INTO `evac_center` (`id`, `name`, `address`, `contact`, `deletedflag`, `updated_by`, `updated_date`, `created_by`, `created_date`) VALUES
(1, 'P. CRUZ COVERED COURT', '985 MRR TRACK ST., BRGY. BARANGKA IBABA', '2987-70824', 0, 1, '2023-11-09 20:51:42', 1, '2023-11-03 20:58:55'),
(2, 'BARANGAY BARANGKA IBABA COVERED COURT', '856 E. PANTALEON ST., BRGY. BARANGKA IBABA', '2423-43244', 0, 1, '2023-11-09 20:51:32', 1, '2023-11-04 00:48:07'),
(3, 'SAN ROQUE PARISH', '51 SAN ROQUE ST., BRGY. BARANGKA  ILAYA, MANDALUYONG CITY', '2453-50454', 0, 1, '2023-11-09 20:52:01', 1, '2023-11-04 00:48:25'),
(4, 'CITY OF MANDALUYONG SCIENCE HIGH SCHOOL', '739 E. PANTALEON ST., BRGY. HULO', '09123456789', 0, 1, '2023-11-07 00:01:13', 1, '2023-11-06 17:29:52'),
(5, 'ILAYA BARANGKA AMPITHEATRE', '1035 S.CRUZ ST., BRGY. BARANGKA ILAYA', '09185098264', 0, 1, '2023-11-09 20:53:22', 1, '2023-11-06 23:48:12'),
(6, 'ST. JUDE CHAPEL', '845 E. PANTALEON ST., BRGY. BARANGKA IBABA', '2763-73664', 0, 1, '2023-11-09 20:52:16', 1, '2023-11-06 23:48:16'),
(7, 'MRR COVERED COURT', '993 MRR TRACK ST., BRGY. BARANGKA ITAAS', '09456789014', 0, 1, '2023-11-09 20:50:04', 1, '2023-11-06 23:48:19'),
(8, 'PEDRO P. CRUZ ELEMENTARY SCHOOL', '450 MARIA CLARA ST., BRGY. BARANGKA DRIVE', '09351694010', 0, 1, '2023-11-09 20:55:03', 1, '2023-11-06 23:48:27'),
(9, 'ILAYA BARANGKA INTEGRATED SCHOOL', '49 LION\'S RD., BRGY. BARANGKA ILAYA', '2894-20080', 0, 1, '2023-11-09 20:51:28', 1, '2023-11-06 23:48:31'),
(10, 'RIZAL TECHNOLOGICAL UNIVERSITY', '661A SACREPANTE ST., BONI AVE, BRGY. MALAMIG', '2853-483267', 0, 1, '2023-11-09 20:51:48', 1, '2023-11-06 23:48:35'),
(11, 'BARANGKA ILAYA BASKETBALL COURT', '70 ILAYA ST., BRGY. BARANGKA ILAYA', '2643-19345', 0, 1, '2023-11-09 20:54:08', 1, '2023-11-06 23:48:38'),
(12, 'BONIFACIO JAVIER NATIONAL HIGH SCHOOL', '761 SGT. BUMATAY ST., BRGY. BARANGKA DRIVE', '2654-98765', 0, 1, '2023-11-09 20:51:35', 1, '2023-11-06 23:48:43'),
(13, 'SAN ANTONIO SUB-PARISH', '526 TALAYAN ST., BRGY. BARANGKA DRIVE', '09674513613', 0, NULL, NULL, 1, '2023-11-09 20:55:46');

-- --------------------------------------------------------

--
-- Table structure for table `incident`
--

CREATE TABLE `incident` (
  `id` int(6) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `deletedflag` int(1) DEFAULT 0,
  `updated_by` int(6) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident`
--

INSERT INTO `incident` (`id`, `name`, `deletedflag`, `updated_by`, `updated_date`, `created_by`, `created_date`) VALUES
(1, 'FLOOD', 0, 1, '2023-11-03 21:28:28', 1, '2023-11-03 20:59:02'),
(2, 'FIRE', 0, NULL, NULL, 1, '2023-11-04 00:46:12'),
(3, 'TYPHOON', 0, NULL, NULL, 1, '2023-11-04 00:46:16'),
(4, 'EARTHQUAKE', 0, NULL, NULL, 1, '2023-11-04 00:46:23'),
(5, 'WILDFIRE', 0, NULL, NULL, 1, '2023-11-06 23:50:43'),
(6, 'TORNADO', 0, NULL, NULL, 1, '2023-11-06 23:50:53'),
(7, 'CHEMICAL SPILL', 0, NULL, NULL, 1, '2023-11-06 23:51:12'),
(8, 'GAS LEAK', 0, NULL, NULL, 1, '2023-11-06 23:51:20'),
(9, 'EXPLOSION', 0, NULL, NULL, 1, '2023-11-06 23:51:31'),
(10, 'HEATWAVE', 0, NULL, NULL, 1, '2023-11-06 23:52:18'),
(11, 'SINKHOLE', 0, NULL, NULL, 1, '2023-11-06 23:52:27'),
(12, 'PROTEST', 0, NULL, NULL, 1, '2023-11-06 23:52:42'),
(13, 'RIOT', 0, NULL, NULL, 1, '2023-11-06 23:52:45'),
(14, 'TERRORIST THREAT OR ATTACK', 0, NULL, NULL, 1, '2023-11-06 23:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `incident_archive`
--

CREATE TABLE `incident_archive` (
  `id` int(6) NOT NULL,
  `evacuee_id` int(6) NOT NULL,
  `incident_id` int(6) NOT NULL,
  `incident_date` datetime NOT NULL,
  `evac_id` int(6) NOT NULL,
  `deletedflag` int(1) DEFAULT 0,
  `updated_by` int(6) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incident_archive`
--

INSERT INTO `incident_archive` (`id`, `evacuee_id`, `incident_id`, `incident_date`, `evac_id`, `deletedflag`, `updated_by`, `updated_date`, `created_by`, `created_date`) VALUES
(1, 1, 1, '2023-11-03 20:59:00', 1, 0, NULL, NULL, 1, '2023-11-03 22:32:49'),
(2, 2, 1, '2023-11-03 20:59:00', 1, 0, NULL, NULL, 1, '2023-11-03 22:32:49'),
(3, 3, 1, '2023-11-03 20:59:00', 1, 0, NULL, NULL, 1, '2023-11-03 22:32:49'),
(4, 4, 1, '2023-11-03 20:59:00', 1, 0, NULL, NULL, 1, '2023-11-03 22:32:49'),
(5, 5, 1, '2023-11-03 20:59:00', 1, 0, NULL, NULL, 1, '2023-11-03 22:32:49'),
(6, 6, 1, '2023-11-03 20:59:00', 1, 0, NULL, NULL, 1, '2023-11-03 22:32:49'),
(7, 7, 1, '2023-11-03 20:59:00', 1, 0, NULL, NULL, 1, '2023-11-03 22:32:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `category` varchar(1) DEFAULT NULL,
  `active` int(1) DEFAULT 1,
  `deletedflag` int(1) DEFAULT 0,
  `updated_by` int(6) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `password`, `contact`, `birthday`, `category`, `active`, `deletedflag`, `updated_by`, `updated_date`, `created_by`, `created_date`) VALUES
(1, 'ADMIN', 'ADMIN DAW', '*23AE809DDACAF96AF0FD78ED04B6A265E05AA257', '09999999999', '2003-11-06', 'A', 0, 0, 1, '2023-11-06 16:46:33', 1, '2023-11-03 17:45:41'),
(2, 'ANGEL', 'ANGEL AZARRAGA', '*23AE809DDACAF96AF0FD78ED04B6A265E05AA257', '09999999999', NULL, 'A', 1, 0, NULL, NULL, 1, '2023-11-03 17:45:41'),
(3, 'STAFF', 'STAFF', '*23AE809DDACAF96AF0FD78ED04B6A265E05AA257', '09999999999', NULL, 'S', 1, 0, NULL, NULL, 1, '2023-11-03 17:45:41'),
(4, 'RVJ', 'RUTH JIMENEZ', '*93A5A7BA39F5A557A010726A8EE2274F7AA7B2EC', '123456789', '2001-12-03', 'A', 1, 1, 1, '2023-11-04 00:02:49', 1, '2023-11-04 00:00:52'),
(5, 'RVJJ', 'RUTH JIMENEZ', '*16386FF1B6D1C626AB3D106F62016CE8956C9AA7', '112345689', '2001-12-03', 'A', 1, 1, 1, '2023-11-04 00:36:39', 1, '2023-11-04 00:03:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `evacuee`
--
ALTER TABLE `evacuee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `representative` (`representative`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `evac_id` (`evac_id`),
  ADD KEY `incident_id` (`incident_id`);

--
-- Indexes for table `evac_center`
--
ALTER TABLE `evac_center`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `incident`
--
ALTER TABLE `incident`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `incident_archive`
--
ALTER TABLE `incident_archive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evacuee_id` (`evacuee_id`),
  ADD KEY `incident_id` (`incident_id`),
  ADD KEY `evac_id` (`evac_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `evacuee`
--
ALTER TABLE `evacuee`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `evac_center`
--
ALTER TABLE `evac_center`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `incident`
--
ALTER TABLE `incident`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `incident_archive`
--
ALTER TABLE `incident_archive`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evacuee`
--
ALTER TABLE `evacuee`
  ADD CONSTRAINT `evacuee_ibfk_1` FOREIGN KEY (`representative`) REFERENCES `evacuee` (`id`),
  ADD CONSTRAINT `evacuee_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `evacuee_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `evacuee_ibfk_4` FOREIGN KEY (`evac_id`) REFERENCES `evac_center` (`id`),
  ADD CONSTRAINT `evacuee_ibfk_5` FOREIGN KEY (`incident_id`) REFERENCES `incident` (`id`);

--
-- Constraints for table `evac_center`
--
ALTER TABLE `evac_center`
  ADD CONSTRAINT `evac_center_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `evac_center_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `incident`
--
ALTER TABLE `incident`
  ADD CONSTRAINT `incident_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `incident_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `incident_archive`
--
ALTER TABLE `incident_archive`
  ADD CONSTRAINT `incident_archive_ibfk_1` FOREIGN KEY (`evacuee_id`) REFERENCES `evacuee` (`id`),
  ADD CONSTRAINT `incident_archive_ibfk_2` FOREIGN KEY (`incident_id`) REFERENCES `incident` (`id`),
  ADD CONSTRAINT `incident_archive_ibfk_3` FOREIGN KEY (`evac_id`) REFERENCES `evac_center` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
