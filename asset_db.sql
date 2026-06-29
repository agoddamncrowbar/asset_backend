-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 29, 2026 at 10:15 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asset_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
CREATE TABLE IF NOT EXISTS `assets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asset_tag` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` int NOT NULL,
  `department_id` int NOT NULL,
  `location_id` int NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `purchase_cost` decimal(12,2) DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_status` enum('excellent','good','fair','poor','damaged') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'good',
  `asset_status` enum('available','assigned','maintenance','retired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asset_tag` (`asset_tag`),
  UNIQUE KEY `serial_number` (`serial_number`),
  KEY `fk_assets_category` (`category_id`),
  KEY `fk_assets_department` (`department_id`),
  KEY `fk_assets_location` (`location_id`),
  KEY `fk_assets_created_by` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`id`, `asset_tag`, `serial_number`, `item_name`, `description`, `category_id`, `department_id`, `location_id`, `purchase_date`, `purchase_cost`, `supplier`, `condition_status`, `asset_status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'ASSET-2-1-2026-000001', 'DL5420-9X21', 'Dell Latitude 5420', 'Office laptop for staff use', 1, 2, 1, NULL, NULL, NULL, 'good', 'assigned', 1, '2026-06-14 19:15:36', '2026-06-23 12:00:18'),
(2, 'ASSET-2-1-2026-000002', 'TP-T14-2026-88421', 'Lenovo ThinkPad T14 Gen 3 - Updated', 'Updated assignment: engineering lead machine', 1, 2, 1, '2026-01-10', 1250.00, 'Lenovo Authorized Reseller', 'excellent', 'available', 1, '2026-06-14 19:16:34', '2026-06-25 17:53:30'),
(3, 'USIU-2-5-2026-000003', '65464 7455 5544 5745', '6 Way Extension', '6 way power extension', 5, 2, 1, '2026-01-10', 3500.00, 'Power Group Nairobi', 'excellent', 'available', 1, '2026-06-25 11:52:24', '2026-06-25 17:06:07'),
(4, 'USIU-2-1-2026-000004', 'INGCO 038 408342', 'Computer Dust Blower', 'Heavy duty dust blower', 5, 2, 1, '2026-01-10', 7500.00, 'Power Group Nairobi', 'excellent', 'available', 1, '2026-06-25 12:02:10', '2026-06-29 09:57:35'),
(5, 'USIU-4-5-2026-000005', '34235ff', 'tesst', 'fsbg ', 5, 2, 3, '2026-06-23', 20.00, 'testt', 'excellent', 'available', 1, '2026-06-25 12:06:47', '2026-06-29 10:12:46');

-- --------------------------------------------------------

--
-- Table structure for table `asset_assignments`
--

DROP TABLE IF EXISTS `asset_assignments`;
CREATE TABLE IF NOT EXISTS `asset_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asset_id` int NOT NULL,
  `assigned_to` bigint NOT NULL,
  `assigned_by` bigint NOT NULL,
  `assigned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expected_return_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `returned_at` datetime DEFAULT NULL,
  `return_notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','returned','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_asset` (`asset_id`),
  KEY `idx_assigned_to` (`assigned_to`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_assignments`
--

INSERT INTO `asset_assignments` (`id`, `asset_id`, `assigned_to`, `assigned_by`, `assigned_at`, `expected_return_date`, `notes`, `returned_at`, `return_notes`, `status`, `created_at`) VALUES
(1, 1, 2, 1, '2026-06-15 08:56:02', '2026-07-15', 'Testing assignment system', '2026-06-15 09:03:46', NULL, 'returned', '2026-06-15 05:56:02'),
(2, 2, 2, 1, '2026-06-15 08:57:20', '2026-07-15', 'Testing assignment system', '2026-06-15 09:04:46', NULL, 'returned', '2026-06-15 05:57:20'),
(3, 2, 2, 1, '2026-06-15 09:04:26', '2026-07-15', 'Testing assignment system', '2026-06-15 09:06:50', NULL, 'returned', '2026-06-15 06:04:26'),
(4, 2, 2, 1, '2026-06-15 09:07:50', '2026-07-15', 'Testing assignment system', '2026-06-15 09:14:39', NULL, 'returned', '2026-06-15 06:07:50'),
(5, 2, 2, 1, '2026-06-15 09:14:48', '2026-07-15', 'Testing assignment system', '2026-06-15 09:22:12', NULL, 'returned', '2026-06-15 06:14:48'),
(6, 2, 2, 1, '2026-06-15 09:22:20', '2026-07-15', 'Testing assignment system', '2026-06-25 20:53:30', 'test from UI', 'returned', '2026-06-15 06:22:20'),
(16, 1, 6, 1, '2026-06-15 12:00:08', '2026-07-15', 'Testing assignment system', '2026-06-15 12:12:17', 'Device has visible wear on casing', 'returned', '2026-06-15 09:00:08'),
(17, 1, 6, 1, '2026-06-17 15:11:01', '2026-07-15', 'Testing assignment system', '2026-06-17 15:16:05', NULL, 'returned', '2026-06-17 12:11:01'),
(18, 1, 6, 1, '2026-06-17 15:24:17', '2026-07-15', 'Testing assignment system', '2026-06-17 15:24:44', NULL, 'returned', '2026-06-17 12:24:17'),
(19, 1, 7, 1, '2026-06-17 15:26:42', NULL, 'Created from approved request', '2026-06-17 15:30:00', NULL, 'returned', '2026-06-17 12:26:42'),
(20, 1, 6, 1, '2026-06-18 15:23:25', '2026-07-15', 'Testing assignment system', '2026-06-18 18:28:10', NULL, 'returned', '2026-06-18 12:23:25'),
(21, 1, 8, 1, '2026-06-18 18:32:30', '2026-07-15', 'Testing assignment system', '2026-06-18 18:33:15', NULL, 'returned', '2026-06-18 15:32:30'),
(22, 1, 8, 1, '2026-06-18 18:37:34', '2026-07-15', 'Testing assignment system', '2026-06-18 18:37:41', NULL, 'returned', '2026-06-18 15:37:34'),
(23, 1, 6, 1, '2026-06-18 18:39:07', '2026-07-15', 'Testing assignment system', '2026-06-18 18:39:22', NULL, 'returned', '2026-06-18 15:39:07'),
(24, 1, 8, 1, '2026-06-18 18:44:36', '2026-07-15', 'Testing assignment system', '2026-06-18 18:44:47', NULL, 'returned', '2026-06-18 15:44:36'),
(25, 1, 8, 1, '2026-06-18 18:45:53', '2026-07-15', 'Testing assignment system', '2026-06-18 18:47:00', NULL, 'returned', '2026-06-18 15:45:53'),
(26, 1, 8, 1, '2026-06-18 18:48:07', '2026-07-15', 'Testing assignment system', '2026-06-18 18:48:46', NULL, 'returned', '2026-06-18 15:48:07'),
(27, 1, 8, 1, '2026-06-18 19:02:03', '2026-07-15', 'Testing assignment system', '2026-06-18 19:02:34', NULL, 'returned', '2026-06-18 16:02:03'),
(28, 1, 8, 1, '2026-06-18 19:04:24', '2026-07-15', 'Testing assignment system', '2026-06-18 19:04:29', NULL, 'returned', '2026-06-18 16:04:24'),
(29, 1, 8, 1, '2026-06-18 19:06:30', '2026-07-15', 'Testing assignment system', '2026-06-18 19:07:11', NULL, 'returned', '2026-06-18 16:06:30'),
(30, 1, 8, 1, '2026-06-18 19:08:17', '2026-07-15', 'Testing assignment system', '2026-06-18 19:08:31', NULL, 'returned', '2026-06-18 16:08:17'),
(31, 1, 8, 1, '2026-06-18 19:08:39', '2026-07-15', 'Testing assignment system', '2026-06-18 19:10:15', 'Returned in good condition', 'returned', '2026-06-18 16:08:39'),
(32, 1, 8, 1, '2026-06-18 19:11:25', '2026-07-15', 'Testing assignment system', '2026-06-18 19:11:42', 'Returned in good condition', 'returned', '2026-06-18 16:11:25'),
(33, 1, 6, 1, '2026-06-18 19:11:42', NULL, 'Created from approved request', '2026-06-18 19:17:11', 'Returned in good condition', 'returned', '2026-06-18 16:11:42'),
(34, 1, 6, 1, '2026-06-18 19:19:52', '2026-07-15', 'Testing assignment system', '2026-06-18 19:20:00', 'Returned in good condition', 'returned', '2026-06-18 16:19:52'),
(35, 1, 7, 1, '2026-06-18 19:20:01', NULL, 'Created from approved request', '2026-06-18 19:20:28', 'Returned in good condition', 'returned', '2026-06-18 16:20:01'),
(36, 1, 8, 1, '2026-06-23 15:00:18', '2026-07-15', 'Testing assignment system', NULL, NULL, 'active', '2026-06-23 12:00:18'),
(37, 4, 6, 1, '2026-06-25 15:45:15', '2026-06-26', 'Test nite', NULL, NULL, 'active', '2026-06-25 12:45:15'),
(38, 5, 6, 1, '2026-06-25 16:03:41', '2026-06-29', 'test', '2026-06-25 20:53:15', 'Test from UI', 'returned', '2026-06-25 13:03:41'),
(39, 3, 6, 1, '2026-06-25 16:21:59', '2026-06-26', '', '2026-06-25 20:06:07', 'Returned in good condition', 'returned', '2026-06-25 13:21:59');

-- --------------------------------------------------------

--
-- Table structure for table `asset_categories`
--

DROP TABLE IF EXISTS `asset_categories`;
CREATE TABLE IF NOT EXISTS `asset_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `depreciation_period` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_categories`
--

INSERT INTO `asset_categories` (`id`, `name`, `description`, `depreciation_period`, `created_at`, `updated_at`) VALUES
(1, 'Laptops', 'Portable computing devices used for staff and students', 36, '2026-06-14 17:18:36', '2026-06-14 17:18:36'),
(5, 'Extensions', 'Power delivery systems', 12, '2026-06-25 09:53:05', '2026-06-25 09:53:05');

-- --------------------------------------------------------

--
-- Table structure for table `asset_inspections`
--

DROP TABLE IF EXISTS `asset_inspections`;
CREATE TABLE IF NOT EXISTS `asset_inspections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `inspection_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_date` datetime NOT NULL,
  `completed_date` datetime DEFAULT NULL,
  `status` enum('scheduled','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int NOT NULL,
  `completed_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inspection_code` (`inspection_code`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_inspections`
--

INSERT INTO `asset_inspections` (`id`, `inspection_code`, `scheduled_date`, `completed_date`, `status`, `notes`, `created_by`, `completed_by`, `created_at`, `updated_at`) VALUES
(1, 'INSP-20260618-2036', '2026-06-20 10:00:00', '2026-06-18 16:59:05', 'completed', 'Quarterly inspection', 1, 1, '2026-06-18 16:53:40', '2026-06-18 16:59:05'),
(9, 'INSP-20260626-3108', '2026-06-26 08:00:00', '2026-06-26 12:10:19', 'completed', 'Test to begin maintenance of assets', 1, 1, '2026-06-26 12:10:12', '2026-06-26 12:10:19'),
(8, 'INSP-20260626-7420', '2026-06-26 17:00:00', '2026-06-26 10:35:02', 'completed', 'Test System from frontend', 1, 1, '2026-06-26 10:34:54', '2026-06-26 10:35:02'),
(7, 'INSP-20260626-9911', '2026-06-20 10:00:00', '2026-06-26 09:36:28', 'completed', 'Quarterly inspection', 1, 1, '2026-06-26 08:54:14', '2026-06-26 09:36:28');

-- --------------------------------------------------------

--
-- Table structure for table `asset_inspection_assets`
--

DROP TABLE IF EXISTS `asset_inspection_assets`;
CREATE TABLE IF NOT EXISTS `asset_inspection_assets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `inspection_id` int NOT NULL,
  `asset_id` int NOT NULL,
  `result` enum('ok','needs_repair','damaged','retire') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condition_after` enum('excellent','good','fair','poor','damaged') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_inspection_asset` (`inspection_id`,`asset_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_inspection_assets`
--

INSERT INTO `asset_inspection_assets` (`id`, `inspection_id`, `asset_id`, `result`, `condition_after`, `remarks`, `created_at`) VALUES
(1, 1, 1, 'ok', 'good', 'No issues found', '2026-06-18 16:53:40'),
(2, 1, 2, 'needs_repair', 'fair', 'Battery swelling detected, overheating under load', '2026-06-18 16:53:40'),
(4, 2, 1, NULL, NULL, NULL, '2026-06-26 08:49:05'),
(5, 2, 2, NULL, NULL, NULL, '2026-06-26 08:49:05'),
(6, 3, 1, NULL, NULL, NULL, '2026-06-26 08:51:11'),
(7, 3, 2, NULL, NULL, NULL, '2026-06-26 08:51:11'),
(8, 4, 1, NULL, NULL, NULL, '2026-06-26 08:51:15'),
(9, 4, 2, NULL, NULL, NULL, '2026-06-26 08:51:15'),
(10, 5, 1, NULL, NULL, NULL, '2026-06-26 08:51:16'),
(11, 5, 2, NULL, NULL, NULL, '2026-06-26 08:51:16'),
(12, 6, 1, NULL, NULL, NULL, '2026-06-26 08:51:17'),
(13, 6, 2, NULL, NULL, NULL, '2026-06-26 08:51:17'),
(14, 7, 1, 'ok', 'good', '', '2026-06-26 08:54:14'),
(15, 7, 2, 'ok', 'excellent', '', '2026-06-26 08:54:14'),
(16, 8, 4, 'needs_repair', 'damaged', 'Send to maintenance', '2026-06-26 10:34:54'),
(17, 8, 5, 'ok', 'excellent', 'Great condition', '2026-06-26 10:34:54'),
(18, 9, 5, 'ok', 'fair', 'Meh', '2026-06-26 12:10:12'),
(19, 9, 2, 'retire', NULL, NULL, '2026-06-26 12:10:12');

-- --------------------------------------------------------

--
-- Table structure for table `asset_locations`
--

DROP TABLE IF EXISTS `asset_locations`;
CREATE TABLE IF NOT EXISTS `asset_locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `building` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_locations`
--

INSERT INTO `asset_locations` (`id`, `name`, `building`, `room_number`, `description`, `created_at`, `updated_at`) VALUES
(1, 'General Lab', 'Lilian Beam', 'GLab', 'General computer lab used for practical sessions', '2026-06-14 17:31:42', '2026-06-14 17:31:42'),
(3, 'Software Lab', 'Lilian Beam', 'SLAB', 'Software computer lab for developing and testing software', '2026-06-25 10:18:55', '2026-06-25 10:18:55');

-- --------------------------------------------------------

--
-- Table structure for table `asset_maintenance_jobs`
--

DROP TABLE IF EXISTS `asset_maintenance_jobs`;
CREATE TABLE IF NOT EXISTS `asset_maintenance_jobs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asset_id` int NOT NULL,
  `inspection_id` int DEFAULT NULL,
  `status` enum('queued','in_progress','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'queued',
  `priority` enum('low','medium','high') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `issue_report` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `assigned_to` int DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `resolution_notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_maintenance_jobs`
--

INSERT INTO `asset_maintenance_jobs` (`id`, `asset_id`, `inspection_id`, `status`, `priority`, `issue_report`, `assigned_to`, `started_at`, `completed_at`, `resolution_notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'completed', 'medium', 'Battery swelling detected, overheating under load', 7, '2026-06-19 18:25:05', '2026-06-19 18:26:26', 'Replaced battery and thermal paste', 1, '2026-06-18 16:59:05', '2026-06-28 08:42:22'),
(2, 2, 9, 'completed', 'medium', '', 6, '2026-06-28 07:01:44', '2026-06-28 07:26:30', 'Job completed', 1, '2026-06-26 12:22:41', '2026-06-28 07:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `asset_requests`
--

DROP TABLE IF EXISTS `asset_requests`;
CREATE TABLE IF NOT EXISTS `asset_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asset_id` int NOT NULL,
  `requested_by` int NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` enum('queued','approved','rejected','cancelled','fulfilled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'queued',
  `queue_position` int NOT NULL,
  `requested_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `processed_by` int DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_active_request_per_user_asset` (`asset_id`,`requested_by`,`status`),
  KEY `idx_asset_queue` (`asset_id`,`queue_position`),
  KEY `idx_asset_status` (`asset_id`,`status`),
  KEY `idx_requested_by` (`requested_by`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `asset_requests`
--

INSERT INTO `asset_requests` (`id`, `asset_id`, `requested_by`, `reason`, `status`, `queue_position`, `requested_at`, `updated_at`, `processed_by`, `processed_at`) VALUES
(10, 1, 6, 'Need for work B', 'approved', 1, '2026-06-29 12:14:17', '2026-06-29 12:15:06', 1, '2026-06-29 12:15:06'),
(11, 1, 7, 'Need for work A', 'queued', 2, '2026-06-29 12:31:48', NULL, NULL, NULL),
(9, 1, 7, 'Need for work A', 'fulfilled', 2, '2026-06-18 18:46:10', '2026-06-18 19:20:01', 1, '2026-06-18 18:46:47'),
(8, 1, 6, 'Need for work B', 'fulfilled', 1, '2026-06-18 18:46:04', '2026-06-18 19:11:42', 1, '2026-06-18 18:48:22');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_action` (`action`),
  KEY `idx_entity_type` (`entity_type`),
  KEY `idx_entity_id` (`entity_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_values`, `new_values`, `metadata`, `ip_address`, `user_agent`, `created_at`) VALUES
(27, NULL, 'UPDATE', 'Asset', '2', '{\"id\": 2, \"supplier\": \"Lenovo Authorized Reseller\", \"asset_tag\": \"ASSET-2-1-2026-000002\", \"item_name\": \"Lenovo ThinkPad T14 Gen 3 - Updated\", \"created_at\": \"2026-06-14 22:16:34\", \"created_by\": 1, \"updated_at\": \"2026-06-14 22:26:03\", \"category_id\": 1, \"description\": \"Updated assignment: engineering lead machine\", \"location_id\": 1, \"asset_status\": \"assigned\", \"department_id\": 2, \"purchase_cost\": \"1250.00\", \"purchase_date\": \"2026-01-10\", \"serial_number\": \"TP-T14-2026-88421\", \"condition_status\": \"good\"}', '{\"id\": 2, \"supplier\": \"Lenovo Authorized Reseller\", \"asset_tag\": \"ASSET-2-1-2026-000002\", \"item_name\": \"Lenovo ThinkPad T14 Gen 3 - Updated\", \"created_at\": \"2026-06-14 22:16:34\", \"created_by\": 1, \"updated_at\": \"2026-06-14 22:26:53\", \"category_id\": 1, \"description\": \"Updated assignment: engineering lead machine\", \"location_id\": 1, \"asset_status\": \"retired\", \"department_id\": 2, \"purchase_cost\": \"1250.00\", \"purchase_date\": \"2026-01-10\", \"serial_number\": \"TP-T14-2026-88421\", \"condition_status\": \"good\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 19:26:53'),
(26, NULL, 'UPDATE', 'Asset', '2', '{\"id\": 2, \"supplier\": \"Lenovo Authorized Reseller\", \"asset_tag\": \"ASSET-2-1-2026-000002\", \"item_name\": \"Lenovo ThinkPad T14 Gen 3\", \"created_at\": \"2026-06-14 22:16:34\", \"created_by\": 1, \"updated_at\": \"2026-06-14 22:16:34\", \"category_id\": 1, \"description\": \"Development laptop assigned to engineering team\", \"location_id\": 1, \"asset_status\": \"available\", \"department_id\": 2, \"purchase_cost\": \"1200.00\", \"purchase_date\": \"2026-01-10\", \"serial_number\": \"TP-T14-2026-88421\", \"condition_status\": \"excellent\"}', '{\"id\": 2, \"supplier\": \"Lenovo Authorized Reseller\", \"asset_tag\": \"ASSET-2-1-2026-000002\", \"item_name\": \"Lenovo ThinkPad T14 Gen 3 - Updated\", \"created_at\": \"2026-06-14 22:16:34\", \"created_by\": 1, \"updated_at\": \"2026-06-14 22:26:03\", \"category_id\": 1, \"description\": \"Updated assignment: engineering lead machine\", \"location_id\": 1, \"asset_status\": \"assigned\", \"department_id\": 2, \"purchase_cost\": \"1250.00\", \"purchase_date\": \"2026-01-10\", \"serial_number\": \"TP-T14-2026-88421\", \"condition_status\": \"good\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 19:26:03'),
(25, NULL, 'CREATE', 'Asset', '2', NULL, '{\"id\": 2, \"supplier\": \"Lenovo Authorized Reseller\", \"asset_tag\": \"ASSET-2-1-2026-000002\", \"item_name\": \"Lenovo ThinkPad T14 Gen 3\", \"created_at\": \"2026-06-14 22:16:34\", \"created_by\": 1, \"updated_at\": \"2026-06-14 22:16:34\", \"category_id\": 1, \"description\": \"Development laptop assigned to engineering team\", \"location_id\": 1, \"asset_status\": \"available\", \"department_id\": 2, \"purchase_cost\": \"1200.00\", \"purchase_date\": \"2026-01-10\", \"serial_number\": \"TP-T14-2026-88421\", \"condition_status\": \"excellent\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 19:16:34'),
(24, NULL, 'CREATE', 'Asset', '1', NULL, '{\"id\": 1, \"supplier\": null, \"asset_tag\": \"ASSET-2-1-2026-000001\", \"item_name\": \"Dell Latitude 5420\", \"created_at\": \"2026-06-14 22:15:36\", \"created_by\": 1, \"updated_at\": \"2026-06-14 22:15:36\", \"category_id\": 1, \"description\": \"Office laptop for staff use\", \"location_id\": 1, \"asset_status\": \"available\", \"department_id\": 2, \"purchase_cost\": null, \"purchase_date\": null, \"serial_number\": \"DL5420-9X21\", \"condition_status\": \"good\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 19:15:36'),
(22, 1, 'CREATE', 'AssetCategory', '3', NULL, '{\"id\": 3, \"name\": \"Laptops\", \"created_at\": \"2026-06-14 20:25:14\", \"updated_at\": \"2026-06-14 20:25:14\", \"description\": \"Portable computing devices used for staff and students\", \"depreciation_period\": 36}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 17:25:14'),
(23, 1, 'CREATE', 'AssetLocation', '1', NULL, '{\"id\": 1, \"name\": \"General Lab\", \"building\": \"Lilian Beam\", \"created_at\": \"2026-06-14 20:31:42\", \"updated_at\": \"2026-06-14 20:31:42\", \"description\": \"General computer lab used for practical sessions\", \"room_number\": \"GLab\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 17:31:42'),
(19, 1, 'DELETE', 'Department', '1', '{\"id\": 1, \"code\": \"CS\", \"name\": \"Computer Science\", \"created_at\": \"2026-06-14 20:09:44\", \"updated_at\": \"2026-06-14 20:09:44\", \"description\": \"Handles CS programs\"}', NULL, NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 17:13:58'),
(20, 1, 'CREATE', 'AssetCategory', '1', NULL, '{\"id\": 1, \"name\": \"Laptops\", \"created_at\": \"2026-06-14 20:18:36\", \"updated_at\": \"2026-06-14 20:18:36\", \"description\": \"Portable computing devices used for staff and students\", \"depreciation_period\": 36}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 17:18:36'),
(21, 1, 'CREATE', 'AssetCategory', '2', NULL, '{\"id\": 2, \"name\": \"Laptops\", \"created_at\": \"2026-06-14 20:18:48\", \"updated_at\": \"2026-06-14 20:18:48\", \"description\": \"Portable computing devices used for staff and students\", \"depreciation_period\": 36}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 17:18:48'),
(17, 1, 'DELETE', 'User', '5', '{\"id\": 5, \"role\": \"student\", \"email\": \"tdoe@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Ndambuki\", \"created_at\": \"2026-06-14 19:40:21\", \"first_name\": \"Daniel\", \"updated_at\": \"2026-06-14 19:41:59\", \"password_hash\": \"$2y$10$LTntEYmZ5EcH51bd.liYsOcioFobJM.J95u3XSw9L76mYfGUoUShW\", \"university_id\": \"665842\", \"must_change_password\": 1}', NULL, NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 16:42:53'),
(18, 1, 'CREATE', 'Department', '2', NULL, '{\"id\": 2, \"code\": \"SST\", \"name\": \"School of Science and Technology\", \"created_at\": \"2026-06-14 20:12:52\", \"updated_at\": \"2026-06-14 20:12:52\", \"description\": \"Department of Science and Technology\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 17:12:52'),
(16, 1, 'UPDATE', 'User', '5', '{\"id\": 5, \"role\": \"student\", \"email\": \"tdoe@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"suspended\", \"last_name\": \"Ndambuki\", \"created_at\": \"2026-06-14 19:40:21\", \"first_name\": \"Daniel\", \"updated_at\": \"2026-06-14 19:41:22\", \"password_hash\": \"$2y$10$LTntEYmZ5EcH51bd.liYsOcioFobJM.J95u3XSw9L76mYfGUoUShW\", \"university_id\": \"665842\", \"must_change_password\": 1}', '{\"id\": 5, \"role\": \"student\", \"email\": \"tdoe@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Ndambuki\", \"created_at\": \"2026-06-14 19:40:21\", \"first_name\": \"Daniel\", \"updated_at\": \"2026-06-14 19:41:59\", \"password_hash\": \"$2y$10$LTntEYmZ5EcH51bd.liYsOcioFobJM.J95u3XSw9L76mYfGUoUShW\", \"university_id\": \"665842\", \"must_change_password\": 1}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 16:41:59'),
(14, 1, 'CREATE', 'User', '5', NULL, '{\"id\": 5, \"role\": \"student\", \"email\": \"tdoe@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Ndambuki\", \"created_at\": \"2026-06-14 19:40:21\", \"first_name\": \"Daniel\", \"updated_at\": \"2026-06-14 19:40:21\", \"password_hash\": \"$2y$10$LTntEYmZ5EcH51bd.liYsOcioFobJM.J95u3XSw9L76mYfGUoUShW\", \"university_id\": \"665842\", \"must_change_password\": 1}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 16:40:21'),
(15, 1, 'UPDATE', 'User', '5', '{\"id\": 5, \"role\": \"student\", \"email\": \"tdoe@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Ndambuki\", \"created_at\": \"2026-06-14 19:40:21\", \"first_name\": \"Daniel\", \"updated_at\": \"2026-06-14 19:40:21\", \"password_hash\": \"$2y$10$LTntEYmZ5EcH51bd.liYsOcioFobJM.J95u3XSw9L76mYfGUoUShW\", \"university_id\": \"665842\", \"must_change_password\": 1}', '{\"id\": 5, \"role\": \"student\", \"email\": \"tdoe@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"suspended\", \"last_name\": \"Ndambuki\", \"created_at\": \"2026-06-14 19:40:21\", \"first_name\": \"Daniel\", \"updated_at\": \"2026-06-14 19:41:22\", \"password_hash\": \"$2y$10$LTntEYmZ5EcH51bd.liYsOcioFobJM.J95u3XSw9L76mYfGUoUShW\", \"university_id\": \"665842\", \"must_change_password\": 1}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-14 16:41:22'),
(28, NULL, 'CREATE', 'Assignment', '1', NULL, '{\"id\": 1, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 08:56:02\", \"assigned_at\": \"2026-06-15 08:56:02\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 05:56:02'),
(29, NULL, 'CREATE', 'Assignment', '2', NULL, '{\"id\": 2, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 08:57:20\", \"assigned_at\": \"2026-06-15 08:57:20\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 05:57:20'),
(30, NULL, 'UPDATE', 'Assignment', '1', '{\"id\": 1, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 08:56:02\", \"assigned_at\": \"2026-06-15 08:56:02\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 1, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-15 08:56:02\", \"assigned_at\": \"2026-06-15 08:56:02\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": \"2026-06-15 09:03:46\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 06:03:46'),
(31, NULL, 'CREATE', 'Assignment', '3', NULL, '{\"id\": 3, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:04:26\", \"assigned_at\": \"2026-06-15 09:04:26\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 06:04:26'),
(32, NULL, 'UPDATE', 'Assignment', '2', '{\"id\": 2, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 08:57:20\", \"assigned_at\": \"2026-06-15 08:57:20\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 2, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 2, \"created_at\": \"2026-06-15 08:57:20\", \"assigned_at\": \"2026-06-15 08:57:20\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": \"2026-06-15 09:04:46\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 06:04:46'),
(33, NULL, 'UPDATE', 'Assignment', '3', '{\"id\": 3, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:04:26\", \"assigned_at\": \"2026-06-15 09:04:26\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 3, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:04:26\", \"assigned_at\": \"2026-06-15 09:04:26\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": \"2026-06-15 09:06:50\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 06:06:50'),
(34, NULL, 'CREATE', 'Assignment', '4', NULL, '{\"id\": 4, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:07:50\", \"assigned_at\": \"2026-06-15 09:07:50\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 06:07:50'),
(35, NULL, 'UPDATE', 'Assignment', '4', '{\"id\": 4, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:07:50\", \"assigned_at\": \"2026-06-15 09:07:50\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 4, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:07:50\", \"assigned_at\": \"2026-06-15 09:07:50\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": \"2026-06-15 09:14:39\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 06:14:39'),
(36, NULL, 'CREATE', 'Assignment', '5', NULL, '{\"id\": 5, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:14:48\", \"assigned_at\": \"2026-06-15 09:14:48\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 06:14:48'),
(37, NULL, 'UPDATE', 'Assignment', '5', '{\"id\": 5, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:14:48\", \"assigned_at\": \"2026-06-15 09:14:48\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 5, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:14:48\", \"assigned_at\": \"2026-06-15 09:14:48\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": \"2026-06-15 09:22:12\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 06:22:12'),
(38, NULL, 'CREATE', 'Assignment', '6', NULL, '{\"id\": 6, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:22:20\", \"assigned_at\": \"2026-06-15 09:22:20\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 06:22:20'),
(39, NULL, 'CREATE', 'Assignment', '7', NULL, '{\"id\": 7, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 11:33:16\", \"assigned_at\": \"2026-06-15 11:33:16\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 08:33:16'),
(40, NULL, 'CREATE', 'Assignment', '8', NULL, '{\"id\": 8, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 11:37:51\", \"assigned_at\": \"2026-06-15 11:37:51\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 08:37:51'),
(41, NULL, 'CREATE', 'Assignment', '9', NULL, '{\"id\": 9, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 11:38:55\", \"assigned_at\": \"2026-06-15 11:38:55\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 08:38:55'),
(42, NULL, 'CREATE', 'Assignment', '10', NULL, '{\"id\": 10, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 11:40:09\", \"assigned_at\": \"2026-06-15 11:40:09\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 08:40:09'),
(43, NULL, 'CREATE', 'Assignment', '11', NULL, '{\"id\": 11, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 11:41:02\", \"assigned_at\": \"2026-06-15 11:41:02\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 08:41:02'),
(44, NULL, 'CREATE', 'Assignment', '12', NULL, '{\"id\": 12, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 11:44:34\", \"assigned_at\": \"2026-06-15 11:44:34\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 08:44:34'),
(45, NULL, 'CREATE', 'Assignment', '13', NULL, '{\"id\": 13, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 11:46:49\", \"assigned_at\": \"2026-06-15 11:46:49\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 08:46:49'),
(46, NULL, 'CREATE', 'Assignment', '14', NULL, '{\"id\": 14, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 11:50:05\", \"assigned_at\": \"2026-06-15 11:50:05\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 08:50:05'),
(47, 1, 'CREATE', 'User', '6', NULL, '{\"id\": 6, \"role\": \"student\", \"email\": \"dndambuki@usiu.ac.ke\", \"phone\": null, \"status\": \"active\", \"last_name\": \"Ndambuki\", \"created_at\": \"2026-06-15 11:58:31\", \"first_name\": \"Daniel\", \"updated_at\": \"2026-06-15 11:58:31\", \"password_hash\": \"$2y$10$liTS05.70olcBuckVHHz/.A7Imj4oJiQFLFhT.5ve7yq77CmRP1MW\", \"university_id\": \"655952\", \"must_change_password\": 1}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 08:58:31'),
(48, NULL, 'UPDATE', 'Assignment', '16', '{\"id\": 16, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-15 12:00:08\", \"assigned_at\": \"2026-06-15 12:00:08\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 16, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-15 12:00:08\", \"assigned_at\": \"2026-06-15 12:00:08\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": \"2026-06-15 12:12:17\", \"return_notes\": \"Device has visible wear on casing\", \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-15 09:12:17'),
(49, 1, 'CREATE', 'User', '7', NULL, '{\"id\": 7, \"role\": \"student\", \"email\": \"jdoe@usiu.ac.ke\", \"phone\": null, \"status\": \"active\", \"last_name\": \"Doe\", \"created_at\": \"2026-06-17 13:59:20\", \"first_name\": \"John\", \"updated_at\": \"2026-06-17 13:59:20\", \"password_hash\": \"$2y$10$iLD53HPpR0UvYsYud8A/lOIfWJxBujbl/41l8jQgHjUquZnL0P4IW\", \"university_id\": \"668712\", \"must_change_password\": 1}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-17 10:59:20'),
(50, 1, 'CREATE', 'User', '8', NULL, '{\"id\": 8, \"role\": \"student\", \"email\": \"user3@usiu.ac.ke\", \"phone\": null, \"status\": \"active\", \"last_name\": \"3User\", \"created_at\": \"2026-06-17 14:28:10\", \"first_name\": \"User3\", \"updated_at\": \"2026-06-17 14:28:10\", \"password_hash\": \"$2y$10$QtnUoDj04gT5DDpjMVCIiOFRev93ckCjIIMvB0Y8nEfpSdKcPtzjq\", \"university_id\": \"663123\", \"must_change_password\": 1}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-17 11:28:10'),
(51, NULL, 'UPDATE', 'Assignment', '17', '{\"id\": 17, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-17 15:11:01\", \"assigned_at\": \"2026-06-17 15:11:01\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 17, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-17 15:11:01\", \"assigned_at\": \"2026-06-17 15:11:01\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": \"2026-06-17 15:16:05\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-17 12:16:05'),
(52, NULL, 'UPDATE', 'Assignment', '18', '{\"id\": 18, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-17 15:24:17\", \"assigned_at\": \"2026-06-17 15:24:17\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 18, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-17 15:24:17\", \"assigned_at\": \"2026-06-17 15:24:17\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": \"2026-06-17 15:24:44\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-17 12:24:44'),
(53, NULL, 'UPDATE', 'Assignment', '19', '{\"id\": 19, \"notes\": \"Created from approved request\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-17 15:26:42\", \"assigned_at\": \"2026-06-17 15:26:42\", \"assigned_by\": 1, \"assigned_to\": 7, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": null}', '{\"id\": 19, \"notes\": \"Created from approved request\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-17 15:26:42\", \"assigned_at\": \"2026-06-17 15:26:42\", \"assigned_by\": 1, \"assigned_to\": 7, \"returned_at\": \"2026-06-17 15:30:00\", \"return_notes\": null, \"expected_return_date\": null}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-17 12:30:00'),
(54, NULL, 'UPDATE', 'Assignment', '20', '{\"id\": 20, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 15:23:25\", \"assigned_at\": \"2026-06-18 15:23:25\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 20, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 15:23:25\", \"assigned_at\": \"2026-06-18 15:23:25\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": \"2026-06-18 18:28:10\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 15:28:10'),
(55, NULL, 'UPDATE', 'Assignment', '21', '{\"id\": 21, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:32:30\", \"assigned_at\": \"2026-06-18 18:32:30\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 21, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:32:30\", \"assigned_at\": \"2026-06-18 18:32:30\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 18:33:15\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 15:33:15'),
(56, NULL, 'UPDATE', 'Assignment', '22', '{\"id\": 22, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:37:34\", \"assigned_at\": \"2026-06-18 18:37:34\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 22, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:37:34\", \"assigned_at\": \"2026-06-18 18:37:34\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 18:37:41\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 15:37:41'),
(57, NULL, 'UPDATE', 'Assignment', '23', '{\"id\": 23, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:39:07\", \"assigned_at\": \"2026-06-18 18:39:07\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 23, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:39:07\", \"assigned_at\": \"2026-06-18 18:39:07\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": \"2026-06-18 18:39:22\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 15:39:22'),
(58, NULL, 'UPDATE', 'Assignment', '24', '{\"id\": 24, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:44:36\", \"assigned_at\": \"2026-06-18 18:44:36\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 24, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:44:36\", \"assigned_at\": \"2026-06-18 18:44:36\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 18:44:47\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 15:44:47'),
(59, NULL, 'UPDATE', 'Assignment', '25', '{\"id\": 25, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:45:53\", \"assigned_at\": \"2026-06-18 18:45:53\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 25, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:45:53\", \"assigned_at\": \"2026-06-18 18:45:53\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 18:47:00\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 15:47:00'),
(60, NULL, 'UPDATE', 'Assignment', '26', '{\"id\": 26, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:48:07\", \"assigned_at\": \"2026-06-18 18:48:07\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 26, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 18:48:07\", \"assigned_at\": \"2026-06-18 18:48:07\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 18:48:46\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 15:48:46'),
(61, NULL, 'UPDATE', 'Assignment', '27', '{\"id\": 27, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:02:03\", \"assigned_at\": \"2026-06-18 19:02:03\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 27, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:02:03\", \"assigned_at\": \"2026-06-18 19:02:03\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 19:02:34\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 16:02:34'),
(62, NULL, 'UPDATE', 'Assignment', '28', '{\"id\": 28, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:04:24\", \"assigned_at\": \"2026-06-18 19:04:24\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 28, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:04:24\", \"assigned_at\": \"2026-06-18 19:04:24\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 19:04:29\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 16:04:29'),
(63, NULL, 'UPDATE', 'Assignment', '29', '{\"id\": 29, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:06:30\", \"assigned_at\": \"2026-06-18 19:06:30\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 29, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:06:30\", \"assigned_at\": \"2026-06-18 19:06:30\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 19:07:11\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 16:07:11'),
(64, NULL, 'UPDATE', 'Assignment', '30', '{\"id\": 30, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:08:17\", \"assigned_at\": \"2026-06-18 19:08:17\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 30, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:08:17\", \"assigned_at\": \"2026-06-18 19:08:17\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 19:08:31\", \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 16:08:31'),
(65, NULL, 'UPDATE', 'Assignment', '31', '{\"id\": 31, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:08:39\", \"assigned_at\": \"2026-06-18 19:08:39\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 31, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:08:39\", \"assigned_at\": \"2026-06-18 19:08:39\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 19:10:15\", \"return_notes\": \"Returned in good condition\", \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 16:10:15'),
(66, NULL, 'UPDATE', 'Assignment', '32', '{\"id\": 32, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:11:25\", \"assigned_at\": \"2026-06-18 19:11:25\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 32, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:11:25\", \"assigned_at\": \"2026-06-18 19:11:25\", \"assigned_by\": 1, \"assigned_to\": 8, \"returned_at\": \"2026-06-18 19:11:42\", \"return_notes\": \"Returned in good condition\", \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 16:11:42'),
(67, NULL, 'UPDATE', 'Assignment', '33', '{\"id\": 33, \"notes\": \"Created from approved request\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:11:42\", \"assigned_at\": \"2026-06-18 19:11:42\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": null}', '{\"id\": 33, \"notes\": \"Created from approved request\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:11:42\", \"assigned_at\": \"2026-06-18 19:11:42\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": \"2026-06-18 19:17:11\", \"return_notes\": \"Returned in good condition\", \"expected_return_date\": null}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 16:17:11'),
(68, NULL, 'UPDATE', 'Assignment', '34', '{\"id\": 34, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:19:52\", \"assigned_at\": \"2026-06-18 19:19:52\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 34, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:19:52\", \"assigned_at\": \"2026-06-18 19:19:52\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": \"2026-06-18 19:20:00\", \"return_notes\": \"Returned in good condition\", \"expected_return_date\": \"2026-07-15\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 16:20:01'),
(69, NULL, 'UPDATE', 'Assignment', '35', '{\"id\": 35, \"notes\": \"Created from approved request\", \"status\": \"active\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:20:01\", \"assigned_at\": \"2026-06-18 19:20:01\", \"assigned_by\": 1, \"assigned_to\": 7, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": null}', '{\"id\": 35, \"notes\": \"Created from approved request\", \"status\": \"returned\", \"asset_id\": 1, \"created_at\": \"2026-06-18 19:20:01\", \"assigned_at\": \"2026-06-18 19:20:01\", \"assigned_by\": 1, \"assigned_to\": 7, \"returned_at\": \"2026-06-18 19:20:28\", \"return_notes\": \"Returned in good condition\", \"expected_return_date\": null}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 16:20:28'),
(70, 1, 'CREATE', 'User', '9', NULL, '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-18 20:20:06\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-18 17:20:06'),
(71, 1, 'UPDATE', 'User', '9', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-18 20:20:06\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"suspended\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:42\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-24 20:17:42'),
(72, 1, 'UPDATE', 'User', '9', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"suspended\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:42\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:44\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-24 20:17:44'),
(73, 1, 'UPDATE', 'User', '9', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:44\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"suspended\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:46\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-24 20:17:46'),
(74, 1, 'UPDATE', 'User', '9', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"suspended\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:46\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:46\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-24 20:17:46'),
(75, 1, 'UPDATE', 'User', '9', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:46\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"suspended\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:47\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-24 20:17:47'),
(76, 1, 'UPDATE', 'User', '9', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"suspended\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:47\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', '{\"id\": 9, \"role\": \"student\", \"email\": \"pwamuyu@usiu.ac.ke\", \"phone\": \"0712345678\", \"status\": \"active\", \"last_name\": \"Wamuyu\", \"created_at\": \"2026-06-18 20:20:06\", \"first_name\": \"Patric}\", \"updated_at\": \"2026-06-24 23:17:47\", \"password_hash\": \"$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue\", \"university_id\": \"549465\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-24 20:17:47'),
(77, 1, 'CREATE', 'User', '10', NULL, '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"active\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-24 23:49:04\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-24 20:49:04'),
(78, 1, 'UPDATE', 'User', '10', '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"active\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-24 23:49:04\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"suspended\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 09:04:16\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 06:04:16'),
(79, 1, 'UPDATE', 'User', '10', '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"suspended\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 09:04:16\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"active\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 09:04:16\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 06:04:16'),
(80, 1, 'UPDATE', 'User', '10', '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"active\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 09:04:16\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"suspended\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 09:04:17\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 06:04:17'),
(81, 1, 'UPDATE', 'User', '10', '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"suspended\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 09:04:17\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"active\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 09:04:18\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 06:04:18'),
(82, 1, 'DELETE', 'User', '10', '{\"id\": 10, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"0654684984\", \"status\": \"active\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-24 23:49:04\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 09:04:18\", \"password_hash\": \"$2y$10$mSc9XI8TJlK.x0NFVbaG5eCKc3uy.ylJLALmin.zzaqBfpcr819za\", \"university_id\": \"6653434\", \"must_change_password\": 1}', NULL, NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-25 09:13:20'),
(83, 1, 'CREATE', 'User', '11', NULL, '{\"id\": 11, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"43343234413\", \"status\": \"active\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-25 12:16:53\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 12:16:53\", \"password_hash\": \"$2y$10$p5wraGgydaM54OZwHDantObGl4vL5am8OD9e5kcsRJsjvUuqtSWMC\", \"university_id\": \"345343\", \"must_change_password\": 1}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 09:16:53'),
(84, 1, 'DELETE', 'User', '11', '{\"id\": 11, \"role\": \"faculty\", \"email\": \"laloo@usiu.ac.ke\", \"phone\": \"43343234413\", \"status\": \"active\", \"last_name\": \"Aloo\", \"created_at\": \"2026-06-25 12:16:53\", \"first_name\": \"Linus\", \"updated_at\": \"2026-06-25 12:16:53\", \"password_hash\": \"$2y$10$p5wraGgydaM54OZwHDantObGl4vL5am8OD9e5kcsRJsjvUuqtSWMC\", \"university_id\": \"345343\", \"must_change_password\": 1}', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 09:18:36'),
(85, 1, 'CREATE', 'Department', '3', NULL, '{\"id\": 3, \"code\": \"SSH\", \"name\": \"School of Humanities and Social Sciences\", \"created_at\": \"2026-06-25 12:21:04\", \"updated_at\": \"2026-06-25 12:21:04\", \"description\": \"Department of Humanities and Social Sciences\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-25 09:21:04'),
(86, 1, 'DELETE', 'Department', '3', '{\"id\": 3, \"code\": \"SSH\", \"name\": \"School of Humanities and Social Sciences\", \"created_at\": \"2026-06-25 12:21:04\", \"updated_at\": \"2026-06-25 12:21:04\", \"description\": \"Department of Humanities and Social Sciences\"}', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 09:35:33'),
(87, 1, 'CREATE', 'Department', '4', NULL, '{\"id\": 4, \"code\": \"SSH\", \"name\": \"School of Social Sciences and Humanities \", \"created_at\": \"2026-06-25 12:36:22\", \"updated_at\": \"2026-06-25 12:36:22\", \"description\": \"Hao wasee wa psychology\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 09:36:22'),
(88, 1, 'CREATE', 'AssetCategory', '4', NULL, '{\"id\": 4, \"name\": \"Extensions\", \"created_at\": \"2026-06-25 12:46:02\", \"updated_at\": \"2026-06-25 12:46:02\", \"description\": \"Power delivery systems\", \"depreciation_period\": 12}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-25 09:46:02'),
(89, 1, 'DELETE', 'AssetCategory', '4', '{\"id\": 4, \"name\": \"Extensions\", \"created_at\": \"2026-06-25 12:46:02\", \"updated_at\": \"2026-06-25 12:46:02\", \"description\": \"Power delivery systems\", \"depreciation_period\": 12}', NULL, NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-25 09:47:32'),
(90, 1, 'CREATE', 'AssetCategory', '5', NULL, '{\"id\": 5, \"name\": \"Extensions\", \"created_at\": \"2026-06-25 12:53:05\", \"updated_at\": \"2026-06-25 12:53:05\", \"description\": \"Power delivery systems\", \"depreciation_period\": 12}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-25 09:53:05'),
(91, 1, 'CREATE', 'AssetLocation', '2', NULL, '{\"id\": 2, \"name\": \"Software Lab\", \"building\": \"Lilian Beam\", \"created_at\": \"2026-06-25 12:56:33\", \"updated_at\": \"2026-06-25 12:56:33\", \"description\": \"Software computer lab used for testing and developing software\", \"room_number\": \"SLAB\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-25 09:56:33'),
(92, 1, 'DELETE', 'AssetLocation', '2', '{\"id\": 2, \"name\": \"Software Lab\", \"building\": \"Lilian Beam\", \"created_at\": \"2026-06-25 12:56:33\", \"updated_at\": \"2026-06-25 12:56:33\", \"description\": \"Software computer lab used for testing and developing software\", \"room_number\": \"SLAB\"}', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 10:18:21');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `old_values`, `new_values`, `metadata`, `ip_address`, `user_agent`, `created_at`) VALUES
(93, 1, 'CREATE', 'AssetLocation', '3', NULL, '{\"id\": 3, \"name\": \"Software Lab\", \"building\": \"Lilian Beam\", \"created_at\": \"2026-06-25 13:18:55\", \"updated_at\": \"2026-06-25 13:18:55\", \"description\": \"Software computer lab for developing and testing software\", \"room_number\": \"SLAB\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 10:18:55'),
(94, NULL, 'CREATE', 'Asset', '3', NULL, '{\"id\": 3, \"supplier\": \"Power Group Nairobi\", \"asset_tag\": \"USIU-2-5-2026-000003\", \"item_name\": \"6 Way Extension\", \"created_at\": \"2026-06-25 14:52:24\", \"created_by\": 1, \"updated_at\": \"2026-06-25 14:52:24\", \"category_id\": 5, \"description\": \"6 way power extension\", \"location_id\": 1, \"asset_status\": \"available\", \"department_id\": 2, \"purchase_cost\": \"3500.00\", \"purchase_date\": \"2026-01-10\", \"serial_number\": \"65464 7455 5544 5745\", \"condition_status\": \"excellent\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-25 11:52:24'),
(95, NULL, 'CREATE', 'Asset', '4', NULL, '{\"id\": 4, \"supplier\": \"New Supplier\", \"asset_tag\": \"USIU-2-1-2026-000004\", \"item_name\": \"Test\", \"created_at\": \"2026-06-25 15:02:10\", \"created_by\": 1, \"updated_at\": \"2026-06-25 15:02:10\", \"category_id\": 1, \"description\": \"test description\", \"location_id\": 3, \"asset_status\": \"available\", \"department_id\": 2, \"purchase_cost\": \"5500.00\", \"purchase_date\": \"2026-06-25\", \"serial_number\": \"134353212134\", \"condition_status\": \"excellent\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 12:02:10'),
(96, NULL, 'CREATE', 'Asset', '5', NULL, '{\"id\": 5, \"supplier\": \"testt\", \"asset_tag\": \"USIU-4-5-2026-000005\", \"item_name\": \"tesst\", \"created_at\": \"2026-06-25 15:06:47\", \"created_by\": 1, \"updated_at\": \"2026-06-25 15:06:47\", \"category_id\": 5, \"description\": \"fsbg \", \"location_id\": 3, \"asset_status\": \"available\", \"department_id\": 4, \"purchase_cost\": \"20.00\", \"purchase_date\": \"2026-06-23\", \"serial_number\": \"34235ff\", \"condition_status\": \"excellent\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 12:06:47'),
(97, 1, 'UPDATE', 'Assignment', '39', '{\"id\": 39, \"notes\": \"\", \"status\": \"active\", \"asset_id\": 3, \"created_at\": \"2026-06-25 16:21:59\", \"assigned_at\": \"2026-06-25 16:21:59\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-06-26\"}', '{\"id\": 39, \"notes\": \"\", \"status\": \"returned\", \"asset_id\": 3, \"created_at\": \"2026-06-25 16:21:59\", \"assigned_at\": \"2026-06-25 16:21:59\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": \"2026-06-25 20:06:07\", \"return_notes\": \"Returned in good condition\", \"expected_return_date\": \"2026-06-26\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-25 17:06:07'),
(98, 1, 'UPDATE', 'Assignment', '38', '{\"id\": 38, \"notes\": \"test\", \"status\": \"active\", \"asset_id\": 5, \"created_at\": \"2026-06-25 16:03:41\", \"assigned_at\": \"2026-06-25 16:03:41\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-06-29\"}', '{\"id\": 38, \"notes\": \"test\", \"status\": \"returned\", \"asset_id\": 5, \"created_at\": \"2026-06-25 16:03:41\", \"assigned_at\": \"2026-06-25 16:03:41\", \"assigned_by\": 1, \"assigned_to\": 6, \"returned_at\": \"2026-06-25 20:53:15\", \"return_notes\": \"Test from UI\", \"expected_return_date\": \"2026-06-29\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 17:53:15'),
(99, 1, 'UPDATE', 'Assignment', '6', '{\"id\": 6, \"notes\": \"Testing assignment system\", \"status\": \"active\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:22:20\", \"assigned_at\": \"2026-06-15 09:22:20\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": null, \"return_notes\": null, \"expected_return_date\": \"2026-07-15\"}', '{\"id\": 6, \"notes\": \"Testing assignment system\", \"status\": \"returned\", \"asset_id\": 2, \"created_at\": \"2026-06-15 09:22:20\", \"assigned_at\": \"2026-06-15 09:22:20\", \"assigned_by\": 1, \"assigned_to\": 2, \"returned_at\": \"2026-06-25 20:53:30\", \"return_notes\": \"test from UI\", \"expected_return_date\": \"2026-07-15\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-25 17:53:30'),
(100, NULL, 'UPDATE', 'Asset', '4', '{\"id\": 4, \"supplier\": \"New Supplier\", \"asset_tag\": \"USIU-2-1-2026-000004\", \"item_name\": \"Test\", \"created_at\": \"2026-06-25 15:02:10\", \"created_by\": 1, \"updated_at\": \"2026-06-25 15:45:15\", \"category_id\": 1, \"description\": \"test description\", \"location_id\": 3, \"asset_status\": \"assigned\", \"department_id\": 2, \"purchase_cost\": \"5500.00\", \"purchase_date\": \"2026-06-25\", \"serial_number\": \"134353212134\", \"condition_status\": \"excellent\"}', '{\"id\": 4, \"supplier\": \"Power Group Nairobi\", \"asset_tag\": \"USIU-2-1-2026-000004\", \"item_name\": \"Computer Dust Blower\", \"created_at\": \"2026-06-25 15:02:10\", \"created_by\": 1, \"updated_at\": \"2026-06-29 12:57:35\", \"category_id\": 5, \"description\": \"Heavy duty dust blower\", \"location_id\": 1, \"asset_status\": \"available\", \"department_id\": 2, \"purchase_cost\": \"7500.00\", \"purchase_date\": \"2026-01-10\", \"serial_number\": \"INGCO 038 408342\", \"condition_status\": \"excellent\"}', NULL, '::1', 'PostmanRuntime/7.54.0', '2026-06-29 09:57:35'),
(101, NULL, 'UPDATE', 'Asset', '5', '{\"id\": 5, \"supplier\": \"testt\", \"asset_tag\": \"USIU-4-5-2026-000005\", \"item_name\": \"tesst\", \"created_at\": \"2026-06-25 15:06:47\", \"created_by\": 1, \"updated_at\": \"2026-06-25 20:53:15\", \"category_id\": 5, \"description\": \"fsbg \", \"location_id\": 3, \"asset_status\": \"available\", \"department_id\": 4, \"purchase_cost\": \"20.00\", \"purchase_date\": \"2026-06-23\", \"serial_number\": \"34235ff\", \"condition_status\": \"excellent\"}', '{\"id\": 5, \"supplier\": \"testt\", \"asset_tag\": \"USIU-4-5-2026-000005\", \"item_name\": \"tesst\", \"created_at\": \"2026-06-25 15:06:47\", \"created_by\": 1, \"updated_at\": \"2026-06-29 13:12:43\", \"category_id\": 5, \"description\": \"fsbg \", \"location_id\": 3, \"asset_status\": \"available\", \"department_id\": 2, \"purchase_cost\": \"20.00\", \"purchase_date\": \"2026-06-23\", \"serial_number\": \"34235ff\", \"condition_status\": \"excellent\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-29 10:12:43'),
(102, NULL, 'UPDATE', 'Asset', '5', '{\"id\": 5, \"supplier\": \"testt\", \"asset_tag\": \"USIU-4-5-2026-000005\", \"item_name\": \"tesst\", \"created_at\": \"2026-06-25 15:06:47\", \"created_by\": 1, \"updated_at\": \"2026-06-29 13:12:43\", \"category_id\": 5, \"description\": \"fsbg \", \"location_id\": 3, \"asset_status\": \"available\", \"department_id\": 2, \"purchase_cost\": \"20.00\", \"purchase_date\": \"2026-06-23\", \"serial_number\": \"34235ff\", \"condition_status\": \"excellent\"}', '{\"id\": 5, \"supplier\": \"testt\", \"asset_tag\": \"USIU-4-5-2026-000005\", \"item_name\": \"tesst\", \"created_at\": \"2026-06-25 15:06:47\", \"created_by\": 1, \"updated_at\": \"2026-06-29 13:12:46\", \"category_id\": 5, \"description\": \"fsbg \", \"location_id\": 3, \"asset_status\": \"available\", \"department_id\": 2, \"purchase_cost\": \"20.00\", \"purchase_date\": \"2026-06-23\", \"serial_number\": \"34235ff\", \"condition_status\": \"excellent\"}', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', '2026-06-29 10:12:46');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(2, 'School of Science and Technology', 'SST', 'Department of Science and Technology', '2026-06-14 17:12:52', '2026-06-14 17:12:52'),
(4, 'School of Social Sciences and Humanities ', 'SSH', 'Hao wasee wa psychology', '2026-06-25 09:36:22', '2026-06-25 09:36:22');

-- --------------------------------------------------------

--
-- Table structure for table `revoked_tokens`
--

DROP TABLE IF EXISTS `revoked_tokens`;
CREATE TABLE IF NOT EXISTS `revoked_tokens` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `jti` varchar(230) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jti` (`jti`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `revoked_tokens`
--

INSERT INTO `revoked_tokens` (`id`, `jti`, `expires_at`, `created_at`) VALUES
(1, '94b1cb60c5e0a55dae3635d2786daf75', '2026-06-08 17:44:29', '2026-06-07 17:52:03'),
(2, 'f3b9a678210d151120308712cfa85350', '2026-06-09 14:12:32', '2026-06-08 14:14:23'),
(3, '10d09815a6a6bf3bd2c7d82f9eabc4c5', '2026-06-10 13:34:17', '2026-06-09 13:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `university_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(230) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('student','faculty','technologist','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','suspended','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `must_change_password` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `university_id` (`university_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `university_id`, `first_name`, `last_name`, `email`, `phone`, `password_hash`, `role`, `status`, `must_change_password`, `created_at`, `updated_at`) VALUES
(1, 'ADMIN001', 'ICTLillianBeam', 'Administrator', 'admin@usiu.ac.ke', '0700000000', '$2y$10$jo9KNrTqNEfo5fC9A3.Ce.8pHRQJ5eMC0Xnl075Uq3h9c/wcRlolm', 'admin', 'active', 0, '2026-06-07 13:46:37', '2026-06-20 15:20:23'),
(7, '668712', 'John', 'Doe', 'jdoe@usiu.ac.ke', NULL, '$2y$10$iLD53HPpR0UvYsYud8A/lOIfWJxBujbl/41l8jQgHjUquZnL0P4IW', 'student', 'active', 1, '2026-06-17 10:59:20', '2026-06-17 10:59:20'),
(6, '655952', 'Daniel', 'Ndambuki', 'dndambuki@usiu.ac.ke', NULL, '$2y$10$liTS05.70olcBuckVHHz/.A7Imj4oJiQFLFhT.5ve7yq77CmRP1MW', 'faculty', 'active', 1, '2026-06-15 08:58:31', '2026-06-26 19:10:48'),
(8, '663123', 'User3', '3User', 'user3@usiu.ac.ke', NULL, '$2y$10$QtnUoDj04gT5DDpjMVCIiOFRev93ckCjIIMvB0Y8nEfpSdKcPtzjq', 'student', 'active', 1, '2026-06-17 11:28:10', '2026-06-17 11:28:10'),
(9, '549465', 'Patric}', 'Wamuyu', 'pwamuyu@usiu.ac.ke', '0712345678', '$2y$10$yQWU89WbBv/5PiNyrg/dYeM9.AyC8MMgpapvqWbo1z.1W0oh4K5ue', 'student', 'active', 1, '2026-06-18 17:20:06', '2026-06-24 20:17:47');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
