-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2019 at 06:51 AM
-- Server version: 10.3.15-MariaDB
-- PHP Version: 7.1.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hyphen`
--

-- --------------------------------------------------------

--
-- Table structure for table `bidding`
--

CREATE TABLE `bidding` (
  `bidding_id` int(11) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `proposed_price` text NOT NULL,
  `description` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bidding`
--

INSERT INTO `bidding` (`bidding_id`, `bid_id`, `user_id`, `proposed_price`, `description`, `time`, `status`) VALUES
(3, 5, 11, '510', 'Hey lets agree with this good price!', '2019-11-03 15:36:08', 1),
(5, 7, 10, '600', 'abuguida', '2019-11-03 15:36:08', 2),
(7, 9, 10, '900', 'quuuuuuuuuuu', '2019-11-03 16:00:04', 2),
(8, 7, 9, '809', 'qqqqqqqqqqqqqqqq', '2019-11-03 17:11:37', 1),
(10, 7, 13, '900', 'qqqqqqqqqqqqqqqq', '2019-11-23 11:21:28', 1),
(12, 7, 18, '900', 'qqqqqqqqqqqqqqqq', '2019-11-25 20:57:12', 1),
(13, 8, 18, '800', 'qqqqqqqqqqqqqqqq', '2019-11-25 20:57:57', 1),
(14, 9, 18, '800', 'qqqqqqqqqqqqqqqq', '2019-11-25 21:10:09', 2);

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `bid_id` int(11) NOT NULL,
  `user_id` text NOT NULL,
  `bid_title` text NOT NULL,
  `bid_description` text NOT NULL,
  `bid_pic_url` text NOT NULL,
  `price` text NOT NULL,
  `posted_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiry_date` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `rating` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`bid_id`, `user_id`, `bid_title`, `bid_description`, `bid_pic_url`, `price`, `posted_time`, `expiry_date`, `status`, `rating`) VALUES
