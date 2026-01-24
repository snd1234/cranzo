-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2026 at 01:58 PM
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
-- Database: `gulfbio_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `Address` varchar(200) DEFAULT NULL,
  `role` tinyint(2) DEFAULT 1 COMMENT '1-Admin,2-Editor,3-Viewer ',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0-Inactive ,1-Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `mobile_number`, `Address`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@example.com', '$2y$12$3AEMaGc2tpC0UYp6n263G./fNegwNE8tFm43PV9aXZC.LNRsgaF7C', NULL, NULL, 1, 1, '2025-11-05 02:29:00', '2025-11-06 09:07:50'),
(2, 'asdadad', 'admin222@example.com', '$2y$12$h67MRKrIe50Rn4AByJ5Lsum6G22zfGIFtVyqdo6zFjHSkcxxZJ.fS', NULL, NULL, 1, 1, '2025-11-06 09:22:44', '2025-11-06 09:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL COMMENT '1-blog ,2-news,3-webinar,4-events',
  `status` tinyint(4) DEFAULT NULL COMMENT '0-Inactive,1-Active',
  `start_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `short_description`, `content`, `image`, `type`, `status`, `start_date`, `created_at`, `updated_at`) VALUES
(1, 'Gulf Bio Analytical provide huge ranges of LC columns to customer', 'gulf-bio-analytical-provide-huge-ranges-of-lc-columns-to-customer', 'Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', 'uploads/blogs/oeMUxqrk5bvsms2SsEIwBbErsekMukIgoc2511jH.jpg', 1, 1, NULL, '2026-01-17 04:35:22', '2026-01-17 04:35:22'),
(2, 'Gulf Bio Analytical provide huge ranges of LC columns to customer ddd', 'gulf-bio-analytical-provide-huge-ranges-of-lc-columns-to-customer', 'Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', 'uploads/blogs/GIJwUBWwyk7c3i2rJ6pXqfomAvVWrYIOQbJF0xSU.jpg', 1, 1, NULL, '2026-01-17 04:35:36', '2026-01-17 05:37:22'),
(3, 'Unlock Tomorrow’s Science at Innovation Day', 'gulf-bio-analytical-provide-huge', 'Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price</p>', 'uploads/blogs/uJWqE8JCaKMssPxyAaDBRSDhKsh5e0THxC3TsaoD.jpg', 2, 1, NULL, '2026-01-17 05:38:48', '2026-01-18 02:33:21'),
(4, 'Gulf Bio Analytical New Test', 'gulf-bio-analytical-provide-huge', 'Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price</p>', 'uploads/blogs/nqw1w3Gka7BhuKpMsrZ9MpwfUZHseVqoAd6zCYEF.jpg', 2, 1, NULL, '2026-01-17 05:39:09', '2026-01-18 02:29:46'),
(5, 'Clinical Genomics Day  Riyadh', 'clinical-genomics-day-riyadh', 'See the break throughs shaping the future of clinical and research in person. Integrated Gulf Biosystems and Thermo Fisher Scientific are hosting a day for scientists, researchers, and innovators.', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', 'uploads/blogs/sqrFPfprGW4V8qdQUHJVprGCYud9DfYd9LVXXHxU.jpg', 4, 1, NULL, '2026-01-18 02:29:25', '2026-01-18 02:29:25'),
(6, 'Clinical Genomics Day – Dubai', 'clinical-genomics-day-dubai', 'See the break throughs shaping the future of clinical and research in person. Integrated Gulf Biosystems and Thermo Fisher Scientific are hosting a day for scientists, researchers, and innovators.', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', 'uploads/blogs/OqlZNUhYTzWp63X2KkUrppBsk0iwITjZn0HBZHGN.jpg', 4, 1, NULL, '2026-01-18 02:30:38', '2026-01-18 02:30:38'),
(7, 'Medlab 2026', 'medlab-2026', 'Medlab Middle East 2026 exhibition, which will be held from February 9-12, 2026 at the Dubai World Trade Centre in Dubai, UAE. It is a leading event for the laboratory and healthcare industry in the Middle East.', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', 'uploads/blogs/Z4D3pPGfcXYbxDJSt5EmydNuDsCIAAznl89arBVT.jpg', 4, 1, NULL, '2026-01-18 02:31:46', '2026-01-18 02:31:46'),
(8, 'Innovation Day – Unlock Tomorrow’s Science | Jeddah', 'innovation-day-unlock-tomorrows-science-jeddah', 'Our Innovation Day, held on 4th September at Hotel Warwick, Jeddah in collaboration with Thermo Fisher Scientific, concluded successfully.\r\n\r\nThe event brought together scientific leaders, industry professionals, and key stakeholders, featuring insightful sessions from Thermo Fisher Scientific speakers and discussions on advancing innovation in life sciences.', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', 'uploads/blogs/aHrHQgBDOYzV3V5Oku1EuS9s0whOKCjW73oFT0J2.jpg', 2, 1, NULL, '2026-01-18 02:32:51', '2026-01-18 02:32:51'),
(9, 'MENA ART 2025, hosted by Sidra Medicine and Qatar Foundation', 'mena-art-2025-hosted-by-sidra-medicine-and-qatar-foundation', 'We\'re excited to share some highlights from the AVITI 24 Roadshow, which successfully wrapped up last week with impactful sessions across leading universities in the UAE and KSA.', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', 'uploads/blogs/2k88jCjIPbSesmwMUvoRVZY0Jy9Os4tyeVrhUmN1.jpg', 2, 1, NULL, '2026-01-18 02:34:17', '2026-01-18 02:34:17'),
(10, 'miRNA workflow by Thermo Fisher Scientific', 'mirna-workflow-by-thermo-fisher-scientific', 'Comprehensive Approaches to miRNA Analysis: From Sample Extraction to qPCR/dPCR MicroRNA analysis is essential for understanding gene regulation and expression. This webinar reviews methodologies for miRNA sample extraction and introduces real-time PCR and TaqMan assays for miRNA quantification. It also addresses digital PCR for absolute miRNA quantification and outlines workflows for pri-miRNA and mature miRNA using various chemistries.', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', 'uploads/blogs/aWwIHcn1AgdGLLodupEbPit3BMmH5EK0qZtfTcqP.jpg', 3, 1, NULL, '2026-01-18 02:35:49', '2026-01-18 02:35:49');

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
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `iso2` char(2) NOT NULL,
  `iso3` char(3) NOT NULL,
  `phone_code` varchar(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso2`, `iso3`, `phone_code`, `status`) VALUES
(1, 'Afghanistan', 'AF', 'AFG', '+93', 1),
(2, 'Albania', 'AL', 'ALB', '+355', 1),
(3, 'Algeria', 'DZ', 'DZA', '+213', 1),
(4, 'Andorra', 'AD', 'AND', '+376', 1),
(5, 'Angola', 'AO', 'AGO', '+244', 1),
(6, 'Antigua and Barbuda', 'AG', 'ATG', '+1', 1),
(7, 'Argentina', 'AR', 'ARG', '+54', 1),
(8, 'Armenia', 'AM', 'ARM', '+374', 1),
(9, 'Australia', 'AU', 'AUS', '+61', 1),
(10, 'Austria', 'AT', 'AUT', '+43', 1),
(11, 'Azerbaijan', 'AZ', 'AZE', '+994', 1),
(12, 'Bahamas', 'BS', 'BHS', '+1', 1),
(13, 'Bahrain', 'BH', 'BHR', '+973', 1),
(14, 'Bangladesh', 'BD', 'BGD', '+880', 1),
(15, 'Barbados', 'BB', 'BRB', '+1', 1),
(16, 'Belarus', 'BY', 'BLR', '+375', 1),
(17, 'Belgium', 'BE', 'BEL', '+32', 1),
(18, 'Belize', 'BZ', 'BLZ', '+501', 1),
(19, 'Benin', 'BJ', 'BEN', '+229', 1),
(20, 'Bhutan', 'BT', 'BTN', '+975', 1),
(21, 'Bolivia', 'BO', 'BOL', '+591', 1),
(22, 'Bosnia and Herzegovina', 'BA', 'BIH', '+387', 1),
(23, 'Botswana', 'BW', 'BWA', '+267', 1),
(24, 'Brazil', 'BR', 'BRA', '+55', 1),
(25, 'Brunei', 'BN', 'BRN', '+673', 1),
(26, 'Bulgaria', 'BG', 'BGR', '+359', 1),
(27, 'Burkina Faso', 'BF', 'BFA', '+226', 1),
(28, 'Burundi', 'BI', 'BDI', '+257', 1),
(29, 'Cambodia', 'KH', 'KHM', '+855', 1),
(30, 'Cameroon', 'CM', 'CMR', '+237', 1),
(31, 'Canada', 'CA', 'CAN', '+1', 1),
(32, 'Cape Verde', 'CV', 'CPV', '+238', 1),
(33, 'Central African Republic', 'CF', 'CAF', '+236', 1),
(34, 'Chad', 'TD', 'TCD', '+235', 1),
(35, 'Chile', 'CL', 'CHL', '+56', 1),
(36, 'China', 'CN', 'CHN', '+86', 1),
(37, 'Colombia', 'CO', 'COL', '+57', 1),
(38, 'Comoros', 'KM', 'COM', '+269', 1),
(39, 'Congo', 'CG', 'COG', '+242', 1),
(40, 'Costa Rica', 'CR', 'CRI', '+506', 1),
(41, 'Croatia', 'HR', 'HRV', '+385', 1),
(42, 'Cuba', 'CU', 'CUB', '+53', 1),
(43, 'Cyprus', 'CY', 'CYP', '+357', 1),
(44, 'Czech Republic', 'CZ', 'CZE', '+420', 1),
(45, 'Denmark', 'DK', 'DNK', '+45', 1),
(46, 'Djibouti', 'DJ', 'DJI', '+253', 1),
(47, 'Dominica', 'DM', 'DMA', '+1', 1),
(48, 'Dominican Republic', 'DO', 'DOM', '+1', 1),
(49, 'Ecuador', 'EC', 'ECU', '+593', 1),
(50, 'Egypt', 'EG', 'EGY', '+20', 1),
(51, 'El Salvador', 'SV', 'SLV', '+503', 1),
(52, 'Equatorial Guinea', 'GQ', 'GNQ', '+240', 1),
(53, 'Eritrea', 'ER', 'ERI', '+291', 1),
(54, 'Estonia', 'EE', 'EST', '+372', 1),
(55, 'Eswatini', 'SZ', 'SWZ', '+268', 1),
(56, 'Ethiopia', 'ET', 'ETH', '+251', 1),
(57, 'Fiji', 'FJ', 'FJI', '+679', 1),
(58, 'Finland', 'FI', 'FIN', '+358', 1),
(59, 'France', 'FR', 'FRA', '+33', 1),
(60, 'Gabon', 'GA', 'GAB', '+241', 1),
(61, 'Gambia', 'GM', 'GMB', '+220', 1),
(62, 'Georgia', 'GE', 'GEO', '+995', 1),
(63, 'Germany', 'DE', 'DEU', '+49', 1),
(64, 'Ghana', 'GH', 'GHA', '+233', 1),
(65, 'Greece', 'GR', 'GRC', '+30', 1),
(66, 'Grenada', 'GD', 'GRD', '+1', 1),
(67, 'Guatemala', 'GT', 'GTM', '+502', 1),
(68, 'Guinea', 'GN', 'GIN', '+224', 1),
(69, 'Guinea-Bissau', 'GW', 'GNB', '+245', 1),
(70, 'Guyana', 'GY', 'GUY', '+592', 1),
(71, 'Haiti', 'HT', 'HTI', '+509', 1),
(72, 'Honduras', 'HN', 'HND', '+504', 1),
(73, 'Hungary', 'HU', 'HUN', '+36', 1),
(74, 'Iceland', 'IS', 'ISL', '+354', 1),
(75, 'India', 'IN', 'IND', '+91', 1),
(76, 'Indonesia', 'ID', 'IDN', '+62', 1),
(77, 'Iran', 'IR', 'IRN', '+98', 1),
(78, 'Iraq', 'IQ', 'IRQ', '+964', 1),
(79, 'Ireland', 'IE', 'IRL', '+353', 1),
(80, 'Israel', 'IL', 'ISR', '+972', 1),
(81, 'Italy', 'IT', 'ITA', '+39', 1),
(82, 'Jamaica', 'JM', 'JAM', '+1', 1),
(83, 'Japan', 'JP', 'JPN', '+81', 1),
(84, 'Jordan', 'JO', 'JOR', '+962', 1),
(85, 'Kazakhstan', 'KZ', 'KAZ', '+7', 1),
(86, 'Kenya', 'KE', 'KEN', '+254', 1),
(87, 'Kiribati', 'KI', 'KIR', '+686', 1),
(88, 'Kuwait', 'KW', 'KWT', '+965', 1),
(89, 'Kyrgyzstan', 'KG', 'KGZ', '+996', 1),
(90, 'Laos', 'LA', 'LAO', '+856', 1),
(91, 'Latvia', 'LV', 'LVA', '+371', 1),
(92, 'Lebanon', 'LB', 'LBN', '+961', 1),
(93, 'Lesotho', 'LS', 'LSO', '+266', 1),
(94, 'Liberia', 'LR', 'LBR', '+231', 1),
(95, 'Libya', 'LY', 'LBY', '+218', 1),
(96, 'Liechtenstein', 'LI', 'LIE', '+423', 1),
(97, 'Lithuania', 'LT', 'LTU', '+370', 1),
(98, 'Luxembourg', 'LU', 'LUX', '+352', 1),
(99, 'Madagascar', 'MG', 'MDG', '+261', 1),
(100, 'Malawi', 'MW', 'MWI', '+265', 1),
(101, 'Malaysia', 'MY', 'MYS', '+60', 1),
(102, 'Maldives', 'MV', 'MDV', '+960', 1),
(103, 'Mali', 'ML', 'MLI', '+223', 1),
(104, 'Malta', 'MT', 'MLT', '+356', 1),
(105, 'Marshall Islands', 'MH', 'MHL', '+692', 1),
(106, 'Mauritania', 'MR', 'MRT', '+222', 1),
(107, 'Mauritius', 'MU', 'MUS', '+230', 1),
(108, 'Mexico', 'MX', 'MEX', '+52', 1),
(109, 'Micronesia', 'FM', 'FSM', '+691', 1),
(110, 'Moldova', 'MD', 'MDA', '+373', 1),
(111, 'Monaco', 'MC', 'MCO', '+377', 1),
(112, 'Mongolia', 'MN', 'MNG', '+976', 1),
(113, 'Montenegro', 'ME', 'MNE', '+382', 1),
(114, 'Morocco', 'MA', 'MAR', '+212', 1),
(115, 'Mozambique', 'MZ', 'MOZ', '+258', 1),
(116, 'Myanmar', 'MM', 'MMR', '+95', 1),
(117, 'Namibia', 'NA', 'NAM', '+264', 1),
(118, 'Nauru', 'NR', 'NRU', '+674', 1),
(119, 'Nepal', 'NP', 'NPL', '+977', 1),
(120, 'Netherlands', 'NL', 'NLD', '+31', 1),
(121, 'New Zealand', 'NZ', 'NZL', '+64', 1),
(122, 'Nicaragua', 'NI', 'NIC', '+505', 1),
(123, 'Niger', 'NE', 'NER', '+227', 1),
(124, 'Nigeria', 'NG', 'NGA', '+234', 1),
(125, 'North Korea', 'KP', 'PRK', '+850', 1),
(126, 'North Macedonia', 'MK', 'MKD', '+389', 1),
(127, 'Norway', 'NO', 'NOR', '+47', 1),
(128, 'Oman', 'OM', 'OMN', '+968', 1),
(129, 'Pakistan', 'PK', 'PAK', '+92', 1),
(130, 'Palau', 'PW', 'PLW', '+680', 1),
(131, 'Panama', 'PA', 'PAN', '+507', 1),
(132, 'Papua New Guinea', 'PG', 'PNG', '+675', 1),
(133, 'Paraguay', 'PY', 'PRY', '+595', 1),
(134, 'Peru', 'PE', 'PER', '+51', 1),
(135, 'Philippines', 'PH', 'PHL', '+63', 1),
(136, 'Poland', 'PL', 'POL', '+48', 1),
(137, 'Portugal', 'PT', 'PRT', '+351', 1),
(138, 'Qatar', 'QA', 'QAT', '+974', 1),
(139, 'Romania', 'RO', 'ROU', '+40', 1),
(140, 'Russia', 'RU', 'RUS', '+7', 1),
(141, 'Rwanda', 'RW', 'RWA', '+250', 1),
(142, 'Saint Kitts and Nevis', 'KN', 'KNA', '+1', 1),
(143, 'Saint Lucia', 'LC', 'LCA', '+1', 1),
(144, 'Saint Vincent and the Grenadines', 'VC', 'VCT', '+1', 1),
(145, 'Samoa', 'WS', 'WSM', '+685', 1),
(146, 'San Marino', 'SM', 'SMR', '+378', 1),
(147, 'Sao Tome and Principe', 'ST', 'STP', '+239', 1),
(148, 'Saudi Arabia', 'SA', 'SAU', '+966', 1),
(149, 'Senegal', 'SN', 'SEN', '+221', 1),
(150, 'Serbia', 'RS', 'SRB', '+381', 1),
(151, 'Seychelles', 'SC', 'SYC', '+248', 1),
(152, 'Sierra Leone', 'SL', 'SLE', '+232', 1),
(153, 'Singapore', 'SG', 'SGP', '+65', 1),
(154, 'Slovakia', 'SK', 'SVK', '+421', 1),
(155, 'Slovenia', 'SI', 'SVN', '+386', 1),
(156, 'Solomon Islands', 'SB', 'SLB', '+677', 1),
(157, 'Somalia', 'SO', 'SOM', '+252', 1),
(158, 'South Africa', 'ZA', 'ZAF', '+27', 1),
(159, 'South Korea', 'KR', 'KOR', '+82', 1),
(160, 'South Sudan', 'SS', 'SSD', '+211', 1),
(161, 'Spain', 'ES', 'ESP', '+34', 1),
(162, 'Sri Lanka', 'LK', 'LKA', '+94', 1),
(163, 'Sudan', 'SD', 'SDN', '+249', 1),
(164, 'Suriname', 'SR', 'SUR', '+597', 1),
(165, 'Sweden', 'SE', 'SWE', '+46', 1),
(166, 'Switzerland', 'CH', 'CHE', '+41', 1),
(167, 'Syria', 'SY', 'SYR', '+963', 1),
(168, 'Taiwan', 'TW', 'TWN', '+886', 1),
(169, 'Tajikistan', 'TJ', 'TJK', '+992', 1),
(170, 'Tanzania', 'TZ', 'TZA', '+255', 1),
(171, 'Thailand', 'TH', 'THA', '+66', 1),
(172, 'Timor-Leste', 'TL', 'TLS', '+670', 1),
(173, 'Togo', 'TG', 'TGO', '+228', 1),
(174, 'Tonga', 'TO', 'TON', '+676', 1),
(175, 'Trinidad and Tobago', 'TT', 'TTO', '+1', 1),
(176, 'Tunisia', 'TN', 'TUN', '+216', 1),
(177, 'Turkey', 'TR', 'TUR', '+90', 1),
(178, 'Turkmenistan', 'TM', 'TKM', '+993', 1),
(179, 'Tuvalu', 'TV', 'TUV', '+688', 1),
(180, 'Uganda', 'UG', 'UGA', '+256', 1),
(181, 'Ukraine', 'UA', 'UKR', '+380', 1),
(182, 'United Arab Emirates', 'AE', 'ARE', '+971', 1),
(183, 'United Kingdom', 'GB', 'GBR', '+44', 1),
(184, 'United States', 'US', 'USA', '+1', 1),
(185, 'Uruguay', 'UY', 'URY', '+598', 1),
(186, 'Uzbekistan', 'UZ', 'UZB', '+998', 1),
(187, 'Vanuatu', 'VU', 'VUT', '+678', 1),
(188, 'Vatican City', 'VA', 'VAT', '+379', 1),
(189, 'Venezuela', 'VE', 'VEN', '+58', 1),
(190, 'Vietnam', 'VN', 'VNM', '+84', 1),
(191, 'Yemen', 'YE', 'YEM', '+967', 1),
(192, 'Zambia', 'ZM', 'ZMB', '+260', 1),
(193, 'Zimbabwe', 'ZW', 'ZWE', '+263', 1);

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `mobile_number` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `designation` varchar(150) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pin_code` varchar(20) DEFAULT NULL,
  `department` varchar(150) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `organization` varchar(200) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiry`
--

INSERT INTO `enquiry` (`id`, `full_name`, `mobile_number`, `email`, `designation`, `address`, `country`, `state`, `city`, `pin_code`, `department`, `source`, `organization`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'ddd', '09654058070', 'sandeepkp23@gmail.com', NULL, NULL, 'IND', 'Tehsildar', 'Rae Bareli', NULL, NULL, NULL, NULL, NULL, '2026-01-17 23:13:15', '2026-01-17 23:13:15'),
(3, 'ddd', '09654058070', 'sandeepkp23@gmail.com', NULL, NULL, 'IND', 'Tehsildar', 'Rae Bareli', NULL, NULL, NULL, NULL, NULL, '2026-01-17 23:15:10', '2026-01-17 23:15:10');

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
-- Table structure for table `main_category`
--

CREATE TABLE `main_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0-Inactive,1-Active',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `main_category`
--

INSERT INTO `main_category` (`id`, `name`, `status`, `created_at`) VALUES
(1, 'Instruments', 1, '2026-01-04 11:50:26'),
(2, 'Consumables', 1, '2026-01-04 11:50:26'),
(3, 'Informatics', 1, '2026-01-04 11:51:15'),
(4, 'Our Services', 1, '2026-01-04 11:51:15');

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
(4, '2025_11_05_064117_create_admins_table', 2),
(5, '2026_01_17_084955_create_blogs_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `body` longtext DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `meta_title` varchar(256) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_category_id`, `title`, `slug`, `body`, `status`, `meta_title`, `meta_description`, `meta_keywords`, `description`, `published_at`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, NULL, 'sddsd', 'asdadsadsda', '<p>adddad</p>', 'draft', 'sdsd', 'sdds', 'sdds', 'ddadad', NULL, 1, 1, '2025-11-29 04:17:38', '2025-11-29 04:17:38'),
(2, NULL, 'Test 1111', 'product-category', NULL, 'draft', NULL, NULL, NULL, 'SDadaddad', NULL, 1, 1, '2026-01-04 02:34:05', '2026-01-04 02:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `page_category`
--

CREATE TABLE `page_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_category`
--

INSERT INTO `page_category` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'about-us', 'Abous Us page', NULL, NULL),
(2, 'Contact Us', 'contact-us', 'Contact Us page', NULL, NULL);

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `sub_category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(2) DEFAULT 1 COMMENT '0-Inactive,1-Active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `sub_category_id`, `title`, `slug`, `short_description`, `description`, `image`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(1, 6, 6, 'KingFisher Duo Prime', 'KingFisher-Duo-Prime', NULL, '<p><strong>KingFisher Duo Prime</strong></p><p>The Thermo Scientific KingFisher Duo Prime is the most versatile compact benchtop sample preparation instrument in the lab for 6 or 12 samples per run. It offers consistent extraction and purification of DNA, RNA, proteins, and cells, and includes an ultraviolet lamp for decontamination. The KingFisher Duo Prime is an affordable choice for academic or small labs.</p><h4>Features</h4><p>In addition to the common features of all KingFisher Purification Systems, the Duo Prime offers:</p><h4>Automated purification</h4><p>6–12 samples per run</p><h4>Choose from two plate formats</h4><p>Wide 20 μL–5 mL volume range</p><h4>Download protocols from our library</h4><p>Or easily customize protocols using ThermoScientific BindIt Software</p><h4>Easy to install and run</h4><p>Ready to run in 10 minutes or less</p><h4>Simple workflow</h4><p>Just follow the on-screen instructions</p><h4>Ultraviolet lamp</h4><p>Decontamination is standard</p><h4>Applications</h4><p>The KingFisher Duo Prime can gently and efficiently isolate and purify nucleic acids, proteins, and cells from a range of sample types for a wide variety of downstream applications. For a selection of application kits, reagents, and protocols that run on the KingFisher Duo Prime, reach out to us.</p><h4>Protocols and software</h4><p>Thermo Scientific BindIt software is included with KingFisher Duo Prime and Flex instruments. The software makes it easy to create, download, run, modify, and store protocols for your KingFisher applications.</p><p>Protocols are available for nucleic acid and protein purification procedures with reagent kits. The software allows you to define and edit steps, parameters, plates, and reagents for every part of your protocol—bind, wash, and elute.</p>', NULL, 1, '2026-01-04 05:04:25', '2026-01-21 13:57:18', 1, 1),
(2, 6, 7, 'Gulf Bio Analytical provide huge ranges of LC columns to customer', 'Gulf-Bio-Analytical-provide-huge-ranges', 'Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', NULL, 1, '2026-01-17 04:10:42', '2026-01-21 13:58:45', 1, 1),
(3, 7, 9, 'Gas Chromatography', 'Gas-Chromatography', 'The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', NULL, 1, '2026-01-18 03:27:48', '2026-01-21 13:59:19', 1, 1),
(4, 7, 10, 'Gas Chromatography', 'Gas-Chromatography-new', 'The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC', '<p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price. Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><h4>Gulf Bio Analytical provide huge</h4><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p><p>Gulf Bio Analytical provide huge ranges of LC columns to customer . The LC columns starting from Basic C-18 to complex chiral columns .Not only for daily running columns but also special application columns like bio inert and SEC . If your application is critical need some special columns which you need to custom designed we can help you to get custom column of your requirement . This custom columns will be of Any Column Any Type Any Phase, but 100% best in quality good in price.</p>', NULL, 1, '2026-01-18 03:29:07', '2026-01-21 13:59:27', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_category`
--

CREATE TABLE `product_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `main_category_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 COMMENT '1- Active, 0 - Inactive',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_category`
--

INSERT INTO `product_category` (`id`, `main_category_id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(6, NULL, 'Instruments', NULL, 'Instruments', 1, '2026-01-21 08:13:20', '2026-01-21 08:18:35', 1, 1),
(7, NULL, 'Consumables', NULL, 'Consumables', 1, '2026-01-21 08:18:51', '2026-01-21 08:18:51', 1, 1),
(8, NULL, 'Informatics', NULL, 'Informatics', 1, '2026-01-21 08:19:00', '2026-01-21 08:19:00', 1, 1),
(9, NULL, 'Our Services', NULL, 'Our Services', 1, '2026-01-21 08:19:10', '2026-01-21 08:19:10', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_image`
--

CREATE TABLE `product_image` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_image`
--

INSERT INTO `product_image` (`id`, `product_id`, `image_path`, `created_at`, `status`, `updated_at`) VALUES
(1, 4, 'uploads/product_images/GM411uBUAp5a1Kvjf9wltRoUmZQKGbj0z79rh432.jpg', '2026-01-18 08:59:07', 1, '2026-01-18 08:59:07'),
(2, 4, 'uploads/product_images/QdNKf9OZwezXGPCMp4UNCeQUtRIKBp7ImbzhHHKz.jpg', '2026-01-18 08:59:08', 1, '2026-01-18 08:59:08'),
(3, 4, 'uploads/product_images/wEoR2CnL2kBN4vjwaVDVgdMPnXPQDSmJmqwY0BlV.jpg', '2026-01-18 08:59:08', 1, '2026-01-18 08:59:08');

-- --------------------------------------------------------

--
-- Table structure for table `product_sub_category`
--

CREATE TABLE `product_sub_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_sub_category`
--

INSERT INTO `product_sub_category` (`id`, `category_id`, `name`, `slug`, `description`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(6, 6, 'GBA Analyzer', NULL, NULL, 1, '2026-01-21 08:22:36', '2026-01-21 08:22:36', 1, 1),
(7, 6, 'GBA Solutions', NULL, 'GBA Solutions', 1, '2026-01-21 08:22:53', '2026-01-21 08:22:53', 1, 1),
(8, 6, 'Chromatography', NULL, 'Chromatography', 1, '2026-01-21 08:23:09', '2026-01-21 08:23:09', 1, 1),
(9, 7, 'LC Columns', NULL, 'LC Columns', 1, '2026-01-21 08:23:23', '2026-01-21 08:23:23', 1, 1),
(10, 7, 'GC Columns', NULL, 'GC Columns', 1, '2026-01-21 08:23:40', '2026-01-21 08:23:40', 1, 1),
(11, 8, 'Chromatography Data', NULL, 'Chromatography Data', 1, '2026-01-21 08:24:00', '2026-01-21 08:24:00', 1, 1),
(12, 9, 'Onsite and repair Services', NULL, NULL, 1, '2026-01-21 08:24:25', '2026-01-21 08:24:25', 1, 1),
(13, 9, 'Compliance', NULL, 'Compliance', 1, '2026-01-21 08:24:37', '2026-01-21 08:24:37', 1, 1);

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
('AOiUcESeJGPxxR8VMtsYesxPKBAESvdUSJvmqzWE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYmdzR3hWcExDMHh0bHJRQ0ZkbFg4MnlnWnpkOXR4aDZyNUxvZEZteCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb250YWN0LXVzIjtzOjU6InJvdXRlIjtzOjk6ImNvbnRhY3RVcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1769258716);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users-new`
--

CREATE TABLE `users-new` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `admin_verify` int(11) NOT NULL DEFAULT 1 COMMENT '1=>verified,2=>not-verified',
  `user_type` int(11) NOT NULL DEFAULT 2 COMMENT '1=> admin,2=>user',
  `mobile_number` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL,
  `otp_status` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users-new`
--

INSERT INTO `users-new` (`id`, `name`, `email`, `status`, `admin_verify`, `user_type`, `mobile_number`, `email_verified_at`, `password`, `profile_image`, `otp`, `otp_status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@gmail.com', 2, 1, 1, '0', NULL, '$2y$10$U7bUbhqRKqeKpNRZnfOBHeCoMLUyprXTc1NTOfbuBCO.YMPdwPdmi', 'upload/profile_image/sUVlmXZsd5bPJlItOEmwVGrwNECYEfzFdPSlOkba.png', NULL, 0, NULL, NULL, '2024-01-17 07:11:43'),
(40, 'Sandeep', 'sandeepkp23@gmail.com', 1, 1, 1, NULL, NULL, '$2y$10$qVCnshOZdAoQNKbKlCOTUuQmN8LRXRUdb1.ew1p5Jr13WQfjksl3i', NULL, NULL, 0, NULL, '2024-03-16 07:37:41', '2024-03-16 07:37:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `main_category`
--
ALTER TABLE `main_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `page_category`
--
ALTER TABLE `page_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `product_image`
--
ALTER TABLE `product_image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sub_category`
--
ALTER TABLE `product_sub_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `users-new`
--
ALTER TABLE `users-new`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=194;

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT for table `main_category`
--
ALTER TABLE `main_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `page_category`
--
ALTER TABLE `page_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_category`
--
ALTER TABLE `product_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `product_image`
--
ALTER TABLE `product_image`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_sub_category`
--
ALTER TABLE `product_sub_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users-new`
--
ALTER TABLE `users-new`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
