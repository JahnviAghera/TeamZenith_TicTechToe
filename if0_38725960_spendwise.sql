-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql207.infinityfree.com
-- Generation Time: Apr 13, 2025 at 05:36 AM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_38725960_spendwise`
--

-- --------------------------------------------------------

--
-- Table structure for table `budgets`
--

CREATE TABLE `budgets` (
  `budget_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `period` enum('daily','weekly','monthly','yearly') DEFAULT 'monthly',
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `budgets`
--

INSERT INTO `budgets` (`budget_id`, `user_id`, `category_id`, `amount`, `period`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
(6, 6, 1, '10.00', 'weekly', '2025-04-12', '2025-04-30', '2025-04-12 16:47:51', '2025-04-12 16:47:51'),
(5, 3, 4, '300.00', 'monthly', '2025-04-12', '2025-04-30', '2025-04-12 04:05:05', '2025-04-12 04:43:31'),
(4, 3, 2, '1000.00', 'monthly', '2025-04-12', '2025-04-30', '2025-04-12 04:05:05', '2025-04-12 04:43:31'),
(7, 6, 1, '10.00', 'weekly', '2025-04-12', '2025-04-30', '2025-04-12 16:50:03', '2025-04-12 16:50:03'),
(8, 6, 1, '10.00', 'weekly', '2025-04-12', '2025-04-30', '2025-04-12 16:50:31', '2025-04-12 16:50:31'),
(9, 6, 1, '10.00', 'weekly', '2025-04-12', '2025-04-30', '2025-04-12 16:50:31', '2025-04-12 16:50:31'),
(10, 9, 13, '1000.00', 'monthly', '2025-04-12', '2025-04-30', '2025-04-12 17:59:13', '2025-04-12 17:59:13'),
(11, 12, 5, '2000.00', 'monthly', '2025-04-10', '2025-04-30', '2025-04-13 07:01:52', '2025-04-13 07:01:52'),
(12, 12, 3, '80000.00', 'monthly', '2025-04-01', '2025-04-30', '2025-04-13 07:02:54', '2025-04-13 07:02:54'),
(13, 12, 1, '1500.00', 'monthly', '2025-04-08', '0000-00-00', '2025-04-13 07:04:44', '2025-04-13 07:04:44'),
(14, 9, 6, '2000.00', 'monthly', '2025-04-13', '2025-04-30', '2025-04-13 08:32:39', '2025-04-13 08:32:39');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('expense','income') NOT NULL,
  `color` varchar(7) DEFAULT '#000000',
  `icon` varchar(50) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `user_id`, `name`, `description`, `type`, `color`, `icon`, `is_default`, `created_at`) VALUES
(1, 1, 'Food', NULL, 'expense', '#FF6347', 'utensils', 1, '2025-04-12 01:35:06'),
(2, 1, 'Salary', NULL, 'income', '#32CD32', 'money-bill', 1, '2025-04-12 01:35:06'),
(3, 1, 'Rent', 'Housing payments', 'expense', '#8A2BE2', 'home', 1, '2025-04-12 01:35:06'),
(4, 1, 'Utilities', 'Electricity, water, gas', 'expense', '#1E90FF', 'lightbulb', 1, '2025-04-12 01:35:06'),
(5, 1, 'Transportation', 'Fuel, public transport', 'expense', '#FFD700', 'car', 1, '2025-04-12 01:35:06'),
(6, 1, 'Entertainment', 'Movies, concerts', 'expense', '#FF69B4', 'film', 1, '2025-04-12 01:35:06'),
(7, 1, 'Healthcare', 'Medical expenses', 'expense', '#DC143C', 'heart-pulse', 1, '2025-04-12 01:35:06'),
(8, 1, 'Shopping', 'General shopping', 'expense', '#FFA500', 'shopping-cart', 1, '2025-04-12 01:35:06'),
(9, 1, 'Education', 'Courses, books', 'expense', '#4B0082', 'book-open', 1, '2025-04-12 01:35:06'),
(10, 1, 'Travel', 'Vacations, trips', 'expense', '#00BFFF', 'plane', 1, '2025-04-12 01:35:06'),
(11, 1, 'Freelance', 'Freelance income', 'income', '#32CD32', 'laptop-code', 1, '2025-04-12 01:35:06'),
(12, 1, 'Investments', 'Stock dividends', 'income', '#006400', 'chart-line', 1, '2025-04-12 01:35:06'),
(13, 1, 'Gifts', 'Received gifts', 'income', '#FF1493', 'gift', 1, '2025-04-12 01:35:06'),
(14, 1, 'Insurance', 'Insurance payments', 'expense', '#808080', 'shield', 1, '2025-04-12 01:35:06'),
(15, 1, 'Miscellaneous', 'Other expenses', 'expense', '#A9A9A9', 'circle-dot', 1, '2025-04-12 01:35:06'),
(16, 1, 'Side Business', 'Side hustle income', 'income', '#008080', 'store', 1, '2025-04-12 01:35:06'),
(17, 1, 'Rental Income', 'Property rentals', 'income', '#8B4513', 'key', 1, '2025-04-12 01:35:06'),
(18, 3, 'Fixed Expense', NULL, 'expense', '#000000', NULL, 0, '2025-04-12 04:42:25');

