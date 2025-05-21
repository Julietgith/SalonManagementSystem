-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2025 at 10:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `salon_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointment_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_amount` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `service_id`, `appointment_date`, `appointment_time`, `status`, `created_at`, `updated_at`, `payment_amount`, `payment_status`, `user_id`) VALUES
(65, 1, '2025-05-20', '12:00:00', 'pending', '2025-05-19 06:39:42', '2025-05-19 06:39:42', 450.00, 'pending', 8),
(66, 2, '2025-05-22', '12:00:00', 'pending', '2025-05-19 06:40:55', '2025-05-19 06:40:55', 450.00, 'pending', 8),
(67, 1, '2025-05-22', '12:00:00', 'completed', '2025-05-19 06:43:09', '2025-05-19 06:50:27', 450.00, 'pending', 8),
(68, 1, '2025-05-21', '12:00:00', 'pending', '2025-05-19 06:45:41', '2025-05-19 06:45:41', 450.00, 'pending', 8),
(69, 1, '2025-05-21', '12:00:00', 'pending', '2025-05-19 06:52:49', '2025-05-19 06:52:49', 450.00, 'pending', 8),
(70, 2, '2025-05-20', '08:00:00', 'pending', '2025-05-19 07:55:45', '2025-05-19 07:55:45', 450.00, 'pending', 10),
(71, 9, '2025-05-20', '08:00:00', 'pending', '2025-05-19 07:57:41', '2025-05-19 07:57:41', 450.00, 'pending', 10),
(72, 6, '2025-05-20', '08:00:00', 'pending', '2025-05-19 07:58:29', '2025-05-19 07:58:29', 300.00, 'pending', 10),
(73, 9, '2025-05-21', '09:00:00', 'pending', '2025-05-19 07:59:14', '2025-05-19 07:59:14', 450.00, 'pending', 11),
(74, 4, '2025-05-21', '09:00:00', 'pending', '2025-05-19 07:59:37', '2025-05-19 07:59:37', 150.00, 'pending', 11);

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `feedback_text` text NOT NULL,
  `submission_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `name`, `email`, `feedback_text`, `submission_date`) VALUES
(1, 'Just', 'julietangcoddinganon@gmail.com', 'Its worth it.', '2025-05-19 23:15:32'),
(2, 'Just', 'julietangcoddinganon@gmail.com', 'Its worth it.', '2025-05-19 23:17:49'),
(3, 'juliet', 'julietangcoddinganon@gmail.com', 'supper good service', '2025-05-19 23:18:23'),
(4, 'mary', 'gaviolamarykris@gmail.com', 'nice well done', '2025-05-19 23:20:28'),
(5, 'mary', 'gaviolamarykris@gmail.com', 'Done!', '2025-05-19 23:20:50'),
(6, 'mary', 'gaviolamarykris@gmail.com', 'Thank you', '2025-05-19 23:21:13'),
(7, 'mary', 'gaviolamarykris@gmail.com', 'Worth it', '2025-05-19 23:21:27'),
(8, 'Ahsa', 'ashacausing@gmailcom', 'Nice job well done ', '2025-05-19 23:22:20'),
(9, 'just', 'julietangcoddinganon@gmail.com', 'job well done', '2025-05-19 09:15:28'),
(10, 'Customer', 'julietangcoddinganon@gmail.com', 'worth it', '2025-05-19 10:15:44');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `expires_at`, `created_at`) VALUES
('julietangcod@gmail.com', '1014d2b381eb49125444bb8e4100403ab85f903a8eb0186d3ebf30ac69b28c6e8effd96ef961c32d7e51a990ead47ca9a2f6', '2025-05-15 05:07:22', '2025-05-15 02:07:22'),
('julietangcod@gmailcom', 'da3de1a9fce9e36221dacab7b7361c8abb51f2b7170c7bc276f746e60f3379a3f31ee8d79fc0ee1ad7ef21fa828d6ce8d3e2', '2025-05-08 12:00:01', '2025-05-08 09:00:01'),
('julietangcoddinganon@gmai.com', '8246e35ebdf58cd1c4f472b388a19b79016ff524722662598bedfc4fe0ea58d6feef01c66cb291615819c166ea48c6aa0b7c', '2025-05-08 12:09:08', '2025-05-08 09:09:08');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) NOT NULL,
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `payment_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `reminder_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_contact` varchar(20) NOT NULL,
  `send_datetime` datetime NOT NULL,
  `email_sent` tinyint(1) DEFAULT 0,
  `sms_sent` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`reminder_id`, `appointment_id`, `message`, `customer_email`, `customer_contact`, `send_datetime`, `email_sent`, `sms_sent`, `created_at`, `updated_at`) VALUES