(1, '', 'call for car sale', 'I want to sell my car you can engage by biding', '', '200000', '2019-11-01 16:44:37', '0000-00-00 00:00:00', 0, ''),
(2, '', 'call for car sale', 'I want to sell my car you can engage by biding', 'qwertyu', '200000', '2019-11-01 17:39:19', '0000-00-00 00:00:00', 0, ''),
(3, '', 'call for car sale', 'I want to sell my car you can engage by biding', 'qwertyu', '200000', '2019-11-01 17:42:13', '2019-11-01 18:39:19', 0, ''),
(4, '', 'call for car sale', 'I want to sell my car you can engage by biding', 'qwertyu', '200000', '2019-11-01 17:44:14', '2019-11-01 18:39:19', 0, ''),
(5, '9', 'asdfg', 'I want to sell my car you can engage by biding', 'qwertyu', '200000', '2019-11-01 17:52:06', '2019-11-01 18:39:19', 1, ''),
(6, '9', 'asdfg', 'I want to sell my car you can engage by biding', 'qwertyu', '200000', '2019-11-01 17:57:05', '2019-11-01 18:39:19', 0, NULL),
(7, '18', 'asdfg', 'I want to sell my car you can engage by biding', 'qwertyu', '200000', '2019-11-01 18:12:04', '2019-11-01 18:39:19', 3, ''),
(9, '18', 'ha ha ah a', 'I want to sell my car you can engage by biding', 'qwertyu', '200000', '2019-11-01 19:06:14', '2019-11-01 18:39:19', 2, '5'),
(10, '10', '', '', '', '', '2019-11-01 19:06:14', '0000-00-00 00:00:00', 1, NULL),
(13, '18', 'Buy My Car', 'I want to sell my car you can engage by biding', 'https://image-cdn.beforward.jp/large/201911/1550502/BG599364_5b2f7b.JPG ', '200000', '2019-11-25 13:20:03', '2019-11-01 18:39:19', 3, '4'),
(14, '11', 'Buy My Car', 'I want to sell my car you can engage by biding', 'https://image-cdn.beforward.jp/large/201911/1550502/BG599364_5b2f7b.JPG ', '200000', '2019-11-25 13:20:10', '2019-11-01 18:39:19', 1, NULL),
(15, '11', 'Buy My Car', 'I want to sell my car you can engage by biding', 'https://image-cdn.beforward.jp/large/201911/1550502/BG599364_5b2f7b.JPG ', '200000', '2019-11-25 13:22:07', '2019-11-01 18:39:19', 1, NULL),
(17, '18', 'Buy My Home', 'I want to sell my home you can engage by biding', 'https://imagecdn.beforward.jp/large/201911/1550502/BG599364_5b2f7b.JPG ', '200000', '2019-11-30 03:25:40', '2019-11-01 18:39:19', 1, NULL),
(18, '18', 'Buy My Home', 'I want to sell my home you can engage by biding', 'https://imagecdn.beforward.jp/large/201911/1550502/BG599364_5b2f7b.JPG ', '200000', '2019-11-30 03:58:04', '2019-11-01 18:39:19', 1, NULL),
(19, '18', 'Buy My Home', 'I want to sell my home you can engage by biding', 'https://imagecdn.beforward.jp/large/201911/1550502/BG599364_5b2f7b.JPG ', '200000', '2019-11-30 03:59:01', '2019-11-01 18:39:19', 1, NULL),
(20, '18', 'Buy My Home', 'I want to sell my home you can engage by biding', 'https://imagecdn.beforward.jp/large/201911/1550502/BG599364_5b2f7b.JPG ', '200000', '2019-11-30 03:59:57', '2019-11-01 18:39:19', 1, NULL),
(21, '18', 'Buy My Home', 'I want to sell my home you can engage by biding', 'https://imagecdn.beforward.jp/large/201911/1550502/BG599364_5b2f7b.JPG ', '200000', '2019-11-30 04:01:00', '2019-11-01 18:39:19', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` text NOT NULL,
  `user_email` text NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` text NOT NULL,
  `profile_pic_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `user_email`, `registration_date`, `password`, `profile_pic_url`) VALUES
(1, '1', 'kal@gmail.com', '2019-10-31 16:17:35', '4c5fca3ed14e45d865d31b780a7bd40c', ''),
(2, '1', 'kael@gmail.com', '2019-10-31 16:23:20', '4c5fca3ed14e45d865d31b780a7bd40c', ''),
(3, '1', 'kaekl@gmail.com', '2019-10-31 16:53:55', 'd41d8cd98f00b204e9800998ecf8427e', ''),
(4, '1', 'mmmm@gmail.com', '2019-10-31 17:16:10', 'd8f28e546569fc622041968718f0e69c', ''),
(5, 'fgfg', '123@gmail.com', '2019-10-31 17:34:03', '4c5fca3ed14e45d865d31b780a7bd40c', ''),
(6, '1234555', 'fgfgfddd@gmail.com', '2019-10-31 18:54:56', '4c5fca3ed14e45d865d31b780a7bd40c', ''),
(7, '345678', 's@gmail.com', '2019-10-31 19:11:06', '4c5fca3ed14e45d865d31b780a7bd40c', ''),
(8, '345678', 'st@gmail.com', '2019-10-31 19:11:38', '4c5fca3ed14e45d865d31b780a7bd40c', ''),
(9, 'kaleab', 'kaleaby@gmail.com', '2019-10-31 19:47:58', '4c5fca3ed14e45d865d31b780a7bd40c', ''),
(10, 'kiya', 'kiya@gmail.com', '2019-10-31 20:43:22', '4c5fca3ed14e45d865d31b780a7bd40c', ''),
(11, 'emebet adinew', 'emebu@gmail.com', '2019-11-22 19:50:17', '1a4eeb020e2de6288f66d31c9e56eeea', ''),
(12, 'hunegnaw', 'hun@gmail.com', '2019-11-22 19:54:26', '14f102737e25d7cb57624effb9e07670', ''),
(13, 'solomon mulatu', 'sol@gmail.com', '2019-11-23 11:20:39', 'b3c16e11824ef101d56a19dc130b2741', ''),
(14, 'Kako', 'kako@gmail.com', '2019-11-25 08:22:37', '4fe2055f76b617cc24b48a5628bbc345', ''),
(15, 'Kako jku', 'kakoo@gmail.com', '2019-11-25 08:58:17', '4fe2055f76b617cc24b48a5628bbc345', ''),
(18, 'abc', 'abc@gmail.com', '2019-11-25 13:09:48', 'd0156bb1108afa7e1ce166da0ce84165', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bidding`
--
ALTER TABLE `bidding`
  ADD PRIMARY KEY (`bidding_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`bid_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bidding`
--
ALTER TABLE `bidding`
  MODIFY `bidding_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
