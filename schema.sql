-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 12, 2022 at 10:50 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schema`
--

-- --------------------------------------------------------

--
-- Table structure for table `boards`
--

CREATE TABLE `boards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `workspace_id` bigint(20) UNSIGNED NOT NULL,
  `template` varchar(200) NOT NULL DEFAULT 'styles/templates/default.css'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `boards`
--

INSERT INTO `boards` (`id`, `name`, `workspace_id`, `template`) VALUES
(1, 'Front end Board', 1, 'styles/templates/default.css'),
(5, 'MY PROJECT', 1, 'styles/templates/default.css'),
(7, 'Styling design', 1, 'styles/templates/sunset.css');

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `list_id` bigint(20) UNSIGNED NOT NULL,
  `serial` int(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `name`, `description`, `list_id`, `serial`) VALUES
(1, 'Login System', 'We have to build complete login system that is consists of:\n1- login\n2- sign up\n3- forget password\n4- sessions\n5- mail verification code\n6- test add description', 1, 1),
(2, 'Home Page', 'This is the first page displayed when the user request the website.', 2, 2),
(3, 'First Structure', 'files of our website :\n1- pages\n2- common\n3- styles\n4- scripts\n5- functions', 2, 1),
(7, 'card', '', 1, 2),
(8, 'another card', '', 1, 1),
(9, 'card text', 'HELLO', 1, 2),
(14, 'Build initial structure', 'initial files structures ', 17, 1),
(15, 'Add some features', 'styles and templates UI ..', 14, 1),
(16, 'Control database', 'requests handlers for AJAX in JSON format \ndelete items \nadding lists, cards and boards\nmaintain order of cards and lists', 15, 1),
(17, 'add listeners', 'listeners for several events like \n- click \n- drag\n- hover \n- focus', 16, 2),
(18, 'develop html', 'HTML elements \nCSS default style ', 16, 1),
(19, 'security check', 'against some attacks like \n- SQL injection \n- XXS\n- Brute forcing', 15, 2),
(24, 'first card', '', 29, 1),
(25, 'second card', '', 29, 1),
(26, 'third card', '', 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

CREATE TABLE `lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `board_id` bigint(20) UNSIGNED NOT NULL,
  `serial` int(255) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lists`
--

INSERT INTO `lists` (`id`, `name`, `board_id`, `serial`) VALUES
(1, 'BRAIN STORM', 1, 2),
(2, 'To Do now', 1, 1),
(14, 'BRAIN STORM', 5, 1),
(15, 'TO DO', 5, 2),
(16, 'DOING', 5, 3),
(17, 'DONE', 5, 4),
(29, 'First list', 7, 1),
(30, 'Second list', 7, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(100) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `reg_date`) VALUES
(6, 'SousSous', 'amroosous@gmail.com', '$2y$10$WXFgjYW9jcBIG7nkAaYYCOSMTtibdIv/lZz.OUcNZ15lgpqv/qnla', '2022-12-09 12:01:44'),
(7, 'MalekDev', 'mlekgamer17@gmail.com', '$2y$10$GhKfylerH1JXd.ARGe2rVO4pi.kAxTkpXW8trzXAlOv066x4ue/Cq', '2022-12-03 09:09:18');

-- --------------------------------------------------------

--
-- Table structure for table `workspaces`
--

CREATE TABLE `workspaces` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `workspaces`
--

INSERT INTO `workspaces` (`id`, `name`, `user_id`) VALUES
(1, 'My Web Project', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boards`
--
ALTER TABLE `boards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `board_Workspace_fk` (`workspace_id`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `card_list_fk` (`list_id`);

--
-- Indexes for table `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_board_fk` (`board_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `workspace_user_fk` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boards`
--
ALTER TABLE `boards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `lists`
--
ALTER TABLE `lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(100) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `workspaces`
--
ALTER TABLE `workspaces`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `boards`
--
ALTER TABLE `boards`
  ADD CONSTRAINT `board_Workspace_fk` FOREIGN KEY (`workspace_id`) REFERENCES `workspaces` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `card_list_fk` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lists`
--
ALTER TABLE `lists`
  ADD CONSTRAINT `list_board_fk` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `workspaces`
--
ALTER TABLE `workspaces`
  ADD CONSTRAINT `workspace_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
