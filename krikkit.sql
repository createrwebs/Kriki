-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 03, 2026 at 05:43 PM
-- Server version: 10.11.18-MariaDB-ubu2404
-- PHP Version: 8.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `krikkit_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_abilities`
--

CREATE TABLE `access_abilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `cluster` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `access_abilities`
--

INSERT INTO `access_abilities` (`id`, `code`, `cluster`, `title`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.access', 'dashboard', 'Access dashboard', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(2, 'users.browse', 'users', 'Browse users', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(3, 'users.compose', 'users', 'Compose users', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(4, 'users.revise', 'users', 'Revise users', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(5, 'sessions.browse', 'sessions', 'Browse login sessions', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(6, 'roles.browse', 'roles', 'Browse access roles', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(7, 'roles.compose', 'roles', 'Compose access roles', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(8, 'roles.revise', 'roles', 'Revise access roles', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(9, 'roles.retire', 'roles', 'Retire access roles', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(10, 'security.self', 'security', 'Manage own security', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(11, 'ai.revise', 'ai', 'Configure API integrations', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(12, 'packs.browse', 'packs', 'Browse packs', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(13, 'packs.compose', 'packs', 'Compose packs', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(14, 'packs.revise', 'packs', 'Revise packs', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(15, 'packs.retire', 'packs', 'Retire packs', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(16, 'payments.revise', 'payments', 'Configure payment methods', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(17, 'finance.browse', 'finance', 'Browse finance', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(18, 'finance.revise', 'finance', 'Revise finance', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(19, 'projects.browse', 'projects', 'Browse Lab projects', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(20, 'projects.revise', 'projects', 'Revise Lab projects', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(21, 'projects.retire', 'projects', 'Retire Lab projects', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(22, 'settings.revise', 'settings', 'Configure site settings', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(23, 'contacts.browse', 'contacts', 'Browse contacts', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(24, 'contacts.revise', 'contacts', 'Reply to contacts', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(25, 'blog.browse', 'blog', 'Browse blog posts', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(26, 'blog.compose', 'blog', 'Compose blog posts', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(27, 'blog.revise', 'blog', 'Revise blog posts', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(28, 'blog.retire', 'blog', 'Retire blog posts', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(29, 'pages.browse', 'pages', 'Browse pages', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(30, 'pages.compose', 'pages', 'Compose pages', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(31, 'pages.revise', 'pages', 'Revise pages', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(32, 'pages.retire', 'pages', 'Retire pages', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(33, 'languages.browse', 'languages', 'Browse languages', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(34, 'languages.compose', 'languages', 'Compose languages', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(35, 'languages.revise', 'languages', 'Revise languages', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(36, 'languages.retire', 'languages', 'Retire languages', '2026-09-03 11:42:49', '2026-09-03 11:42:49');

-- --------------------------------------------------------

--
-- Table structure for table `access_roles`
--

CREATE TABLE `access_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `access_roles`
--