-- --------------------------------------------------------

--
-- Table structure for table `financial_goals`
--

CREATE TABLE `financial_goals` (
  `goal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `target_amount` decimal(12,2) NOT NULL,
  `current_amount` decimal(12,2) DEFAULT 0.00,
  `start_date` date NOT NULL,
  `target_date` date DEFAULT NULL,
  `status` enum('in_progress','completed','failed') DEFAULT 'in_progress',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `financial_goals`
--

INSERT INTO `financial_goals` (`goal_id`, `user_id`, `name`, `target_amount`, `current_amount`, `start_date`, `target_date`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Buy a Laptop', '1000.00', '0.00', '2025-01-01', '2025-06-01', 'in_progress', 'Saving for a MacBook Air', '2025-04-11 18:35:06', '2025-04-11 18:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `message`, `type`, `is_read`, `created_at`) VALUES
(1, 1, 'You have reached 90% of your budget for Food!', 'budget_alert', 0, '2025-04-11 18:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `method_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`method_id`, `user_id`, `name`, `description`, `is_default`, `created_at`) VALUES
(1, 1, 'Cash', 'Physical cash payment', 1, '2025-04-11 18:35:06'),
(2, 1, 'Credit Card', 'Visa Credit Card', 0, '2025-04-11 18:35:06'),
(3, 3, 'Cash', NULL, 1, '2025-04-12 02:02:43');

-- --------------------------------------------------------

--
-- Table structure for table `recurring_transactions`
--