(2, 52, 'Your appointment is scheduled for 2025-05-18 12:49 PM. We look forward to seeing you!', '', '', '2025-05-18 12:51:52', 0, 0, '2025-05-18 10:51:52', '2025-05-18 10:51:52'),
(5, 52, 'Your appointment is scheduled for 2025-05-17 01:41 PM. We look forward to seeing you!', '', '', '2025-05-18 13:41:46', 1, 0, '2025-05-18 11:41:51', '2025-05-18 11:41:51'),
(6, 52, 'Your appointment is scheduled for 2025-05-17 01:41 PM. We look forward to seeing you!', '', '', '2025-05-18 13:43:08', 1, 0, '2025-05-18 11:43:13', '2025-05-18 11:43:13'),
(7, 53, 'Your appointment is scheduled for 2025-05-17 09:00 AM. We look forward to not seeing you!', '', '', '2025-05-19 07:16:06', 1, 0, '2025-05-19 05:16:11', '2025-05-19 05:16:11'),
(8, 54, 'Your appointment is scheduled for 2026-01-01 12:00 AM. We look forward to not seeing you!', '', '', '2025-05-19 07:17:49', 1, 0, '2025-05-19 05:17:53', '2025-05-19 05:17:53'),
(9, 53, 'Your appointment is scheduled for 2025-05-17 at 09:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:32:42', 1, 0, '2025-05-19 05:32:47', '2025-05-19 05:32:47'),
(10, 53, 'Your appointment is scheduled for 2025-05-17 at 09:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:33:14', 1, 0, '2025-05-19 05:33:18', '2025-05-19 05:33:18'),
(11, 58, 'Your appointment is scheduled for 2025-05-19 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:38:30', 1, 0, '2025-05-19 05:38:35', '2025-05-19 05:38:35'),
(12, 58, 'Your appointment is scheduled for 2025-05-19 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:40:25', 1, 0, '2025-05-19 05:40:30', '2025-05-19 05:40:30'),
(13, 59, 'Your appointment is scheduled for 2025-05-19 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:40:48', 1, 0, '2025-05-19 05:40:54', '2025-05-19 05:40:54'),
(14, 59, 'Your appointment is scheduled for 2025-05-19 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:43:23', 1, 0, '2025-05-19 05:43:28', '2025-05-19 05:43:28'),
(15, 59, 'Your appointment is scheduled for 2025-05-19 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:43:52', 1, 0, '2025-05-19 05:43:57', '2025-05-19 05:43:57'),
(16, 59, 'Your appointment is scheduled for 2025-05-19 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:45:02', 1, 0, '2025-05-19 05:45:07', '2025-05-19 05:45:07'),
(17, 59, 'Your appointment is scheduled for 2025-05-19 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:46:38', 1, 0, '2025-05-19 05:46:43', '2025-05-19 05:46:43'),
(18, 53, 'Your appointment is scheduled for 2025-05-17 at 09:00 AM. We look forward to seeing you!', '', '', '2025-05-19 07:47:03', 1, 0, '2025-05-19 05:47:08', '2025-05-19 05:47:08'),
(19, 65, 'Your appointment is scheduled for 2025-05-20 at 3:00 PM. We look forward to seeing you!', '', '', '2025-05-19 09:54:00', 1, 0, '2025-05-19 07:54:04', '2025-05-19 07:54:04'),
(20, 71, 'Your appointment is scheduled for 2025-05-20 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 10:04:20', 1, 0, '2025-05-19 08:04:24', '2025-05-19 08:04:24'),
(21, 74, 'Your appointment is scheduled for 2025-05-21 at 09:00 AM. We look forward to seeing you!', '', '', '2025-05-19 10:04:42', 1, 0, '2025-05-19 08:04:46', '2025-05-19 08:04:46'),
(22, 69, 'Your appointment is scheduled for 2025-05-21 at 12:00 PM. We look forward to seeing you!', '', '', '2025-05-19 10:05:02', 1, 0, '2025-05-19 08:05:06', '2025-05-19 08:05:06'),
(23, 73, 'Your appointment is scheduled for 2025-05-21 at 09:00 AM. We look forward to seeing you!', '', '', '2025-05-19 10:05:24', 1, 0, '2025-05-19 08:05:29', '2025-05-19 08:05:29'),
(24, 66, 'Your appointment is scheduled for 2025-05-22 at 12:00 PM. We look forward to seeing you!', '', '', '2025-05-19 10:05:58', 1, 0, '2025-05-19 08:06:03', '2025-05-19 08:06:03'),
(25, 72, 'Your appointment is scheduled for 2025-05-20 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 10:06:15', 1, 0, '2025-05-19 08:06:19', '2025-05-19 08:06:19'),
(26, 70, 'Your appointment is scheduled for 2025-05-20 at 08:00 AM. We look forward to seeing you!', '', '', '2025-05-19 10:06:27', 1, 0, '2025-05-19 08:06:31', '2025-05-19 08:06:31'),
(27, 68, 'Your appointment is scheduled for 2025-05-21 at 12:00 PM. We look forward to seeing you!', '', '', '2025-05-19 10:06:51', 1, 0, '2025-05-19 08:06:55', '2025-05-19 08:06:55'),
(28, 65, 'Your appointment is scheduled for 2025-05-20 at 12:00 PM. We look forward to seeing you!', '', '', '2025-05-19 10:11:08', 1, 0, '2025-05-19 08:11:12', '2025-05-19 08:11:12');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `duration` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `name`, `description`, `price`, `created_at`, `updated_at`, `duration`) VALUES
(1, 'Hair Rebond', 'chemical treatment that permanently straightens hair by altering its natural structure.', 1500.00, '2025-05-07 11:14:00', '2025-05-07 11:14:00', 160),
(2, 'Hair Treatment', ' Designed to improve hair health and appearance', 1500.00, '2025-05-07 11:18:11', '2025-05-07 11:18:11', 180),
(3, 'Hair Botox', 'Conditioning treatment', 2000.00, '2025-05-07 11:21:49', '2025-05-19 05:03:40', 200),
(4, 'Manicure/Pedicure', 'Nails Beauty', 500.00, '2025-05-07 11:22:57', '2025-05-07 11:22:57', 90),
(5, 'Facial Treatment', 'Cleansing, Exfoliating, and Moisturizing', 2000.00, '2025-05-07 11:24:49', '2025-05-07 11:24:49', 120),
(6, 'Hair Color with Treatment', 'Color and Treatment', 1000.00, '2025-05-07 11:27:22', '2025-05-07 11:27:22', 120),
(7, 'Gluta Drip / Gluta Push', 'Administering glutathione', 5500.00, '2025-05-07 11:29:13', '2025-05-07 11:29:13', 360),
(8, 'Hair Highlights', 'Adding lighter strands of color to the hair', 800.00, '2025-05-07 11:32:46', '2025-05-07 11:32:46', 120),
(9, 'Hair Extension', 'To boost your natural hair’s length, volume, or texture.', 1500.00, '2025-05-19 20:18:14', '2025-05-19 20:18:14', 180),
(10, 'Blow Out', 'To create smooth, shiny tresses with plenty of volume.', 500.00, '2025-05-19 20:21:30', '2025-05-19 20:21:30', 120);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `reset_code` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `email`, `password`, `contact_number`, `role`, `reset_code`, `reset_token_expiry`, `created_at`, `updated_at`) VALUES
(6, 'Admin', 'Admin', 'julietangcod@gmail.com', '$2y$10$VCIpk5qo8RZqNgUKV4SfoulwHnmTcjrkfpMDIow/5sxPw75w5ahBW', '09554426596', 'admin', '380673', NULL, '2025-05-14 05:33:49', '2025-05-19 08:03:00'),
(8, 'Customer', 'Customer', 'julietangcoddinganon@gmail.com', '$2y$10$8RGZKKzcxOv27UhTQFdYR.8oOugvAt9sgRuJ4Zb9kh275lUqEz63G', '09554426596', 'customer', NULL, NULL, '2025-05-14 05:34:58', '2025-05-19 08:19:59'),
(9, 'Juls', 'July', 'giljel671@gmail.com', '$2y$10$ueKU0h9Z5EKdv6QOmtd0ceIWTd0ycaAsW.JelJfi.o6bdEj/GvJ5u', '09554426596', 'customer', NULL, NULL, '2025-05-16 01:53:39', '2025-05-16 01:53:39'),
(10, 'Marykris ', 'Mary', 'gaviolamarykris@gmail.com', '$2y$10$LSKCO0j7hggRa2KkVAMFIuw78JRIUZSOw/Rh71TGi4xfDPDYGNgfS', '09362445190', 'customer', NULL, NULL, '2025-05-16 09:49:39', '2025-05-16 09:49:39'),
(11, 'Asha Causing', 'Asha', 'ashacausing@gmail.com', '$2y$10$PfqFJ2/m85hjoEPSXAnkU.QN2VCM7KA/ROutU9SzuAIARbZ6mWmWa', '09362445190', 'customer', NULL, NULL, '2025-05-16 09:52:32', '2025-05-16 09:52:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `fk_appointments_user` (`user_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`reminder_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `fk_appointments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
