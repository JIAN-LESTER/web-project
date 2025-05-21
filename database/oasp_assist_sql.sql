-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 05:56 PM
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
-- Database: `oasp_assist_sql`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categoryID` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `conversationID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `conversation_status` enum('pending','answered','not_answered') NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `courseID` bigint(20) UNSIGNED NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`courseID`, `course_name`, `created_at`, `updated_at`) VALUES
(1, 'BS - Information Technology', NULL, NULL),
(2, 'BS - Engineering', NULL, NULL),
(3, 'BS - Nursing', NULL, NULL),
(4, 'BS - Accountancy', NULL, NULL),
(5, 'BS - Agriculture', NULL, NULL),
(6, 'BS - Education', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `knowledge_bases`
--

CREATE TABLE `knowledge_bases` (
  `kbID` bigint(20) UNSIGNED NOT NULL,
  `kb_title` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `embedding` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`embedding`)),
  `categoryID` bigint(20) UNSIGNED NOT NULL,
  `source` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `logID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED DEFAULT NULL,
  `messageID` bigint(20) UNSIGNED DEFAULT NULL,
  `action_type` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`logID`, `userID`, `messageID`, `action_type`, `timestamp`) VALUES
(4, 30, NULL, 'Registered own account. Email verification sent.', '2025-04-26 15:11:23'),
(5, 30, NULL, 'Email Verified', '2025-04-26 15:14:37'),
(6, 30, NULL, 'Sent a password reset link to s.tabarno.jianlester@cmu.edu.ph', '2025-04-26 15:15:21'),
(7, 30, NULL, 'Reset password for s.tabarno.jianlester@cmu.edu.ph', '2025-04-26 15:15:49'),
(8, 24, NULL, 'Attempted to login. 2FA code sent.', '2025-04-26 15:16:07'),
(9, 24, NULL, 'Successful 2FA login', '2025-04-26 15:16:44'),
(10, 24, NULL, 'Sent a password reset link to jianlestertabarno2014@gmail.com', '2025-04-26 15:31:45'),
(11, 24, NULL, 'Sent a password reset link to jianlestertabarno2014@gmail.com', '2025-04-26 15:36:29'),
(12, 24, NULL, 'Sent a password reset link to jianlestertabarno2014@gmail.com', '2025-04-26 15:39:06'),
(13, 24, NULL, 'Reset password for jianlestertabarno2014@gmail.com', '2025-04-26 15:46:25'),
(14, 24, NULL, 'Attempted to login. 2FA code sent.', '2025-04-26 15:46:34'),
(15, 24, NULL, 'Successful 2FA login', '2025-04-26 15:46:51');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `messageID` bigint(20) UNSIGNED NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `kbID` bigint(20) UNSIGNED DEFAULT NULL,
  `conversationID` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `message_status` enum('sent','delivered','read') NOT NULL,
  `sender` varchar(255) NOT NULL,
  `message_type` varchar(255) NOT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_04_19_123352_create_courses_table', 1),
(4, '2025_04_19_123408_create_years_table', 1),
(5, '2025_04_19_123819_create_categories_table', 1),
(6, '2025_04_19_123835_create_knowledge_bases_table', 1),
(7, '2025_04_20_003403_create_users_table', 1),
(8, '2025_04_20_003436_create_reports_table', 1),
(9, '2025_04_20_003503_create_conversations_table', 1),
(10, '2025_04_20_003527_create_messages_table', 1),
(11, '2025_04_20_003538_create_logs_table', 1),
(12, '2025_04_23_044023_add_timestamps_to_conversations_table', 2),
(13, '2025_04_23_045126_add_timestamps_to_messages_table', 3),
(14, '2025_04_23_175536_add_failed_attempts_and_lockout_time_to_users_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `reportID` bigint(20) UNSIGNED NOT NULL,
  `report_title` varchar(255) NOT NULL,
  `report_type` varchar(255) NOT NULL,
  `userID` bigint(20) UNSIGNED NOT NULL,
  `started_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('aZYleyf0KjxtG1qeTYIqr0DrHanUNKhyFDlQwl7o', 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiOWQxYnVNcnJKQXVkanpCNVNFeXd3dVhxU2VzMllzV3I2aGpsT3lwYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMToiMmZhX3VzZXJfaWQiO2k6MjQ7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MjQ7czo1OiJlbWFpbCI7czozMToiamlhbmxlc3RlcnRhYmFybm8yMDE0QGdtYWlsLmNvbSI7fQ==', 1745682419);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `user_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `courseID` bigint(20) UNSIGNED DEFAULT NULL,
  `yearID` bigint(20) UNSIGNED DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token` varchar(255) DEFAULT NULL,
  `two_factor_code` varchar(255) DEFAULT NULL,
  `two_factor_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `failed_attempts` int(11) NOT NULL DEFAULT 0,
  `lockout_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `name`, `email`, `password`, `role`, `user_status`, `courseID`, `yearID`, `avatar`, `is_verified`, `verification_token`, `two_factor_code`, `two_factor_expires_at`, `created_at`, `updated_at`, `failed_attempts`, `lockout_time`) VALUES
(24, 'Jian Lester Tabarnos', 'jianlestertabarno2014@gmail.com', '$2y$12$Sb/mQGsAwaM0Bw6yy4VuC.uCegs9Ip55FQTCFXlNz27gy4MwDIaU6', 'admin', 'active', NULL, 0, 'avatars/default.png', 1, NULL, NULL, NULL, '2025-04-23 10:21:29', '2025-04-26 07:46:51', 0, NULL),
(28, 'Zeldrex', 'inso@gmail.com', '$2y$12$bDvOC8Sr.LAdnTw4YUiyTOP3wbBtiGVJBOyb4u1RgGVLulexBnr16', 'user', 'active', NULL, 0, 'avatars/hibmQ6fNwJiNXWgzGrXYxOMred8lEW26DW9WC329.jpg', 1, NULL, NULL, NULL, '2025-04-25 20:58:40', '2025-04-25 23:52:29', 0, NULL),
(29, 'Jao Beronio', 'jao@gmail.com', '$2y$12$5u6c1iGvyLR/WsQ2iYYyV.yYpHAHbfQic/aX9JhnE8Yr/dtr/O2fG', 'admin', 'active', NULL, 0, 'avatars/default.png', 1, NULL, '681211', '2025-04-25 23:58:37', '2025-04-25 23:53:21', '2025-04-25 23:53:37', 0, NULL),
(30, 'Jian Lester Tabarno', 's.tabarno.jianlester@cmu.edu.ph', '$2y$12$K94Lz0ziOooaIqByqIQ.t.oygThPOpsvvorW4HkSTqKVBKpX6CArS', 'user', 'active', 4, 2, 'avatars/default.png', 1, NULL, NULL, NULL, '2025-04-26 07:11:23', '2025-04-26 07:15:49', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `years`
--

CREATE TABLE `years` (
  `yearID` bigint(20) UNSIGNED NOT NULL,
  `year_level` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `years`
--

INSERT INTO `years` (`yearID`, `year_level`, `created_at`, `updated_at`) VALUES
(0, 'Incoming', NULL, NULL),
(1, '1st year', NULL, NULL),
(2, '2nd year', NULL, NULL),
(3, '3rd year', NULL, NULL),
(4, '4th year', NULL, NULL),
(5, 'Graduate', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`conversationID`),
  ADD KEY `conversations_userid_foreign` (`userID`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`courseID`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `knowledge_bases`
--
ALTER TABLE `knowledge_bases`
  ADD PRIMARY KEY (`kbID`),
  ADD KEY `knowledge_bases_categoryid_foreign` (`categoryID`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`logID`),
  ADD KEY `logs_userid_foreign` (`userID`),
  ADD KEY `logs_messageid_foreign` (`messageID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageID`),
  ADD KEY `messages_userid_foreign` (`userID`),
  ADD KEY `messages_kbid_foreign` (`kbID`),
  ADD KEY `messages_conversationid_foreign` (`conversationID`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`reportID`),
  ADD KEY `reports_userid_foreign` (`userID`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_courseid_foreign` (`courseID`),
  ADD KEY `users_yearid_foreign` (`yearID`);

--
-- Indexes for table `years`
--
ALTER TABLE `years`
  ADD PRIMARY KEY (`yearID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conversationID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `courseID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `knowledge_bases`
--
ALTER TABLE `knowledge_bases`
  MODIFY `kbID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `logID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `messageID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `reportID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `years`
--
ALTER TABLE `years`
  MODIFY `yearID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `knowledge_bases`
--
ALTER TABLE `knowledge_bases`
  ADD CONSTRAINT `knowledge_bases_categoryid_foreign` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`categoryID`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_messageid_foreign` FOREIGN KEY (`messageID`) REFERENCES `messages` (`messageID`) ON DELETE CASCADE,
  ADD CONSTRAINT `logs_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversationid_foreign` FOREIGN KEY (`conversationID`) REFERENCES `conversations` (`conversationID`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_kbid_foreign` FOREIGN KEY (`kbID`) REFERENCES `knowledge_bases` (`kbID`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_userid_foreign` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_courseid_foreign` FOREIGN KEY (`courseID`) REFERENCES `courses` (`courseID`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_yearid_foreign` FOREIGN KEY (`yearID`) REFERENCES `years` (`yearID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
