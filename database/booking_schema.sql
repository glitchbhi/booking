-- ============================================
-- Booking System Database Schema
-- Generated for MySQL
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS `dit04_krishna_prasad_client_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dit04_krishna_prasad_client_db`;

-- ============================================
-- Core Laravel Tables
-- ============================================

-- Users table
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('user','owner','admin') NOT NULL DEFAULT 'user',
  `owner_status` enum('none','pending','approved','rejected') NOT NULL DEFAULT 'none',
  `is_suspended` tinyint(1) NOT NULL DEFAULT 0,
  `suspended_until` timestamp NULL DEFAULT NULL,
  `late_cancel_count` int(11) NOT NULL DEFAULT 0,
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password reset tokens table
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions table
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache table
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache locks table
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs table
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Job batches table
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Failed jobs table
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Application Tables
-- ============================================

-- Sports types table
CREATE TABLE `sports_types` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sports_types_name_unique` (`name`),
  UNIQUE KEY `sports_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Grounds table
CREATE TABLE `grounds` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) UNSIGNED NOT NULL,
  `sport_type_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `rate_per_hour` decimal(10,2) NOT NULL,
  `night_rate_per_hour` decimal(10,2) DEFAULT NULL,
  `capacity` varchar(255) DEFAULT NULL,
  `capacity_description` varchar(255) DEFAULT NULL,
  `day_rate_start` time NOT NULL DEFAULT '06:00:00',
  `day_rate_end` time NOT NULL DEFAULT '18:00:00',
  `night_rate_start` time NOT NULL DEFAULT '18:00:00',
  `night_rate_end` time NOT NULL DEFAULT '22:00:00',
  `images` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `average_rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_bookings` int(11) NOT NULL DEFAULT 0,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grounds_owner_id_is_active_index` (`owner_id`,`is_active`),
  KEY `grounds_sport_type_id_is_active_index` (`sport_type_id`,`is_active`),
  KEY `grounds_location_is_active_index` (`location`,`is_active`),
  KEY `grounds_average_rating_index` (`average_rating`),
  KEY `grounds_capacity_index` (`capacity`),
  CONSTRAINT `grounds_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grounds_sport_type_id_foreign` FOREIGN KEY (`sport_type_id`) REFERENCES `sports_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ground availabilities table
CREATE TABLE `ground_availabilities` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ground_id` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ground_availabilities_ground_id_day_of_week_is_active_index` (`ground_id`,`day_of_week`,`is_active`),
  CONSTRAINT `ground_availabilities_ground_id_foreign` FOREIGN KEY (`ground_id`) REFERENCES `grounds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings table
CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ground_id` bigint(20) UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `duration_hours` decimal(8,2) NOT NULL,
  `rate_per_hour` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `admin_commission` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('booked','ongoing','completed','cancelled') NOT NULL DEFAULT 'booked',
  `booking_type` enum('online','offline') NOT NULL DEFAULT 'online',
  `cancellation_reason` text DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `is_refunded` tinyint(1) NOT NULL DEFAULT 0,
  `refund_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_no_show` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_number_unique` (`booking_number`),
  KEY `bookings_user_id_status_index` (`user_id`,`status`),
  KEY `bookings_ground_id_status_index` (`ground_id`,`status`),
  KEY `bookings_start_time_end_time_index` (`start_time`,`end_time`),
  KEY `bookings_status_index` (`status`),
  KEY `bookings_booking_type_index` (`booking_type`),
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_ground_id_foreign` FOREIGN KEY (`ground_id`) REFERENCES `grounds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Booking locks table
CREATE TABLE `booking_locks` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ground_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `locked_until` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_locks_ground_id_locked_until_index` (`ground_id`,`locked_until`),
  KEY `booking_locks_user_id_locked_until_index` (`user_id`,`locked_until`),
  CONSTRAINT `booking_locks_ground_id_foreign` FOREIGN KEY (`ground_id`) REFERENCES `grounds` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_locks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Wallet transactions table
CREATE TABLE `wallet_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `transaction_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('credit','debit','refund') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance_before` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `metadata` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallet_transactions_transaction_number_unique` (`transaction_number`),
  KEY `wallet_transactions_user_id_type_index` (`user_id`,`type`),
  KEY `wallet_transactions_booking_id_index` (`booking_id`),
  KEY `wallet_transactions_created_at_index` (`created_at`),
  CONSTRAINT `wallet_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_transactions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews table
CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ground_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_user_id_ground_id_unique` (`user_id`,`ground_id`),
  KEY `reviews_ground_id_rating_index` (`ground_id`,`rating`),
  KEY `reviews_user_id_index` (`user_id`),
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ground_id_foreign` FOREIGN KEY (`ground_id`) REFERENCES `grounds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System ratings table
CREATE TABLE `system_ratings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_ratings_user_id_unique` (`user_id`),
  KEY `system_ratings_rating_index` (`rating`),
  CONSTRAINT `system_ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Owner requests table
CREATE TABLE `owner_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ground_name` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `team_size` tinyint(3) UNSIGNED DEFAULT NULL,
  `day_time_start` time DEFAULT '06:00:00',
  `price_day` decimal(10,2) DEFAULT NULL,
  `price_night` decimal(10,2) DEFAULT NULL,
  `night_time_start` time DEFAULT '18:00:00',
  `available_at_night` tinyint(1) NOT NULL DEFAULT 0,
  `ground_images` json DEFAULT NULL,
  `business_address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `business_details` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_requests_user_id_status_index` (`user_id`,`status`),
  KEY `owner_requests_status_index` (`status`),
  CONSTRAINT `owner_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `owner_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migrations table (for Laravel)
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert migration records
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2024_01_31_000001_add_role_fields_to_users_table', 1),
('2024_01_31_000002_create_sports_types_table', 1),
('2024_01_31_000003_create_grounds_table', 1),
('2024_01_31_000004_create_ground_availabilities_table', 1),
('2024_01_31_000005_create_bookings_table', 1),
('2024_01_31_000006_create_booking_locks_table', 1),
('2024_01_31_000007_create_wallet_transactions_table', 1),
('2024_01_31_000008_create_reviews_table', 1),
('2024_01_31_000009_create_owner_requests_table', 1),
('2026_01_31_223735_add_night_rate_to_grounds_table', 1),
('2026_01_31_235853_add_google_fields_to_users_table', 1),
('2026_02_01_195643_add_capacity_and_rate_timing_to_grounds_table', 1),
('2026_02_01_201812_add_phone_to_grounds_table', 1),
('2026_02_04_142920_add_ground_details_to_owner_requests_table', 1),
('2026_02_04_144112_add_business_fields_to_owner_requests_table', 1),
('2026_02_04_144740_add_ground_size_to_owner_requests_table', 1),
('2026_02_04_145022_add_time_slots_to_owner_requests_table', 1),
('2026_02_04_145518_change_ground_size_to_team_size_in_owner_requests_table', 1),
('2026_02_05_125823_update_reviews_table_for_user_based_rating', 1),
('2026_02_05_130441_create_system_ratings_table', 1);

-- ============================================
-- Default Admin User
-- ============================================
-- Email: thunderbooking975@gmail.com
-- Password: Thunder@booking123 (hashed with bcrypt)
INSERT INTO `users` (`name`, `email`, `role`, `password`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('Admin', 'thunderbooking975@gmail.com', 'admin', '$2y$12$Nieliveq67SKnVca0f2xu.Be27TY5b/HVzK3XHYpuKwfWLvPpU2c6', NOW(), NOW(), NOW());

-- ============================================
-- Sample Sports Types
-- ============================================
INSERT INTO `sports_types` (`name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
('Football', 'football', 'Football grounds and pitches', 1, NOW(), NOW()),
('Cricket', 'cricket', 'Cricket grounds and nets', 1, NOW(), NOW()),
('Basketball', 'basketball', 'Basketball courts', 1, NOW(), NOW()),
('Badminton', 'badminton', 'Badminton courts', 1, NOW(), NOW()),
('Tennis', 'tennis', 'Tennis courts', 1, NOW(), NOW()),
('Volleyball', 'volleyball', 'Volleyball courts', 1, NOW(), NOW());

-- ============================================
-- End of Schema
-- ============================================
