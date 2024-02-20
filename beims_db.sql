-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2024 at 09:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beims_db`
--

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
  `barangay_id` int(6) DEFAULT NULL,
  `deletedflag` int(1) DEFAULT 0,
  `updated_by` int(6) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_by` int(6) DEFAULT NULL,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fullname`, `password`, `contact`, `birthday`, `category`, `active`, `barangay_id`, `deletedflag`, `updated_by`, `updated_date`, `created_by`, `created_date`) VALUES
(1, 'ADMIN', 'ADMIN', '*23AE809DDACAF96AF0FD78ED04B6A265E05AA257', '09999999999', '2001-08-20', 'A', 1, 1, 0, 1, '2023-11-17 10:07:59', 1, '2023-11-09 21:07:05'),
(2, 'ANGEL', 'ANGEL AZARRAGA', '*23AE809DDACAF96AF0FD78ED04B6A265E05AA257', '09772177092', '2001-07-30', 'S', 1, 1, 0, 1, '2023-11-17 10:15:01', 1, '2023-11-09 21:07:05'),
(3, 'STAFF', 'RUTH JIMENEZ', '*69156C3775BC63A03BDF56AD0B48E2BE5DF601DD', '09999999999', '2001-12-03', 'S', 0, 1, 1, 1, '2023-11-11 16:07:57', 1, '2023-11-09 21:07:05'),
(4, 'RVJIMENEZ', 'RUTH JIMENEZ', '*AF5B74738CA28DA6A34F0D1634FB495CC1FCDF10', '09152414936', '2001-12-03', 'S', 1, 1, 0, 1, '2023-11-18 09:59:44', 1, '2023-11-11 16:10:57'),
(5, 'ROLSCALMERIN', 'ROWIN CALMERIN', '*3E1DBB22FA8E75EF03187ACDDAE3B3C208C390A8', '09683134227', '2002-07-22', 'S', 1, 1, 0, NULL, NULL, 1, '2023-11-11 16:48:37'),
(6, 'RENZOOOOOWWWWW', 'RENZ WILEN RACELIS', '*3306DCFD61B084F24EBFC31845EB92024CEABD65', '09995517713', '2002-05-02', 'S', 1, 1, 0, 1, '2023-11-17 14:19:50', 1, '2023-11-11 16:56:43'),
(7, 'KATH123', 'KATRINA MORDENO', '*CB8016B6C7813048E5CEFC7F94508F0F75FA4330', '09159755068', '2002-05-25', 'S', 1, 1, 0, NULL, NULL, 1, '2023-11-11 17:05:40'),
(8, 'ALIMERC', 'ANTONIUS LIENZE MERCADEJAS', '*8DC54F2E15823C98AEA063E339A5D4C53D1A471A', '09876337465', '1989-07-07', 'S', 1, 1, 1, 1, '2023-11-18 09:59:52', 1, '2023-11-13 00:15:37'),
(9, 'GJSTR', 'SATORU GOJO', '*00A51F3F48415C7D4E8908980D443C29C69B60C9', '09873645284', '1989-12-07', 'S', 1, 1, 1, 1, '2023-11-15 08:25:27', 1, '2023-11-15 08:24:59'),
(10, 'BONGNABARO', 'BONG NABARO', '*5CEF0BFA8A90DC4BAD15D58322EE7F8772836117', '09473278762', '1995-01-01', 'S', 1, 1, 0, NULL, NULL, 1, '2023-11-18 10:00:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `users_ibfk_3` (`barangay_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`barangay_id`) REFERENCES `barangay` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