CREATE TABLE `recurring_transactions` (
  `recurring_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `transaction_type` enum('expense','income') NOT NULL,
  `frequency` enum('daily','weekly','monthly','yearly') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `next_occurrence` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `recurring_transactions`
--

INSERT INTO `recurring_transactions` (`recurring_id`, `user_id`, `category_id`, `amount`, `description`, `transaction_type`, `frequency`, `start_date`, `end_date`, `next_occurrence`, `payment_method`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '3000.00', 'Monthly Salary', 'income', 'monthly', '2025-01-01', NULL, '2025-05-01', 'Bank Transfer', 1, '2025-04-11 18:35:06', '2025-04-11 18:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `user_id`, `name`, `created_at`) VALUES
(1, 1, 'Food', '2025-04-11 18:35:06'),
(2, 1, 'Salary', '2025-04-11 18:35:06');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `transaction_type` enum('expense','income') NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transaction_id`, `user_id`, `category_id`, `amount`, `description`, `transaction_date`, `transaction_type`, `payment_method`, `receipt_image`, `location`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '15.50', 'Lunch at Subway', '2025-04-10', 'expense', 'Cash', NULL, NULL, NULL, '2025-04-11 18:35:06', '2025-04-11 18:35:06'),
(2, 1, 2, '3000.00', 'Monthly salary', '2025-04-01', 'income', 'Bank Transfer', NULL, NULL, NULL, '2025-04-11 18:35:06', '2025-04-11 18:35:06'),
(4, 3, 2, '200.00', 'Education', '2025-04-11', 'expense', 'Cash', NULL, NULL, NULL, '2025-04-12 04:42:43', '2025-04-12 07:25:15'),
(5, 1, 3, '15.50', 'Meds for Throat Infection', '2025-04-08', 'expense', 'Paytm', NULL, NULL, NULL, '2025-04-08 18:35:06', '2025-04-08 18:35:06'),
(9, 9, 9, '200.00', 'Nachiketa Stationery ', '2025-04-12', 'expense', 'Cash', NULL, NULL, NULL, '2025-04-13 02:25:14', '2025-04-13 02:25:14'),
(10, 9, 13, '500.00', 'Teddy', '2025-04-12', 'expense', 'Mobile Wallet', NULL, NULL, NULL, '2025-04-13 02:35:17', '2025-04-13 02:35:17'),
(8, 9, 1, '120.00', 'Groceries', '2025-04-11', 'expense', 'Credit Card', NULL, NULL, NULL, '2025-04-12 17:54:11', '2025-04-13 02:34:21'),
(11, 12, 5, '500.00', 'Rajkot to Ahemdabad Bus', '2025-04-13', 'expense', 'Cash', NULL, NULL, NULL, '2025-04-13 07:03:46', '2025-04-13 07:03:46'),
(12, 12, 1, '150.00', 'Tick Tech Toe day1', '2025-04-13', 'expense', 'Cash', NULL, NULL, NULL, '2025-04-13 07:05:42', '2025-04-13 07:05:42'),
(13, 9, 6, '1000.00', 'Disney +', '2025-04-13', 'expense', 'Net Banking', NULL, NULL, NULL, '2025-04-13 08:33:27', '2025-04-13 08:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_tags`
--

CREATE TABLE `transaction_tags` (
  `transaction_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `transaction_tags`
--

INSERT INTO `transaction_tags` (`transaction_id`, `tag_id`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'USD',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `photo` longblob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `profile_image`, `currency`, `created_at`, `updated_at`, `photo`) VALUES
(1, 'jdoe', 'jdoe@example.com', 'hashed_pass_123', 'John', 'Doe', NULL, 'USD', '2025-04-11 18:35:06', '2025-04-11 18:35:06', ''),
(2, 'jane', 'jane@example.com', 'hashed_pass_456', 'Jane', 'Smith', NULL, 'USD', '2025-04-11 18:35:06', '2025-04-11 18:35:06', ''),
(10, 'Payal-Makwana', 'payalmakwana.122828@marwadiuniversity.ac.in', '$2y$10$BkOp.sc25kPgP5iotEOZt.lyiDsdhZhcWZT13XUvBLNQ9SUw11Tfu', 'Payal', 'Makwana', NULL, 'USD', '2025-04-12 18:10:38', '2025-04-12 18:10:38', ''),
(9, 'jha', 'jahnviaghera@gmail.com', '$2y$10$zZiBPAndd2dDTqao28jnzeeu0LqDH0wuXO/LbzdAwOJ0ZL5wW6XP6', 'Jahnvi', 'Aghera', NULL, 'USD', '2025-04-12 17:24:32', '2025-04-12 17:24:32', ''),
(7, 'Aditya', 'adivid198986@gmail.com', '$2y$10$yK4w5KVP2PpXKCackXHAO.g7JzDXzA5yzbqxtHDBgUdnXGyd/zVaa', 'Aditya', 'Raj', NULL, 'USD', '2025-04-12 09:58:13', '2025-04-12 09:58:13', ''),
(6, 'Payal', 'iampayal018@gmail.com', '$2y$10$fPCCXd5BkDvbFakVOiEYkuRer0gvNmbM.HSPoUwQCiXTOToM38ne6', 'Payal', 'Makwana', 'uploads/profile_pictures/user_6_1744469742.png', 'USD', '2025-04-12 08:12:51', '2025-04-12 14:55:43', ''),
(11, 'Payal Makwana', 'payalmakwana.1228280@marwadiuniversity.ac.in', '$2y$10$Yf5eFmYJKvH/f60PkvN/0O.bRvjfMVcZbaHjBk0c6Z0g9Ri/kjx8G', 'Payal', 'Makwana', NULL, 'USD', '2025-04-12 18:12:48', '2025-04-12 18:12:48', ''),
(12, 'JahnviAghera', 'agherajahnvi@gmail.com', '$2y$10$g9IaxHvoF9pWDdNOKW4VIOsyeEOR7TWQ.UkaG3HDh2vKxA2o0YCzi', 'Jahnvi', 'Aghera', NULL, 'INR', '2025-04-13 06:59:59', '2025-04-13 07:04:07', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `setting_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language` varchar(10) DEFAULT 'en',
  `theme` varchar(20) DEFAULT 'light',
  `notification_preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`setting_id`, `user_id`, `language`, `theme`, `notification_preferences`, `dashboard_preferences`, `created_at`, `updated_at`) VALUES
(1, 1, 'en', 'dark', '{\"email\":true,\"sms\":false}', '{\"showCharts\":true,\"currency\":\"USD\"}', '2025-04-11 18:35:06', '2025-04-11 18:35:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`budget_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `financial_goals`
--
ALTER TABLE `financial_goals`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`method_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  ADD PRIMARY KEY (`recurring_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `transaction_tags`
--
ALTER TABLE `transaction_tags`
  ADD PRIMARY KEY (`transaction_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `budget_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `financial_goals`
--
ALTER TABLE `financial_goals`
  MODIFY `goal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recurring_transactions`
--
ALTER TABLE `recurring_transactions`
  MODIFY `recurring_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
