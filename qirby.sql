-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2024 at 06:53 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qirby`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_user`
--

CREATE TABLE `data_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_user` varchar(255) NOT NULL,
  `phone_user` varchar(255) NOT NULL,
  `email_user` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `property_id`, `image`, `created_at`, `updated_at`) VALUES
(91, 50, 'uTHBSLPFEpimage (1).png', '2024-05-21 03:10:19', '2024-05-21 03:10:19'),
(92, 51, 'TsRRHyt4QEClient-Win64-Shipping Screenshot 2024.05.23 - 12.24.51.41.png', '2024-05-26 21:00:36', '2024-05-26 21:00:36'),
(94, 54, 'UDnvulwL1K1714146952125.jpeg', '2024-05-26 21:42:49', '2024-05-26 21:42:49'),
(95, 55, 'FWlmLsuSMG1714146952125.jpeg', '2024-05-26 21:43:30', '2024-05-26 21:43:30'),
(97, 57, 'u0TV7f4xH9image (1).png', '2024-05-26 21:47:07', '2024-05-26 21:47:07');

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
(13, '2024_05_04_082803_create_users_table', 1),
(14, '2024_05_04_151244_create_property_table', 1),
(15, '2024_05_04_153939_create_data_user_table', 1),
(16, '2024_05_10_103317_create_images_table', 1),
(17, '2019_12_14_000001_create_personal_access_tokens_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(20) DEFAULT NULL,
  `status` enum('ready','pending','sold') NOT NULL,
  `address` text NOT NULL,
  `lat` decimal(11,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `description` text NOT NULL,
  `sqft` int(11) NOT NULL,
  `bath` int(11) NOT NULL,
  `garage` int(11) NOT NULL,
  `floor` int(11) NOT NULL,
  `bed` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`id`, `name`, `price`, `status`, `address`, `lat`, `lng`, `description`, `sqft`, `bath`, `garage`, `floor`, `bed`, `created_at`, `updated_at`) VALUES
(49, 'alfar', '100.00', 'ready', 'bubat', NULL, NULL, 'asede', 12, 123, 213, 213, 41, '2024-05-21 03:07:44', '2024-05-21 03:07:44'),
(50, 'acheron', '200.00', 'pending', 'izumo', NULL, NULL, 'annihilator', 123, 41, 21, 21, 3, '2024-05-21 03:10:19', '2024-05-21 03:10:19'),
(51, 'taoqi', '200.00', 'pending', 'bogor', NULL, NULL, 'bandung', 3, 7, 4, 6, 5, '2024-05-26 21:00:36', '2024-05-26 21:00:36'),
(54, 'Muhammad Alfarizqi Rabbani', '50.000.000.000', 'ready', 'aaa', NULL, NULL, 'sss', 2, 6, 3, 5, 4, '2024-05-26 21:42:49', '2024-05-26 21:42:49'),
(55, 'taoqi', '20.000.000.000', 'ready', 'asasa', NULL, NULL, 'asas', 1, 6, 3, 5, 4, '2024-05-26 21:43:30', '2024-05-26 21:43:30'),
(56, 'angel selingkuh', '50.000.000.000.', 'ready', 'cina', NULL, NULL, 'selingkuh 5 orang', 2, 151, 3, 14, 4, '2024-05-26 21:45:07', '2024-05-26 21:45:07'),
(57, 'acheron', '50.000.000.000.000', 'ready', 'qwwef', NULL, NULL, 'wefwe', 3, 14, 4, 6, 5, '2024-05-26 21:47:07', '2024-05-26 21:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin123@gmail.com', 'Admin_001', '$2y$10$eQ5eqvR57/WvwdHnZLsWkuuYxBc4q4NbnWcPt1kXzhcSKBEIv1lN.', '', '2024-05-10 04:40:32', '2024-05-10 04:40:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_user`
--
ALTER TABLE `data_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `data_user_email_user_unique` (`email_user`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_user`
--
ALTER TABLE `data_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
