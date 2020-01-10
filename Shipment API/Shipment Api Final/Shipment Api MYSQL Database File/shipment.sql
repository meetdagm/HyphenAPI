-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2019 at 09:02 PM
-- Server version: 10.3.16-MariaDB
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shipment`
--

-- --------------------------------------------------------

--
-- Table structure for table `shipment`
--

CREATE TABLE `shipment` (
  `shipment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `delivery_man_id` int(11) NOT NULL,
  `receiver_address` text NOT NULL,
  `starting_location` text NOT NULL,
  `destination_location` text NOT NULL,
  `starting_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipment_status` text NOT NULL,
  `delivery_man_starting_location` text NOT NULL,
  `path` text NOT NULL,
  `current_location` text NOT NULL,
  `note` text NOT NULL,
  `mode` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `shipment`
--

INSERT INTO `shipment` (`shipment_id`, `user_id`, `delivery_man_id`, `receiver_address`, `starting_location`, `destination_location`, `starting_time`, `end_time`, `created_at`, `shipment_status`, `delivery_man_starting_location`, `path`, `current_location`, `note`, `mode`) VALUES
(1, 1, 1, 'Amanuel Gizachew', '45.465454,55.6554', '45.465454,55.6554', '01:17:10', '15:09:18', '2019-11-26 08:00:00', 'delivered', '8.2564,12.5566', '4.2564,8.5566:4.2564,8.5566:4.2564,8.5566', '4.2564,8.5566', 'this gay:Cancel - because the car Engine is fail', 'Shipment only'),
(2, 2, 4, 'Amanuel Arage, 0914558575, Lato', '4.555,8.45645', '8.22222,89.4545', '05:55:00', '09:09:00', '2019-11-26 08:00:00', 'received', '55.45,4.56', '12.455,11.889:12.455,11.889:9.455,5.889:9.455,5.889', '9.455,5.889', 'sakfksja skaf ksa dh', 'Shipping only'),
(4, 5, 1, 'Galiliyo, 09252874,Lebu', '8.5451,4.54654', '9.5465,8.54546', '03:20:00', '02:55:00', '2019-11-26 08:00:00', 'delivered', '8.2564,12.5566', '', '', 'nfuzb xvkbs dxckvksd', 'Lafto'),
(5, 2, 1, 'Kebede Azalu, 0928353975,kera', '78.54654,45.856', '25.54654,12.856', '04:55:00', '03:21:00', '2019-11-26 08:00:00', 'started', '45.65465431,8.1531513', '', '', 'sagflksabkfsakb:Cancel - dxhcjgckj', 'Shipping only'),
(6, 2, 1, 'Aschalew Bulo, 0927353975,Kera', '8.953465, 38.748573', '8.994054, 38.753297', '06:20:00', '11:30:00', '0000-00-00 00:00:00', 'started', '45.65465431,8.1531513', '37.4219983,-20.0839983:37.4219983,-20.0839983:37.4219983,-20.0839983:37.4219983,-20.0839983:37.4219983,-20.0839983:45.7895,8.5465454:45.7895,8.5465454:45.7895,8.5465454:45.7895,8.5465454:4.54651,12.46451:45.7895,8.5465454:8.4545,9.2525', '8.4545,9.2525', 'Simply Broken Material', 'Shipping only'),
(8, 2, 0, 'Amanuel Gizachew, 0912554758, Addis Ababa', '4.5485,8.54135', '8.4151351,9.646464', '04:35:00', '08:44:00', '0000-00-00 00:00:00', 'open', '', '', '', 'sajsaljljsalhlasdldsad aljsbd', 'Package only');

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `user_id` int(11) NOT NULL,
  `full_name` text NOT NULL,
  `e_mail` text NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` text NOT NULL,
  `profile_pic_url` text NOT NULL,
  `user_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_id`, `full_name`, `e_mail`, `registration_date`, `password`, `profile_pic_url`, `user_type`) VALUES
(1, 'solomon', 'sol@gmail.com', '2019-11-30 08:00:00', '45G7nbH2VsCQk', 'dsgdg.png', 'DELIVERY_MAN'),
(2, 'jonny', 'jo@gmail.com', '2019-11-30 08:00:00', '45G7nbH2VsCQk', 'jo/aman.jpg', 'ADMIN'),
(3, 'Amanuel', 'aman@gmail.com', '2019-11-30 08:00:00', '45G7nbH2VsCQk', 'aman.jpg', 'ADMIN'),
(4, 'kebede', 'kebe@gmail.com', '2019-11-30 08:00:00', '45G7nbH2VsCQk', 'shf', 'DELIVERY_MAN'),
(5, 'aschalew bulo', 'asche@gmail.com', '2019-11-30 08:00:00', '45G7nbH2VsCQk', 'aman.png', 'ADMIN'),
(6, 'Sisay Abreham', 'sis@gmail.com', '2019-11-30 08:00:00', '45G7nbH2VsCQk', 'sis.jpg', 'ADMIN'),
(7, 'Feruz ', 'feru@gmail.com', '2019-11-30 08:00:00', '45G7nbH2VsCQk', 'aman/gmail.com', 'ADMIN'),
(8, 'Tsion Gizachew', 'tsi@gmail.com', '2019-11-30 08:00:00', '45G7nbH2VsCQk', 'tsi.jpg', 'DELIVERY_MAN'),
(10, 'Jilalu Aschalew', 'jil@gmail.com', '2019-11-30 08:00:00', '45G7nbH2VsCQk', 'aman.jpg', 'DELIVERY_MAN'),
(11, 'akalu mekonen', 'ake@gmail.com', '0000-00-00 00:00:00', '45G7nbH2VsCQk', '', 'ADMIN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shipment`
--
ALTER TABLE `shipment`
  ADD PRIMARY KEY (`shipment_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shipment`
--
ALTER TABLE `shipment`
  MODIFY `shipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