INSERT INTO `access_roles` (`id`, `slug`, `title`, `summary`, `locked`, `created_at`, `updated_at`) VALUES
(1, 'owner', 'Owner', 'Full workspace authority. Locked system role.', 1, '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(2, 'member', 'Member', 'Standard workspace access and personal security.', 0, '2026-09-03 11:42:49', '2026-09-03 11:42:49');

-- --------------------------------------------------------

--
-- Table structure for table `access_role_ability`
--

CREATE TABLE `access_role_ability` (
  `access_role_id` bigint(20) UNSIGNED NOT NULL,
  `access_ability_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `access_role_ability`
--

INSERT INTO `access_role_ability` (`access_role_id`, `access_ability_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(2, 1),
(2, 10);

-- --------------------------------------------------------

--
-- Table structure for table `ai_provider_credentials`
--

CREATE TABLE `ai_provider_credentials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) NOT NULL,
  `application_id` varchar(255) DEFAULT NULL,
  `api_key` text DEFAULT NULL,
  `api_secret` text DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_workspace_settings`
--

CREATE TABLE `ai_workspace_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `default_model` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `excerpt` varchar(400) DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `published_on` date DEFAULT NULL,
  `author_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entitlement_plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `reply_body` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `replied_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_grants`
--

CREATE TABLE `credit_grants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` int(11) NOT NULL,
  `kind` varchar(255) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `granted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `entitlement_plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `entitlement_plans`
--

CREATE TABLE `entitlement_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `rank` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `price_monthly` decimal(10,2) DEFAULT NULL,
  `price_yearly` decimal(10,2) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `entitlement_plans`
--

INSERT INTO `entitlement_plans` (`id`, `public_id`, `slug`, `title`, `summary`, `rank`, `price_monthly`, `price_yearly`, `is_default`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '01M1KVHWXT242F5JHPMSCCMF9X', 'free', 'Free', 'A small project allowance and monthly Lab credits.', 10, 0.00, 0.00, 1, 1, '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(2, '01M1KVHWYP57QRJYVRCJ7EWR4T', 'pro', 'Pro', 'More projects, a larger Lab credit pool, and unlocked extras.', 20, 29.00, 290.00, 0, 1, '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(3, '01M1KVHWZ50VTY6T1QK6101P8N', 'agency', 'Agency', 'Unlimited projects and Lab credits for agencies and enterprise teams.', 30, NULL, NULL, 0, 1, '2026-09-03 11:42:49', '2026-09-03 11:42:49');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `entitlement_plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_entitlement_id` bigint(20) UNSIGNED DEFAULT NULL,
  `driver` varchar(255) NOT NULL,
  `provider_invoice_id` varchar(255) DEFAULT NULL,
  `provider_subscription_id` varchar(255) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `paid_at` timestamp NULL DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `refunded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
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
-- Table structure for table `lab_messages`
--

CREATE TABLE `lab_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lab_project_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(16) NOT NULL,
  `content` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_projects`
--

CREATE TABLE `lab_projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `kit_version` tinyint(3) UNSIGNED DEFAULT NULL,
  `stack` varchar(64) DEFAULT NULL,
  `workspace_status` varchar(16) NOT NULL DEFAULT 'pending',
  `credits_spent` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `frozen_at` timestamp NULL DEFAULT NULL,
  `opened_at` timestamp NULL DEFAULT NULL,
  `starred_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_project_remotes`
--

CREATE TABLE `lab_project_remotes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lab_project_id` bigint(20) UNSIGNED NOT NULL,
  `driver` varchar(24) NOT NULL DEFAULT 'github',
  `owner` varchar(100) NOT NULL,
  `repo` varchar(100) NOT NULL,
  `branch` varchar(200) NOT NULL DEFAULT 'main',
  `root_directory` varchar(240) DEFAULT NULL,
  `html_url` varchar(500) DEFAULT NULL,
  `forked_from` varchar(220) DEFAULT NULL,
  `last_pushed_sha` varchar(64) DEFAULT NULL,
  `last_pulled_sha` varchar(64) DEFAULT NULL,
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_publications`
--

CREATE TABLE `lab_publications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lab_project_id` bigint(20) UNSIGNED NOT NULL,
  `public_id` varchar(26) NOT NULL,
  `kind` varchar(16) NOT NULL DEFAULT 'subdomain',
  `subdomain` varchar(48) DEFAULT NULL,
  `custom_host` varchar(253) DEFAULT NULL,
  `status` varchar(16) NOT NULL DEFAULT 'idle',
  `verify_token` varchar(64) NOT NULL,
  `custom_host_verified_at` timestamp NULL DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `last_error` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `code` varchar(16) NOT NULL,
  `name` varchar(255) NOT NULL,
  `native_name` varchar(255) DEFAULT NULL,
  `direction` varchar(3) NOT NULL DEFAULT 'ltr',
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `public_id`, `code`, `name`, `native_name`, `direction`, `enabled`, `is_default`, `created_at`, `updated_at`) VALUES
(1, '01M1KVHWZRH5JVMTRZDXN44Y6R', 'en', 'English', 'English', 'ltr', 1, 1, '2026-09-03 11:42:49', '2026-09-03 11:42:49');

-- --------------------------------------------------------

--
-- Table structure for table `language_lines`
--

CREATE TABLE `language_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_id` bigint(20) UNSIGNED NOT NULL,
  `group` varchar(64) NOT NULL,
  `key` varchar(512) NOT NULL,
  `value` text DEFAULT NULL,
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_03_120000_add_two_factor_columns_to_users_table', 1),
(5, '2026_08_03_121000_add_two_factor_last_timestep_to_users_table', 1),
(6, '2026_08_03_140000_create_access_control_tables', 1),
(7, '2026_08_03_151300_add_public_id_to_users_table', 1),
(8, '2026_08_03_200000_create_ai_settings_tables', 1),
(9, '2026_08_04_020500_add_appearance_to_users_table', 1),
(10, '2026_08_04_051600_create_lab_projects_table', 1),
(11, '2026_08_04_070600_add_workspace_columns_to_lab_projects_table', 1),
(12, '2026_08_06_060400_add_metadata_to_lab_messages_table', 1),
(13, '2026_08_09_220000_add_profile_fields_to_users_table', 1),
(14, '2026_08_11_022600_create_workspace_oauth_apps_table', 1),
(15, '2026_08_11_022700_create_user_vcs_links_table', 1),
(16, '2026_08_11_035500_add_settings_to_workspace_oauth_apps_table', 1),
(17, '2026_08_15_231300_create_entitlement_tables', 1),
(18, '2026_08_15_235900_rename_ai_generations_to_lab_credits', 1),
(19, '2026_08_16_001700_add_pack_pricing_to_entitlement_plans', 1),
(20, '2026_08_16_002800_drop_currency_from_entitlement_plans', 1),
(21, '2026_08_16_003800_create_payment_gateway_tables', 1),
(22, '2026_08_16_031300_add_credits_spent_to_lab_projects_table', 1),
(23, '2026_08_16_040000_create_site_settings_table', 1),
(24, '2026_08_16_041300_create_language_tables', 1),
(25, '2026_08_16_052000_create_finance_tables', 1),
(26, '2026_08_16_053100_add_frozen_at_to_lab_projects_table', 1),
(27, '2026_08_16_060000_create_blog_posts_and_site_pages_tables', 1),
(28, '2026_08_16_081000_create_lab_publications_table', 1),
(29, '2026_08_18_015300_create_workspace_datastores_table', 1),
(30, '2026_08_18_021000_create_user_datastore_links_table', 1),
(31, '2026_08_19_060800_create_lab_project_remotes_table', 1),
(32, '2026_08_19_061000_create_newsletter_subscribers_table', 1),
(33, '2026_08_19_070000_stamp_pro_pack_checkout_prices', 1),
(34, '2026_08_21_074600_add_opened_at_to_lab_projects_table', 1),
(35, '2026_08_22_071900_create_contacts_table', 1),
(36, '2026_08_22_081300_add_starred_at_to_lab_projects_table', 1),
(37, '2026_08_27_081000_add_preferences_to_users_table', 1),
(38, '2026_08_31_052800_add_unsplash_fields_to_ai_provider_credentials', 1);

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 0,
  `mode` varchar(255) NOT NULL DEFAULT 'test',
  `public_key` text DEFAULT NULL,
  `secret_key` text DEFAULT NULL,
  `webhook_secret` text DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_webhook_events`
--

CREATE TABLE `payment_webhook_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `driver` varchar(255) NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `provider_event_id` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'received',
  `attempts` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `last_error` text DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_entitlement_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plan_grants`
--

CREATE TABLE `plan_grants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `entitlement_plan_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `kind` varchar(255) NOT NULL,
  `allowed` tinyint(1) NOT NULL DEFAULT 1,
  `ceiling` int(10) UNSIGNED DEFAULT NULL,
  `window` varchar(255) NOT NULL DEFAULT 'none',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plan_grants`
--

INSERT INTO `plan_grants` (`id`, `entitlement_plan_id`, `code`, `kind`, `allowed`, `ceiling`, `window`, `created_at`, `updated_at`) VALUES
(1, 1, 'projects', 'quota', 1, 3, 'lifetime', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(2, 1, 'lab_credits', 'quota', 1, 100, 'monthly', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(3, 1, 'custom_subdomain', 'feature', 0, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(4, 1, 'custom_domain', 'feature', 0, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(5, 1, 'advanced_export', 'feature', 0, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(6, 1, 'github_import', 'feature', 0, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(7, 2, 'projects', 'quota', 1, 25, 'lifetime', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(8, 2, 'lab_credits', 'quota', 1, 2500, 'monthly', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(9, 2, 'custom_subdomain', 'feature', 1, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(10, 2, 'custom_domain', 'feature', 1, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(11, 2, 'advanced_export', 'feature', 1, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(12, 2, 'github_import', 'feature', 1, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(13, 3, 'projects', 'quota', 1, NULL, 'lifetime', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(14, 3, 'lab_credits', 'quota', 1, NULL, 'monthly', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(15, 3, 'custom_subdomain', 'feature', 1, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(16, 3, 'custom_domain', 'feature', 1, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(17, 3, 'advanced_export', 'feature', 1, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49'),
(18, 3, 'github_import', 'feature', 1, NULL, 'none', '2026-09-03 11:42:49', '2026-09-03 11:42:49');

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


-- --------------------------------------------------------

--
-- Table structure for table `site_pages`
--

CREATE TABLE `site_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` longtext DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `published_on` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `payload`, `created_at`, `updated_at`) VALUES
(1, '{\"permit\":{\"tone\":\"deferred\",\"secret\":\"\",\"tail\":\"\",\"checked_at\":\"2026-09-03T14:42:33+00:00\",\"notice\":\"License skipped. Bind a key later from Settings \\u2192 License.\",\"kind\":\"\",\"bound_host\":\"\",\"cap\":null}}', '2026-09-03 11:42:55', '2026-09-03 11:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `usage_ledgers`
--

CREATE TABLE `usage_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `window_key` varchar(255) NOT NULL,
  `consumed` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` varchar(26) DEFAULT NULL,
  `access_role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `status` varchar(16) NOT NULL DEFAULT 'active',
  `avatar_path` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `two_factor_last_timestep` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `appearance` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`appearance`)),
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `public_id`, `access_role_id`, `name`, `username`, `email`, `phone`, `country`, `status`, `avatar_path`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `two_factor_last_timestep`, `remember_token`, `appearance`, `preferences`, `created_at`, `updated_at`) VALUES
(1, '01M1KVJ2DQ0ZP1YX82D2PBZ98B', 1, 'asdasda', 'asdasda', 'admin@krikkit.space', NULL, NULL, 'active', NULL, '2026-09-03 11:42:55', '$2y$12$eh7E.G/uDXGXHUSXG7OsMO.Ezfg3Wb1RilQDi3y6gX.WUPWMNu/OG', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-03 11:42:55', '2026-09-03 11:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `user_datastore_links`
--

CREATE TABLE `user_datastore_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `driver` varchar(32) NOT NULL,
  `external_id` varchar(64) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `access_token` text DEFAULT NULL,
  `refresh_token` text DEFAULT NULL,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `host_url` varchar(255) DEFAULT NULL,
  `publishable_token` text DEFAULT NULL,
  `steward_token` text DEFAULT NULL,
  `project_ref` varchar(64) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `organization_id` varchar(64) DEFAULT NULL,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `linked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_entitlements`
--

CREATE TABLE `user_entitlements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `public_id` char(26) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `entitlement_plan_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `driver` varchar(255) DEFAULT NULL,
  `provider_subscription_id` varchar(255) DEFAULT NULL,
  `interval` varchar(255) DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `last_paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_entitlements`
--

INSERT INTO `user_entitlements` (`id`, `public_id`, `user_id`, `entitlement_plan_id`, `status`, `driver`, `provider_subscription_id`, `interval`, `started_at`, `ends_at`, `last_paid_at`, `created_at`, `updated_at`) VALUES
(1, '01M1KVJ2E1V2XVHFCNDS31B4T0', 1, 3, 'active', NULL, NULL, NULL, '2026-09-03 11:42:55', NULL, NULL, '2026-09-03 11:42:55', '2026-09-03 11:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `user_vcs_links`
--

CREATE TABLE `user_vcs_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `driver` varchar(32) NOT NULL,
  `external_id` varchar(64) NOT NULL,
  `login` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `access_token` text NOT NULL,
  `scopes` varchar(255) DEFAULT NULL,
  `linked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_datastores`
--

CREATE TABLE `workspace_datastores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kind` varchar(32) NOT NULL,
  `host_url` varchar(255) DEFAULT NULL,
  `publishable_token` text DEFAULT NULL,
  `steward_token` text DEFAULT NULL,
  `console_token` text DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workspace_oauth_apps`
--

CREATE TABLE `workspace_oauth_apps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver` varchar(32) NOT NULL,
  `client_id` varchar(255) DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_abilities`
--
ALTER TABLE `access_abilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `access_abilities_code_unique` (`code`);

--
-- Indexes for table `access_roles`
--
ALTER TABLE `access_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `access_roles_slug_unique` (`slug`);

--
-- Indexes for table `access_role_ability`
--
ALTER TABLE `access_role_ability`
  ADD PRIMARY KEY (`access_role_id`,`access_ability_id`),
  ADD KEY `access_role_ability_access_ability_id_foreign` (`access_ability_id`);

--
-- Indexes for table `ai_provider_credentials`
--
ALTER TABLE `ai_provider_credentials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ai_provider_credentials_provider_unique` (`provider`);

--
-- Indexes for table `ai_workspace_settings`
--
ALTER TABLE `ai_workspace_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blog_posts_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  ADD KEY `blog_posts_author_id_foreign` (`author_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contacts_public_id_unique` (`public_id`),
  ADD KEY `contacts_user_id_foreign` (`user_id`),
  ADD KEY `contacts_entitlement_plan_id_foreign` (`entitlement_plan_id`),
  ADD KEY `contacts_replied_by_foreign` (`replied_by`),
  ADD KEY `contacts_read_at_replied_at_index` (`read_at`,`replied_at`);

--
-- Indexes for table `credit_grants`
--
ALTER TABLE `credit_grants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `credit_grants_public_id_unique` (`public_id`),
  ADD KEY `credit_grants_granted_by_foreign` (`granted_by`),
  ADD KEY `credit_grants_entitlement_plan_id_foreign` (`entitlement_plan_id`),
  ADD KEY `credit_grants_invoice_id_foreign` (`invoice_id`),
  ADD KEY `credit_grants_user_id_created_at_index` (`user_id`,`created_at`);

--
-- Indexes for table `entitlement_plans`
--
ALTER TABLE `entitlement_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entitlement_plans_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `entitlement_plans_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `invoices_driver_provider_invoice_id_unique` (`driver`,`provider_invoice_id`),
  ADD KEY `invoices_user_id_foreign` (`user_id`),
  ADD KEY `invoices_entitlement_plan_id_foreign` (`entitlement_plan_id`),
  ADD KEY `invoices_user_entitlement_id_foreign` (`user_entitlement_id`),
  ADD KEY `invoices_refunded_by_foreign` (`refunded_by`),
  ADD KEY `invoices_status_paid_at_index` (`status`,`paid_at`);

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
-- Indexes for table `lab_messages`
--
ALTER TABLE `lab_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lab_messages_lab_project_id_id_index` (`lab_project_id`,`id`);

--
-- Indexes for table `lab_projects`
--
ALTER TABLE `lab_projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_projects_uuid_unique` (`uuid`),
  ADD KEY `lab_projects_user_id_foreign` (`user_id`);

--
-- Indexes for table `lab_project_remotes`
--
ALTER TABLE `lab_project_remotes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_project_remotes_lab_project_id_driver_unique` (`lab_project_id`,`driver`);

--
-- Indexes for table `lab_publications`
--
ALTER TABLE `lab_publications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_publications_lab_project_id_unique` (`lab_project_id`),
  ADD UNIQUE KEY `lab_publications_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `lab_publications_subdomain_unique` (`subdomain`),
  ADD UNIQUE KEY `lab_publications_custom_host_unique` (`custom_host`),
  ADD KEY `lab_publications_status_subdomain_index` (`status`,`subdomain`),
  ADD KEY `lab_publications_status_custom_host_index` (`status`,`custom_host`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `languages_code_unique` (`code`);

--
-- Indexes for table `language_lines`
--
ALTER TABLE `language_lines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `language_lines_language_id_group_key_unique` (`language_id`,`group`,`key`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `newsletter_subscribers_email_unique` (`email`),
  ADD UNIQUE KEY `newsletter_subscribers_public_id_unique` (`public_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_gateways_driver_unique` (`driver`);

--
-- Indexes for table `payment_webhook_events`
--
ALTER TABLE `payment_webhook_events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_webhook_events_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `payment_webhook_events_driver_provider_event_id_unique` (`driver`,`provider_event_id`),
  ADD KEY `payment_webhook_events_driver_created_at_index` (`driver`,`created_at`),
  ADD KEY `payment_webhook_events_invoice_id_foreign` (`invoice_id`),
  ADD KEY `payment_webhook_events_user_entitlement_id_foreign` (`user_entitlement_id`),
  ADD KEY `payment_webhook_events_status_index` (`status`);

--
-- Indexes for table `plan_grants`
--
ALTER TABLE `plan_grants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plan_grants_entitlement_plan_id_code_unique` (`entitlement_plan_id`,`code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `site_pages`
--
ALTER TABLE `site_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_pages_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `site_pages_slug_unique` (`slug`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usage_ledgers`
--
ALTER TABLE `usage_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usage_ledgers_user_id_code_window_key_unique` (`user_id`,`code`,`window_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_access_role_id_foreign` (`access_role_id`);

--
-- Indexes for table `user_datastore_links`
--
ALTER TABLE `user_datastore_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_datastore_links_user_id_driver_unique` (`user_id`,`driver`),
  ADD KEY `user_datastore_links_driver_project_ref_index` (`driver`,`project_ref`);

--
-- Indexes for table `user_entitlements`
--
ALTER TABLE `user_entitlements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_entitlements_public_id_unique` (`public_id`),
  ADD UNIQUE KEY `user_entitlements_user_id_unique` (`user_id`),
  ADD KEY `user_entitlements_entitlement_plan_id_foreign` (`entitlement_plan_id`),
  ADD KEY `user_entitlements_status_interval_index` (`status`,`interval`);

--
-- Indexes for table `user_vcs_links`
--
ALTER TABLE `user_vcs_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_vcs_links_user_id_driver_unique` (`user_id`,`driver`),
  ADD KEY `user_vcs_links_driver_external_id_index` (`driver`,`external_id`);

--
-- Indexes for table `workspace_datastores`
--
ALTER TABLE `workspace_datastores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `workspace_datastores_kind_unique` (`kind`);

--
-- Indexes for table `workspace_oauth_apps`
--
ALTER TABLE `workspace_oauth_apps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `workspace_oauth_apps_driver_unique` (`driver`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_abilities`
--
ALTER TABLE `access_abilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `access_roles`
--
ALTER TABLE `access_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ai_provider_credentials`
--
ALTER TABLE `ai_provider_credentials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_workspace_settings`
--
ALTER TABLE `ai_workspace_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_grants`
--
ALTER TABLE `credit_grants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `entitlement_plans`
--
ALTER TABLE `entitlement_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_messages`
--
ALTER TABLE `lab_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_projects`
--
ALTER TABLE `lab_projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_project_remotes`
--
ALTER TABLE `lab_project_remotes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_publications`
--
ALTER TABLE `lab_publications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `language_lines`
--
ALTER TABLE `language_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_webhook_events`
--
ALTER TABLE `payment_webhook_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plan_grants`
--
ALTER TABLE `plan_grants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `site_pages`
--
ALTER TABLE `site_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `usage_ledgers`
--
ALTER TABLE `usage_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_datastore_links`
--
ALTER TABLE `user_datastore_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_entitlements`
--
ALTER TABLE `user_entitlements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_vcs_links`
--
ALTER TABLE `user_vcs_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workspace_datastores`
--
ALTER TABLE `workspace_datastores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workspace_oauth_apps`
--
ALTER TABLE `workspace_oauth_apps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_role_ability`
--
ALTER TABLE `access_role_ability`
  ADD CONSTRAINT `access_role_ability_access_ability_id_foreign` FOREIGN KEY (`access_ability_id`) REFERENCES `access_abilities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `access_role_ability_access_role_id_foreign` FOREIGN KEY (`access_role_id`) REFERENCES `access_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_entitlement_plan_id_foreign` FOREIGN KEY (`entitlement_plan_id`) REFERENCES `entitlement_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_replied_by_foreign` FOREIGN KEY (`replied_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `credit_grants`
--
ALTER TABLE `credit_grants`
  ADD CONSTRAINT `credit_grants_entitlement_plan_id_foreign` FOREIGN KEY (`entitlement_plan_id`) REFERENCES `entitlement_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `credit_grants_granted_by_foreign` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `credit_grants_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `credit_grants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_entitlement_plan_id_foreign` FOREIGN KEY (`entitlement_plan_id`) REFERENCES `entitlement_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_refunded_by_foreign` FOREIGN KEY (`refunded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_user_entitlement_id_foreign` FOREIGN KEY (`user_entitlement_id`) REFERENCES `user_entitlements` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lab_messages`
--
ALTER TABLE `lab_messages`
  ADD CONSTRAINT `lab_messages_lab_project_id_foreign` FOREIGN KEY (`lab_project_id`) REFERENCES `lab_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_projects`
--
ALTER TABLE `lab_projects`
  ADD CONSTRAINT `lab_projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lab_project_remotes`
--
ALTER TABLE `lab_project_remotes`
  ADD CONSTRAINT `lab_project_remotes_lab_project_id_foreign` FOREIGN KEY (`lab_project_id`) REFERENCES `lab_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lab_publications`
--
ALTER TABLE `lab_publications`
  ADD CONSTRAINT `lab_publications_lab_project_id_foreign` FOREIGN KEY (`lab_project_id`) REFERENCES `lab_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `language_lines`
--
ALTER TABLE `language_lines`
  ADD CONSTRAINT `language_lines_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_webhook_events`
--
ALTER TABLE `payment_webhook_events`
  ADD CONSTRAINT `payment_webhook_events_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_webhook_events_user_entitlement_id_foreign` FOREIGN KEY (`user_entitlement_id`) REFERENCES `user_entitlements` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `plan_grants`
--
ALTER TABLE `plan_grants`
  ADD CONSTRAINT `plan_grants_entitlement_plan_id_foreign` FOREIGN KEY (`entitlement_plan_id`) REFERENCES `entitlement_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `usage_ledgers`
--
ALTER TABLE `usage_ledgers`
  ADD CONSTRAINT `usage_ledgers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_access_role_id_foreign` FOREIGN KEY (`access_role_id`) REFERENCES `access_roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_datastore_links`
--
ALTER TABLE `user_datastore_links`
  ADD CONSTRAINT `user_datastore_links_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_entitlements`
--
ALTER TABLE `user_entitlements`
  ADD CONSTRAINT `user_entitlements_entitlement_plan_id_foreign` FOREIGN KEY (`entitlement_plan_id`) REFERENCES `entitlement_plans` (`id`),
  ADD CONSTRAINT `user_entitlements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_vcs_links`
--
ALTER TABLE `user_vcs_links`
  ADD CONSTRAINT `user_vcs_links_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
