-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2023 at 07:09 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `raqueldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `factors_intervention`
--

CREATE TABLE `factors_intervention` (
  `id` int(11) NOT NULL,
  `factor` varchar(255) DEFAULT NULL,
  `intervention` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `factors_intervention`
--

INSERT INTO `factors_intervention` (`id`, `factor`, `intervention`, `created_at`, `deleted_at`) VALUES
(7, 'Introduction to Computing', '[{\"name\":\"Teacher student coaching\"},{\"name\":\"asd\"},{\"name\":\"haha\"}]', '2022-10-04 18:44:43', NULL),
(9, 'Fundamentals of Programming (C++)', '[{\"name\":\"Teacher student coaching\"}]', '2022-10-04 21:45:22', NULL),
(10, 'Purposive Communication', '[{\"name\":\"Teacher student coaching\"},{\"name\":\"coms\"}]', '2022-10-04 21:45:34', NULL),
(11, 'Intermediate Programming(Java)', '[{\"name\":\"Teacher student coaching\"}]', '2022-10-04 21:45:46', NULL),
(12, 'Discrete Mathematics', '[{\"name\":\"Teacher student coaching\"}]', '2022-10-04 21:45:59', NULL),
(13, 'Mathematics in the Modern World', '[{\"name\":\"Student - Teacher coaching\"}]', '2022-10-08 08:16:58', NULL),
(14, 'factor 1', '[{\"name\":\"int 1\"},{\"name\":\"int 2\"},{\"name\":\"int 3\"}]', '2022-10-08 13:38:56', NULL),
(15, 'factor 2', '[{\"name\":\"haha\"}]', '2022-10-08 13:42:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `factors` varchar(255) NOT NULL,
  `base_file` text DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `year_id`, `school_id`, `section_id`, `factors`, `base_file`, `category`, `created_at`, `deleted_at`) VALUES
(194, 9, 2, 3, '', '17789-9-20230524190219-basefile.pdf', 'BALAT\n', '2023-05-25 01:02:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `school_name` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `school_name`, `created_at`, `deleted_at`) VALUES
(1, 'Bulacan State Unniversity', '2023-05-25 00:13:24', NULL),
(2, 'PLP', '2023-05-25 00:28:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `school_year`
--

CREATE TABLE `school_year` (
  `id` int(11) NOT NULL,
  `year_name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `school_year`
--

INSERT INTO `school_year` (`id`, `year_name`, `created_at`, `deleted_at`) VALUES
(1, '2019-2020', '2022-09-29 17:32:32', NULL),
(2, '2020-2021', '2022-09-29 17:32:32', NULL),
(3, '2021-2022', '2022-09-29 17:32:50', NULL),
(4, '2023-2024', '2022-09-29 17:32:50', NULL),
(5, '2025-2026', '2022-09-29 17:33:10', NULL),
(6, '2027-2028', '2022-09-29 17:33:10', NULL),
(7, '2022-2023', '2022-09-29 17:33:37', NULL),
(8, '2024-2025', '2022-09-29 17:33:52', NULL),
(9, '2026-2027', '2022-09-29 17:34:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `section_name` varchar(255) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `section_name`, `school_id`, `created_at`, `deleted_at`) VALUES
(1, 'BSIT-1A', 1, '2022-09-29 00:00:00', NULL),
(2, 'BSIT-1B', 1, '2022-09-29 00:00:00', NULL),
(3, 'BSIT-1C', 2, '2022-09-29 00:00:00', NULL),
(4, 'BSIT-1D', 2, '2022-09-29 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` text NOT NULL,
  `token` text DEFAULT NULL,
  `user_level` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `password`, `token`, `user_level`, `created_at`, `deleted_at`) VALUES
(48, 'racquel', 'racquel', 'racquel', '$2y$10$x2HdyT25yGlwB7Sff3hlVOKmvWXv96ie4jnVc5cALUnJXe3kfNMpy', '$2y$10$NxXBSVuku0tGI4ug47IGtug90N.QTjP.hDqVDS.sGis4feGLx7hXi', 1, '2023-05-24 17:46:56', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `factors_intervention`
--
ALTER TABLE `factors_intervention`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_year`
--
ALTER TABLE `school_year`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
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
-- AUTO_INCREMENT for table `factors_intervention`
--
ALTER TABLE `factors_intervention`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `school_year`
--
ALTER TABLE `school_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
