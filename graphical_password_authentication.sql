-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2020 at 08:15 PM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `graphical_password_authentication`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `other_names` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `image_filename` varchar(100) NOT NULL,
  `img_point00` int(11) DEFAULT NULL,
  `img_point01` int(11) DEFAULT NULL,
  `img_point02` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `surname`, `other_names`, `email`, `phone`, `password`, `image_filename`, `img_point00`, `img_point01`, `img_point02`) VALUES
(1, 'Okoroike', 'John Chiemeka', 'john@gmail.com', '07065927099', '12aea210b4563725861aaf72ac7b6b8a', 'Store/Uploads/12aea210b4563725861aaf72ac7b6b8aconnection.png', 203, 190, 203),
(2, 'Musa', 'Haruna Godswill', 'godsmusa@gmail.com', '07030303030', 'f4cb5d8b1ae162c60b2bbff68475afe0', 'Store/Uploads/f4cb5d8b1ae162c60b2bbff68475afe0product-2.jpg', 124, 208, 153),
(3, 'Okoroike', 'John', 'okoroike@gmail.com', '07065927099', 'f6c1c426bca4a8e65fb86565bf4bd404', 'Store/Uploads/f6c1c426bca4a8e65fb86565bf4bd404mouau-logo.png', 72, 179, 94);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
