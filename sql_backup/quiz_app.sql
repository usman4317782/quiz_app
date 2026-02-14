-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2026 at 01:45 PM
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
-- Database: `quiz_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `attempt_details`
--

CREATE TABLE `attempt_details` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `question_id` varchar(50) NOT NULL,
  `user_answer_index` int(11) NOT NULL,
  `is_correct` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attempt_details`
--

INSERT INTO `attempt_details` (`id`, `attempt_id`, `question_id`, `user_answer_index`, `is_correct`) VALUES
(1, 1, '6', 2, 1),
(2, 1, '7', 3, 0),
(3, 1, '8', 3, 0),
(4, 1, '9', 2, 0),
(5, 1, '10', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_logs`
--

CREATE TABLE `auth_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_type` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `session_id` varchar(128) DEFAULT NULL,
  `status_code` int(11) DEFAULT 200,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_logs`
--

INSERT INTO `auth_logs` (`id`, `user_id`, `event_type`, `ip_address`, `user_agent`, `session_id`, `status_code`, `message`, `created_at`) VALUES
(1, NULL, 'Login Failure', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'k0mum3s1p08q652p4im2lnkuol', 401, 'Invalid credentials for usman@gmail.com', '2026-02-14 09:54:03'),
(2, NULL, 'Login Failure', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'k0mum3s1p08q652p4im2lnkuol', 401, 'Invalid credentials for usman@gmail.com', '2026-02-14 09:54:10'),
(3, NULL, 'Login Failure', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'k0mum3s1p08q652p4im2lnkuol', 401, 'Invalid credentials for usman@gmail.com', '2026-02-14 09:54:20'),
(4, NULL, 'Test Event 1771062912', 'UNKNOWN', 'UNKNOWN', 'test_session_id', 200, NULL, '2026-02-14 09:55:12'),
(5, 2, 'Login Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'ma79dco179b7uun9mh5l33uk1s', 200, 'User logged in successfully', '2026-02-14 09:56:49'),
(6, 2, 'Login Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'p3h4ptkso4bagvje3gt0nduc67', 200, 'User logged in successfully', '2026-02-14 11:26:16'),
(7, 2, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'p3h4ptkso4bagvje3gt0nduc67', 200, NULL, '2026-02-14 11:34:59'),
(8, NULL, 'Login Failure', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'p3h4ptkso4bagvje3gt0nduc67', 401, 'Invalid credentials for imran@gmail.com', '2026-02-14 11:35:05'),
(9, 2, 'Login Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '9sl9c87gvdj866nh8uaccm9r0l', 200, 'User logged in successfully', '2026-02-14 11:35:12'),
(10, 2, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '9sl9c87gvdj866nh8uaccm9r0l', 200, NULL, '2026-02-14 12:14:48'),
(11, NULL, 'Login Failure', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '9sl9c87gvdj866nh8uaccm9r0l', 401, 'Invalid credentials for qasim@gmail.com', '2026-02-14 12:14:55'),
(12, 2, 'Login Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'gnttu2omv1kigmq8k9renn9aqj', 200, 'User logged in successfully', '2026-02-14 12:15:06'),
(13, 2, 'Logout', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', 'gnttu2omv1kigmq8k9renn9aqj', 200, NULL, '2026-02-14 12:18:24'),
(14, 2, 'Login Success', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '1024thsqa8nfd40kketiasthil', 200, 'User logged in successfully', '2026-02-14 12:18:33'),
(15, 2, 'Quiz Submitted', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36 Edg/144.0.0.0', '1024thsqa8nfd40kketiasthil', 200, 'Attempt ID: 1', '2026-02-14 12:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) NOT NULL,
  `attempt_time` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question_description` text NOT NULL,
  `correct_answer_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_description`, `correct_answer_index`) VALUES
(6, 'What does PHP stand for?', 2),
(7, 'Which symbol is used to access a property of an object in PHP?', 1),
(8, 'Which of the following is NOT a valid variable name in PHP?', 2),
(9, 'How do you start a session in PHP?', 0),
(10, 'Which superglobal variable holds information about headers, paths, and script locations?', 2);

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `option_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `option_text`, `option_index`) VALUES
(21, 6, 'Personal Home Page', 0),
(22, 6, 'Private Home Page', 1),
(23, 6, 'PHP: Hypertext Preprocessor', 2),
(24, 6, 'Public Hypertext Preprocessor', 3),
(25, 7, '.', 0),
(26, 7, '->', 1),
(27, 7, '::', 2),
(28, 7, '#', 3),
(29, 8, '$my_var', 0),
(30, 8, '$myVar', 1),
(31, 8, '$1myVar', 2),
(32, 8, '$_myVar', 3),
(33, 9, 'session_start()', 0),
(34, 9, 'session_begin()', 1),
(35, 9, 'start_session()', 2),
(36, 9, 'init_session()', 3),
(37, 10, '$_GET', 0),
(38, 10, '$_SESSION', 1),
(39, 10, '$_SERVER', 2),
(40, 10, '$_GLOBALS', 3);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` varchar(50) DEFAULT 'default_quiz',
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `attempt_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `user_id`, `quiz_id`, `score`, `total_questions`, `attempt_timestamp`) VALUES
(1, 2, 'default_quiz', 2, 5, '2026-02-14 12:18:45');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `access` int(10) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `access`, `data`, `created_at`) VALUES
('1024thsqa8nfd40kketiasthil', 1771072201, 'csrf_token|s:64:\"5e3b05b14087097ff865c93893446349e722720e63d91a0eef47f70880305a37\";last_activity|i:1771072201;created|i:1771071513;user_id|i:2;username|s:6:\"sajjad\";', '2026-02-14 12:18:33'),
('ibkmc7d3o61su7decufqbmfmq0', 1771071402, 'csrf_token|s:64:\"0ab1a2a6967da526a18b7152fbb21cd0a6e7c7ca46a2bed172b90099e3b1cfbb\";last_activity|i:1771071402;created|i:1771071402;', '2026-02-14 12:16:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`, `updated_at`) VALUES
(1, 'usman', 'usman@gmail.com', '$2y$10$uXWTcsH4b/8V6x2a5KEm0uoLPp60nMVaEfAllEpQxJsPVSukZigN.', '2026-02-14 09:53:57', '2026-02-14 09:53:57'),
(2, 'sajjad', 'sajjad@gmail.com', '$2y$10$m5Hp7LP5RjXybD/MzbNAW.zwhwCCZKUx1/w1G968qUA9STmZid2bi', '2026-02-14 09:56:43', '2026-02-14 09:56:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attempt_details`
--
ALTER TABLE `attempt_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attempt_id` (`attempt_id`);

--
-- Indexes for table `auth_logs`
--
ALTER TABLE `auth_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ip_address` (`ip_address`,`attempt_time`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `attempt_details`
--
ALTER TABLE `attempt_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `auth_logs`
--
ALTER TABLE `auth_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attempt_details`
--
ALTER TABLE `attempt_details`
  ADD CONSTRAINT `attempt_details_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_logs`
--
ALTER TABLE `auth_logs`
  ADD CONSTRAINT `auth_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `question_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
