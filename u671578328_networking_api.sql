-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2024 at 04:08 PM
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
-- Database: `pedsprod`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `companycode` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `website_url` varchar(255) NOT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_codes`
--

CREATE TABLE `email_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transNo` varchar(255) NOT NULL,
  `desc_code` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `routes` varchar(255) NOT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'A',
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `transNo`, `desc_code`, `description`, `icon`, `class`, `routes`, `sort`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '1', 'top_navigation', 'Home', 'icon-home', 'class-home', 'home', 1, 'A', NULL, NULL, NULL, NULL),
(2, '2', 'top_navigation', 'System', 'icon-system', 'class-system', '#', 15, 'A', NULL, NULL, NULL, NULL),
(5, '3', 'top_navigation', 'Messaging', 'icon-message', 'icon-class', 'message', 2, 'A', NULL, NULL, NULL, NULL),
(6, '4', 'side_bar', 'Dashboard', '', 'icon-dashboard', 'dashboard', 1, 'A', NULL, NULL, NULL, NULL),
(7, '5', 'top_navigation', 'My Network', 'icon-network', '', 'network', 3, 'A', NULL, NULL, NULL, NULL),
(8, '6', 'top_navigation', 'Notifications', 'icon-notifications', 'class-notifications', 'notifications', 4, 'A', NULL, NULL, NULL, NULL);

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_11_02_055944_create_resources_table', 1),
(6, '2024_11_02_060040_create_companies_table', 1),
(7, '2024_11_02_060201_create_roles_table', 1),
(8, '2024_11_02_060556_create_menus_table', 1),
(9, '2024_11_02_060737_create_submenus_table', 1),
(10, '2024_11_02_064146_create_roleaccesssubmenus_table', 1),
(11, '2024_11_02_065922_create_roleaccessmenus_table', 1),
(12, '2024_11_27_054345_create_email_codes_table', 1),
(13, '2024_12_04_070123_create_userprofiles_table', 1),
(14, '2024_12_04_070856_create_usercapabilities_table', 1),
(15, '2024_12_04_070954_create_usereducations_table', 1),
(16, '2024_12_04_093133_create_usertrainings_table', 1),
(17, '2024_12_04_093322_create_userseminars_table', 1),
(18, '2024_12_05_142707_create_usercertificates_table', 1),
(19, '2024_12_05_144202_create_useremploymentrecords_table', 1);

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
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` int(11) NOT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `companywebsite` varchar(255) DEFAULT NULL,
  `role_code` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `home_country` varchar(255) DEFAULT NULL,
  `current_location` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `resumepdf` varchar(255) DEFAULT NULL,
  `h1_fname` varchar(255) DEFAULT NULL,
  `h1_lname` varchar(255) DEFAULT NULL,
  `h1_mname` varchar(255) DEFAULT NULL,
  `h1_fullname` varchar(255) DEFAULT NULL,
  `h1_contact_no` int(11) DEFAULT NULL,
  `h1_email` varchar(255) DEFAULT NULL,
  `h1_address1` varchar(255) DEFAULT NULL,
  `h1_address2` varchar(255) DEFAULT NULL,
  `h1_city` varchar(255) DEFAULT NULL,
  `h1_province` varchar(255) DEFAULT NULL,
  `h1_postal_code` varchar(255) DEFAULT NULL,
  `h1_companycode` varchar(255) DEFAULT NULL,
  `h1_rolecode` int(11) DEFAULT NULL,
  `h1_designation` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `code`, `fname`, `lname`, `mname`, `fullname`, `contact_no`, `age`, `email`, `profession`, `company`, `industry`, `companywebsite`, `role_code`, `designation`, `date_birth`, `home_country`, `current_location`, `profile_picture`, `resumepdf`, `h1_fname`, `h1_lname`, `h1_mname`, `h1_fullname`, `h1_contact_no`, `h1_email`, `h1_address1`, `h1_address2`, `h1_city`, `h1_province`, `h1_postal_code`, `h1_companycode`, `h1_rolecode`, `h1_designation`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(89, 701, 'Pedro', 'yorpo', '', 'Pedro yorpo', '+639999990909', NULL, 'pedroyorpo17@gmail.com', NULL, 'ABC Company', 'Civil Services (Government, Armed Forces)', 'nexsuz.com', 'DEF-MASTERADMIN', 'position', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-27 05:28:16', '2024-11-27 05:28:16'),
(90, 702, 'Pedro', 'Yorpo', '', 'Pedro Yorpo', '+6392999990909', '1', 'pedroyorpo22@gmail.com', 'programmer', NULL, NULL, NULL, 'DEF-USERS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-27 06:10:49', '2024-11-27 06:10:49'),
(91, 703, 'Elizabeth', 'Punay', '', 'Elizabeth Punay', '+639994589906', '1', 'elizabethpunay01@gmail.com', 'Teacher', NULL, NULL, NULL, 'DEF-USERS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-27 06:18:12', '2024-11-27 06:18:12'),
(93, 705, 'David', 'Dela Cruz', '', 'David Dela Cruz', '+8613061767765', '1', 'dhave.cdc83@gmail.com', 'Recruitment Manager', NULL, NULL, NULL, 'DEF-USERS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 16:36:59', '2024-11-28 16:36:59'),
(95, 707, 'David', 'Dela Cruz', '', 'David Dela Cruz', '+8613061767765', NULL, 'manpower@hraintl.com', NULL, 'HRA International', 'Human Resources Management/Consultancy', 'hraintl.com', 'DEF-CLIENT', 'Recruitment Manager', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 18:22:55', '2024-11-28 18:22:55'),
(97, 709, 'Angel', 'Angeles', '', 'Angel Angeles', '+639052237858', '1', 'strategichrtaprofessional@gmail.com', 'Regional Manager', NULL, NULL, NULL, 'DEF-USERS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-30 04:03:52', '2024-11-30 04:03:52'),
(98, 710, 'Francis', 'Angeles', '', 'Francis Angeles', '+8615900603216', '1', 'francis.angeles710@gmail.com', 'Regional Manager', NULL, NULL, NULL, 'DEF-USERS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-30 04:14:40', '2024-11-30 04:14:40'),
(99, 711, 'human$', 'crazy', '', 'Human$ crazy', '+639453570677', NULL, 'reinjunelaride34@gmail.com', NULL, 'VFI', 'Laboratory', 'mail.com', 'DEF-MASTERADMIN', 'PROFESSIONAL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-01 21:12:05', '2024-12-01 21:12:05');

-- --------------------------------------------------------

--
-- Table structure for table `roleaccessmenus`
--

CREATE TABLE `roleaccessmenus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rolecode` varchar(255) NOT NULL,
  `transNo` int(11) NOT NULL,
  `menus_id` int(11) NOT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roleaccessmenus`
--

INSERT INTO `roleaccessmenus` (`id`, `rolecode`, `transNo`, `menus_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(3, 'DEF-USERS', 3, 1, 'Pedro Yorpo', 'Pedro Yorpo', NULL, NULL),
(4, 'DEF-USERS', 4, 5, 'Pedro Yorpo', 'Pedro Yorpo', NULL, NULL),
(5, 'DEF-USERS', 5, 7, 'Pedro Yorpo', 'Pedro Yorpo', NULL, NULL),
(6, 'DEF-USERS', 6, 8, 'Pedro Yorpo', 'Pedro Yorpo', NULL, NULL),
(7, 'DEF-CLIENT', 7, 1, 'Pedro Yorpo', 'Pedro Yorpo', NULL, NULL),
(8, 'DEF-CLIENT', 8, 8, 'Pedro Yorpo', 'Pedro Yorpo', NULL, NULL),
(20, 'DEF-MASTERADMIN', 9, 1, NULL, NULL, NULL, NULL),
(21, 'DEF-MASTERADMIN', 10, 2, NULL, NULL, NULL, NULL),
(22, 'DEF-MASTERADMIN', 11, 5, NULL, NULL, NULL, NULL),
(23, 'DEF-MASTERADMIN', 12, 6, NULL, NULL, NULL, NULL),
(24, 'DEF-MASTERADMIN', 13, 7, NULL, NULL, NULL, NULL),
(25, 'DEF-MASTERADMIN', 14, 8, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roleaccesssubmenus`
--

CREATE TABLE `roleaccesssubmenus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rolecode` varchar(255) NOT NULL,
  `transNo` int(11) NOT NULL,
  `submenus_id` int(11) NOT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roleaccesssubmenus`
--

INSERT INTO `roleaccesssubmenus` (`id`, `rolecode`, `transNo`, `submenus_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, 'DEF-MASTERADMIN', 10, 1, NULL, NULL, NULL, NULL),
(6, 'DEF-MASTERADMIN', 10, 2, NULL, NULL, NULL, NULL),
(7, 'DEF-MASTERADMIN', 10, 3, NULL, NULL, NULL, NULL),
(8, 'DEF-MASTERADMIN', 10, 4, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rolecode` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `rolecode`, `description`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 'DEF-USERS', 'System User with Access to Standard Features', 'Rj ediral', NULL, '2024-11-08 00:09:29', '2024-11-08 00:09:29'),
(3, 'DEF-MASTERADMIN', 'System Developer with Full Access to All Modules and Features.', 'Rj ediral', 'Pedro Yorpo', '2024-11-11 16:27:35', '2024-11-12 05:53:02'),
(4, 'DEF-SUPERADMIN', 'Top-level Admin with access to manage settings and create admins.', 'Pedro Yorpo', NULL, '2024-11-16 23:18:13', '2024-11-16 23:18:13'),
(5, 'DEF-CLIENT', 'Standard user with access to client-specific features.', 'Pedro Yorpo', NULL, '2024-11-21 05:43:00', '2024-11-21 05:43:00'),
(8, 'DEF-HR', 'Roles', 'Human$ crazy', NULL, '2024-12-05 01:25:36', '2024-12-05 01:25:36');

-- --------------------------------------------------------

--
-- Table structure for table `submenus`
--

CREATE TABLE `submenus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transNo` int(11) NOT NULL,
  `desc_code` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `routes` varchar(255) NOT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'A',
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `submenus`
--

INSERT INTO `submenus` (`id`, `transNo`, `desc_code`, `description`, `icon`, `class`, `routes`, `sort`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'top_navigation', 'Security roles', 'icon-security', 'class-security', 'security', 1, 'A', NULL, NULL, NULL, NULL),
(2, 2, 'top_navigation', 'Users', 'icon-user', 'class-user', 'user', 2, 'A', NULL, NULL, NULL, NULL),
(3, 2, 'top_navigation', 'Menus', 'icon-menu', 'class-menu', 'menu', 3, 'A', NULL, NULL, NULL, NULL),
(4, 2, 'top_navigation', 'Roles', 'icon-role', 'class-role', 'role', 4, 'A', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usercapabilities`
--

CREATE TABLE `usercapabilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` int(11) DEFAULT NULL,
  `transNo` int(11) DEFAULT NULL,
  `language` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usercertificates`
--

CREATE TABLE `usercertificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `transNo` int(11) NOT NULL,
  `certificate_title` varchar(255) DEFAULT NULL,
  `certificate_provider` varchar(255) DEFAULT NULL,
  `date_completed` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usereducations`
--

CREATE TABLE `usereducations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` int(11) DEFAULT NULL,
  `transNo` int(11) DEFAULT NULL,
  `highest_education` varchar(255) DEFAULT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `year_entry` year(4) DEFAULT NULL,
  `year_end` year(4) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `useremploymentrecords`
--

CREATE TABLE `useremploymentrecords` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `transNo` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `job_description` varchar(255) DEFAULT NULL,
  `date_completed` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userprofiles`
--

CREATE TABLE `userprofiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` int(11) DEFAULT NULL,
  `transNo` int(11) DEFAULT NULL,
  `photo_pic` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `contact_visibility` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `email_visibility` tinyint(1) NOT NULL DEFAULT 0,
  `summary` varchar(255) DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `home_country` varchar(255) DEFAULT NULL,
  `current_location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `contactno` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'I',
  `company` varchar(255) DEFAULT NULL,
  `code` int(11) NOT NULL,
  `role_code` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `mname`, `contactno`, `fullname`, `email`, `email_verified_at`, `password`, `status`, `company`, `code`, `role_code`, `remember_token`, `created_at`, `updated_at`) VALUES
(91, 'Pedro', 'yorpo', '', '+639999990909', 'Pedro yorpo', 'pedroyorpo17@gmail.com', NULL, '$2y$10$U503pB5cnMOrGmC6UhYb/u9gXVBy0sPjFlXy2xZkOm/AIIjR3ntfq', 'A', NULL, 701, 'DEF-MASTERADMIN', NULL, '2024-11-27 05:28:16', '2024-11-27 05:38:12'),
(92, 'Pedro', 'Yorpo', '', '+6392999990909', 'Pedro Yorpo', 'pedroyorpo22@gmail.com', NULL, '$2y$10$llcrc7MelFMCJH7yTJC9ZuW0vB3bcWGJCc0SbcomhDb4RtX35WtWC', 'A', NULL, 702, 'DEF-USERS', NULL, '2024-11-27 06:10:49', '2024-11-27 06:12:56'),
(93, 'Elizabeth', 'Punay', '', '+639994589906', 'Elizabeth Punay', 'elizabethpunay01@gmail.com', NULL, '$2y$10$38yMIG4XDhPactoT1nyes.NFiMKCszitgAcyUsUQ15L.R8PiFVziW', 'A', NULL, 703, 'DEF-USERS', NULL, '2024-11-27 06:18:12', '2024-11-27 06:24:20'),
(95, 'David', 'Dela Cruz', '', '+8613061767765', 'David Dela Cruz', 'dhave.cdc83@gmail.com', NULL, '$2y$10$jHm96v.Ipdp819//DRuan.jvMsvrLkHNUv3cG4ymrQ8nLU6ANc1sK', 'A', NULL, 705, 'DEF-USERS', NULL, '2024-11-28 16:36:59', '2024-11-28 16:46:20'),
(98, 'David', 'Dela Cruz', '', '+8613061767765', 'David Dela Cruz', 'manpower@hraintl.com', NULL, '$2y$10$SQWTaMK8YwMiscLKsX8sGOOKLzNxnlvqiVfylGEOocmdr/D4H4MQ6', 'A', NULL, 707, 'DEF-CLIENT', NULL, '2024-11-28 18:22:55', '2024-11-28 18:25:41'),
(100, 'Angel', 'Angeles', '', '+639052237858', 'Angel Angeles', 'strategichrtaprofessional@gmail.com', NULL, '$2y$10$ovrH1.Nlrevb3I.1mD.NyumDnwSLKGhjTP/I/Ht..p52iOHyxyQ9O', 'I', NULL, 709, 'DEF-USERS', NULL, '2024-11-30 04:03:52', '2024-11-30 04:03:52'),
(101, 'Francis', 'Angeles', '', '+8615900603216', 'Francis Angeles', 'francis.angeles710@gmail.com', NULL, '$2y$10$DbdXNBNO/KuKiWANGrET5.RIdNPKEUNV74FNlV1GPJpZNTwTRJEQ6', 'A', NULL, 710, 'DEF-USERS', NULL, '2024-11-30 04:14:40', '2024-11-30 04:15:52'),
(102, 'human$', 'crazy', '', '+639453570677', 'Human$ crazy', 'reinjunelaride34@gmail.com', NULL, '$2y$10$.7NkLxaKy4p9Vax0K0M7Me2lZBBlM0B3DGSBRjbL2WPPCHiJmLCAK', 'A', NULL, 711, 'DEF-HR', NULL, '2024-12-01 21:12:05', '2024-12-01 21:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `userseminars`
--

CREATE TABLE `userseminars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `transNo` int(11) NOT NULL,
  `seminar_title` varchar(255) DEFAULT NULL,
  `seminar_provider` varchar(255) DEFAULT NULL,
  `date_completed` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usertrainings`
--

CREATE TABLE `usertrainings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `transNo` int(11) DEFAULT NULL,
  `training_title` varchar(255) DEFAULT NULL,
  `training_provider` varchar(255) DEFAULT NULL,
  `date_completed` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_codes`
--
ALTER TABLE `email_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resources_code_unique` (`code`),
  ADD UNIQUE KEY `resources_email_unique` (`email`),
  ADD UNIQUE KEY `resources_h1_email_unique` (`h1_email`);

--
-- Indexes for table `roleaccessmenus`
--
ALTER TABLE `roleaccessmenus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roleaccesssubmenus`
--
ALTER TABLE `roleaccesssubmenus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `submenus`
--
ALTER TABLE `submenus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usercapabilities`
--
ALTER TABLE `usercapabilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usercertificates`
--
ALTER TABLE `usercertificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usereducations`
--
ALTER TABLE `usereducations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `useremploymentrecords`
--
ALTER TABLE `useremploymentrecords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userprofiles`
--
ALTER TABLE `userprofiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userprofiles_email_unique` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_code_unique` (`code`);

--
-- Indexes for table `userseminars`
--
ALTER TABLE `userseminars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usertrainings`
--
ALTER TABLE `usertrainings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_codes`
--
ALTER TABLE `email_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `roleaccessmenus`
--
ALTER TABLE `roleaccessmenus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `roleaccesssubmenus`
--
ALTER TABLE `roleaccesssubmenus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `submenus`
--
ALTER TABLE `submenus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usercapabilities`
--
ALTER TABLE `usercapabilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usercertificates`
--
ALTER TABLE `usercertificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usereducations`
--
ALTER TABLE `usereducations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `useremploymentrecords`
--
ALTER TABLE `useremploymentrecords`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userprofiles`
--
ALTER TABLE `userprofiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `userseminars`
--
ALTER TABLE `userseminars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usertrainings`
--
ALTER TABLE `usertrainings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
