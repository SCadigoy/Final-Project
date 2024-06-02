-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2024 at 07:27 AM
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
-- Database: `instagram_clone`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `comment`, `created_at`) VALUES
(38, 6, 113, 'saba tom!!', '2024-05-29 08:04:25'),
(39, 13, 121, 'amen', '2024-05-30 07:31:44');

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`follower_id`, `followed_id`) VALUES
(6, 7),
(6, 10),
(6, 12),
(7, 6),
(7, 8),
(7, 11),
(8, 6),
(8, 7),
(8, 11),
(9, 11),
(10, 6),
(10, 12),
(11, 7),
(11, 8),
(12, 6),
(12, 10),
(13, 6),
(13, 7),
(13, 9),
(13, 11);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `caption` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `caption`, `image`, `created_at`) VALUES
(113, 12, 'Ka hadlok na ini na panahon🥺', '', '2024-05-29 07:24:49'),
(114, 12, 'Praise the Lord', 'uploads/bible.jpg', '2024-05-29 07:34:15'),
(116, 6, 'Kacute!!!!', 'uploads/anna_nie.jpg', '2024-05-29 07:57:07'),
(117, 10, 'Sa college rajud ka makasuway mo hilak bisag wala buwagi', '', '2024-05-29 08:06:23'),
(118, 7, 'I\'m so proud of you.', 'uploads/marga_mup.jpg', '2024-05-29 08:10:14'),
(119, 8, 'Makagraduate lagi ako soon!!', 'uploads/SNSU.jpg', '2024-05-29 08:14:22'),
(120, 11, 'Vote Straight', 'uploads/sinag.jpg', '2024-05-29 08:22:38'),
(121, 9, '', 'uploads/drake.jpg', '2024-05-29 08:23:41'),
(122, 13, 'Cute', 'uploads/ferlyn.jpg', '2024-05-30 07:31:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_picture` varchar(255) DEFAULT NULL,
  `cover_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `profile_picture`, `cover_picture`) VALUES
(6, 'Anna Marie', 'anna@gmail.com', '$2y$10$RqHRH3B8JLvL4Sz7bJXy3ubAyowK1DSRDPXqSY7G6F.LskBF8ptra', '2024-05-29 03:58:13', 'uploads/anna.jpg', 'uploads/annamariemar.jpg'),
(7, 'Ferelyn', 'fer@gmail.com', '$2y$10$1./V66Hubr8SxkgxExx8c.yxPVUyEEtReDk5B2Yeb4yjRsTcstya.', '2024-05-29 05:10:26', 'uploads/fer.jpg', 'uploads/ferlyn.jpg'),
(8, 'Sheena', 'sheena@gmail.com', '$2y$10$AbbnltVwM7vg5I8qkzowvucOunnuBZkITm.HOQ3RchjfINswIBpCW', '2024-05-29 06:55:42', 'uploads/me.jpg', 'uploads/Family Pic-Choc.HIlls.jpg'),
(9, 'Carlyn Mae', 'carlyn@gmail.com', '$2y$10$KQ5Mk18N8SEp3vVdknK3sOROhlud1ZL0HihxQ2pWM/7MRxGkGW30u', '2024-05-29 06:59:40', 'uploads/car.jpg', 'uploads/noh.jpg'),
(10, 'Jexce ', 'jex@gmail.com', '$2y$10$75mDWscILyOk7zT50DbMoOslwCzZz.KaI0p/.hX7SjYOASjG8wOw6', '2024-05-29 07:01:13', 'uploads/jex.jpg', 'uploads/jexce.jpg'),
(11, 'Jethro', 'jet@gmail.com', '$2y$10$/8BbTxxVN9e04Fg2SgLLC.X3ajCp1Azo2S2nFnfLSHjo8QoJvApJ2', '2024-05-29 07:04:32', 'uploads/jet.jpg', 'uploads/jethro.jpg'),
(12, 'James', 'james@gmail.com', '$2y$10$pvMYVK9G5FF.OvhSKhkmfefVf/jxqbYUMrtbWgn4l3Rcn.RzCeLQ6', '2024-05-29 07:08:05', 'uploads/jam.jpg', 'uploads/james.jpg'),
(13, 'Anne', 'anne@gmail.com', '$2y$10$kdCMND7vVcZe9dUYENbLmOthvqmir8TdBVagm/ufBQPSVCOyVzGVe', '2024-05-30 07:30:47', 'uploads/jexce.jpg', 'uploads/annamariemar.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`follower_id`,`followed_id`),
  ADD KEY `followed_id` (`followed_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Constraints for table `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
