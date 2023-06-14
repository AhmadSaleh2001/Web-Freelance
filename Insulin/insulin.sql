-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2023 at 01:51 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `insulin`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categoriesposts`
--

CREATE TABLE `categoriesposts` (
  `id` int(11) NOT NULL,
  `categoryname` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categoriesposts`
--

INSERT INTO `categoriesposts` (`id`, `categoryname`) VALUES
(14, 'asdasd');

-- --------------------------------------------------------

--
-- Table structure for table `exp`
--

CREATE TABLE `exp` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `totalcarbo` double DEFAULT NULL,
  `needinsulin` double DEFAULT NULL,
  `Foods` text NOT NULL,
  `sugarinblood` double NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `exp2`
--

CREATE TABLE `exp2` (
  `id` int(11) NOT NULL,
  `value` int(4) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `expdate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `food`
--

CREATE TABLE `food` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `carbo` double DEFAULT NULL,
  `quantity` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT 'default.jpg',
  `catid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `img` varchar(300) DEFAULT 'default.jpg',
  `userid` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `body`, `img`, `userid`, `type`) VALUES
(79, 'asdasd', '<p>asdasdasd</p>', 'default.jpg', 3, 14),
(80, 'sdfdsfsd', '<p>sdfsdfsdfsd</p>', 'default.jpg', 3, 14),
(81, 'dfgdfgfdgfdgfdgdfgdf', '<p>dfgfdgfdgdfgfdgfdg</p>', 'default.jpg', 3, 14),
(82, 'fdgdfsdfsdfsdf', '<p>ssssssssssssssssssssssssssssssss</p>', 'default.jpg', 3, 14),
(83, 'Last', '<p>Last</p>', 'default.jpg', 3, 14),
(84, 'asdasd', '<p>asdsadsa</p>', 'default.jpg', 3, 14),
(85, 'sdsdf', '<p>sdfsdf</p>', 'default.jpg', 3, 14);

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `weight` double NOT NULL,
  `height` double NOT NULL,
  `age` double NOT NULL,
  `gender` varchar(255) NOT NULL,
  `isadmin` int(2) DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `weight`, `height`, `age`, `gender`, `isadmin`) VALUES
(3, 'Ahmad', 'Ahmadmfsaleh@gmail.com', 'Ahmad123', 0, 0, 0, 'ذكر', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categoriesposts`
--
ALTER TABLE `categoriesposts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exp`
--
ALTER TABLE `exp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_between_users_and_exp` (`userid`);

--
-- Indexes for table `exp2`
--
ALTER TABLE `exp2`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_between_users_exp2FOREIGN` (`userid`);

--
-- Indexes for table `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_between_categories_food` (`catid`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_between_users_posts` (`userid`),
  ADD KEY `fk_between_posts_category_posts` (`type`);

--
-- Indexes for table `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `categoriesposts`
--
ALTER TABLE `categoriesposts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `exp`
--
ALTER TABLE `exp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `exp2`
--
ALTER TABLE `exp2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `food`
--
ALTER TABLE `food`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exp`
--
ALTER TABLE `exp`
  ADD CONSTRAINT `fk_between_users_and_exp` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exp2`
--
ALTER TABLE `exp2`
  ADD CONSTRAINT `fk_between_users_exp2FOREIGN` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `fk_between_categories_food` FOREIGN KEY (`catid`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_between_posts_category_posts` FOREIGN KEY (`type`) REFERENCES `categoriesposts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_between_users_posts` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
