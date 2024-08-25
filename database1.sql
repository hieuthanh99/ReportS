-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 23, 2024 at 10:44 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database1`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` bigint(20) UNSIGNED NOT NULL,
  `CategoryName` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CreatedBy` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CreatedDTG` timestamp NULL DEFAULT NULL,
  `UpdatedBy` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UpdatedDTG` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `CreatedBy`, `CreatedDTG`, `UpdatedBy`, `UpdatedDTG`, `created_at`, `updated_at`) VALUES
(1, 'Công nghiệp CNS', NULL, '2024-07-31 09:29:37', NULL, NULL, '2024-07-31 09:29:37', '2024-07-31 09:29:37'),
(2, 'Điều hành, kiểm tra, giám sát', NULL, '2024-07-31 09:29:42', NULL, '2024-08-05 11:25:48', '2024-07-31 09:29:42', '2024-08-05 11:25:48'),
(3, 'Số hóa các ngành kinh tế', NULL, '2024-07-31 09:29:47', NULL, '2024-08-05 11:26:00', '2024-07-31 09:29:47', '2024-08-05 11:26:00'),
(4, 'Quản trị số', NULL, '2024-08-05 11:40:22', NULL, NULL, '2024-08-05 11:40:22', '2024-08-05 11:40:22'),
(5, 'Nền tảng, ứng dụng số', NULL, '2024-08-05 11:40:34', NULL, NULL, '2024-08-05 11:40:34', '2024-08-05 11:40:34'),
(6, 'Dữ liệu số', NULL, '2024-08-05 11:40:42', NULL, NULL, '2024-08-05 11:40:42', '2024-08-05 11:40:42'),
(7, 'Hạ tầng số', NULL, '2024-08-05 11:40:50', NULL, NULL, '2024-08-05 11:40:50', '2024-08-05 11:40:50'),
(8, 'Đảm bảo an toàn thông tin', NULL, '2024-08-05 11:40:56', NULL, NULL, '2024-08-05 11:40:56', '2024-08-05 11:40:56'),
(9, 'Truyền thông và phát triển nhân lực số', NULL, '2024-08-05 11:41:03', NULL, NULL, '2024-08-05 11:41:03', '2024-08-05 11:41:03'),
(10, 'Tổng hợp', NULL, '2024-08-05 11:41:11', NULL, NULL, '2024-08-05 11:41:11', '2024-08-05 11:41:11');

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `criteria`
--

INSERT INTO `criteria` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
(8, 'CT1', 'Thúc đẩy phát triển doanh nghiệp công nghệ số hoạt động tại địa phương.', '2024-08-05 11:47:40', '2024-08-05 11:47:40'),
(9, 'CT2', 'Doanh nghiệp tại các khu công nghiệp, khu chế xuất ứng dụng các nền tảng số trong quản trị, sản xuất để thay đổi quy trình sản xuất, kinh doanh, tăng năng suất, hiệu quả hoạt động, giảm phát thải.', '2024-08-05 11:47:40', '2024-08-05 11:47:40'),
(10, 'CT3', 'Người dân trưởng thành có sử dụng dịch vụ công trực tuyến', '2024-08-05 11:47:40', '2024-08-05 11:47:40'),
(11, 'CT4', 'Hoàn thành triển khai 53 dịch vụ công thiết yếu', '2024-08-05 11:47:40', '2024-08-05 11:47:40'),
(12, 'CT5', 'Hệ thống thông tin giải quyết thủ tục hành chính của các bộ, ngành, địa phương kết nối với hệ thống giám sát, đo lường mức độ cung cấp và sử dụng dịch vụ (Hệ thống EMC).', '2024-08-05 11:47:40', '2024-08-05 11:47:40'),
(13, 'CT10', 'Hoàn thành triển khai 53 dịch vụ công thiết yếu', '2024-08-05 12:08:30', '2024-08-05 12:08:30'),
(14, 'CT11', 'Hệ thống thông tin giải quyết thủ tục hành chính của các bộ, ngành, địa phương kết nối với hệ thống giám sát, đo lường mức độ cung cấp và sử dụng dịch vụ (Hệ thống EMC).', '2024-08-05 12:08:30', '2024-08-05 12:08:30'),
(15, 'CT12', 'Hệ thống thông tin báo cáo của các bộ, ngành, địa phương kết nối với hệ thống thông tin báo cáo Chính phủ, Trung tâm thông tin, chỉ đạo điều hành của Chính phủ, Thủ tướng Chính phủ.', '2024-08-05 12:08:30', '2024-08-05 12:08:30');

-- --------------------------------------------------------

--
-- Table structure for table `criterias_task`
--

CREATE TABLE `criterias_task` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `TaskID` int(11) DEFAULT NULL,
  `CriteriaID` int(11) DEFAULT NULL,
  `CriteriaCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CriteriaName` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CreatedBy` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `UpdatedBy` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `DocumentID` int(11) DEFAULT NULL,
  `TaskCode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `RequestResult` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `progress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `progress_evaluation` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issuing_department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `document_code`, `document_name`, `issuing_department`, `creator`, `release_date`, `created_at`, `updated_at`, `category_id`) VALUES
(134, 'KHCNTT2004', 'Triển khai chương trình CPĐT', '17', '1', '2024-08-19', '2024-08-18 19:25:51', '2024-08-18 19:25:51', 1),
(135, 'TBCP009', 'Thông báo về việc sơ kết 6 tháng đầu năm', '1', '1', '2024-08-19', '2024-08-18 21:32:11', '2024-08-18 21:32:11', 1),
(136, '34-2022-TT-BTTTT', 'Thông tư hướng dẫn dịch vụ trực tuyến', '14', '1', '2022-02-01', '2024-08-18 22:26:20', '2024-08-18 22:26:20', 2),
(137, 'DVK', 'Kế hoạch lấy vk của Khải', '1', '1', '2024-08-22', '2024-08-22 04:23:43', '2024-08-22 04:23:43', 1),
(138, 'VB1231232', 'văn bản test', '2', '5', '2024-08-13', '2024-08-22 12:31:34', '2024-08-22 12:31:34', 2);

-- --------------------------------------------------------

--
-- Table structure for table `document_categories`
--

CREATE TABLE `document_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `document_categories`
--

INSERT INTO `document_categories` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'L1', 'Loại Văn Bản 2', 'Loại Văn Bản 1', '2024-08-15 01:48:48', '2024-08-15 04:13:05'),
(2, 'TT', 'Thông tư', 'Các văn bản thông tư', '2024-08-18 22:24:39', '2024-08-18 22:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `number_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cycle_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `document_id`, `file_name`, `type`, `file_path`, `created_at`, `updated_at`, `number_type`, `cycle_type`) VALUES
(175, 81, '1723192067_du lieu Input.xlsx', '1', 'tasks/1723192067_du lieu Input.xlsx', '2024-08-09 01:27:47', '2024-08-09 01:27:47', NULL, NULL),
(176, 81, '1723192396_du lieu Input.xlsx', '1', 'tasks/1723192396_du lieu Input.xlsx', '2024-08-09 01:33:16', '2024-08-09 01:33:16', NULL, NULL),
(177, 83, '1723192795_1722785743_1_002_K24TDD_471_8507 (1).pdf', '1', 'tasks/1723192795_1722785743_1_002_K24TDD_471_8507 (1).pdf', '2024-08-09 01:39:55', '2024-08-09 01:39:55', NULL, NULL),
(178, 85, '1723192855_1_002_K24TDD_472_8507.pdf', '1', 'tasks/1723192855_1_002_K24TDD_472_8507.pdf', '2024-08-09 01:40:55', '2024-08-09 01:40:55', NULL, NULL),
(183, 91, '1723199092_du lieu Input.xlsx', '1', 'tasks/1723199092_du lieu Input.xlsx', '2024-08-09 03:24:53', '2024-08-09 03:24:53', NULL, NULL),
(202, 132, '1723286946_Hướng dẫn sử dụng SSL VPN cho Android 23.pdf', '3', 'documents/1723286946_Hướng dẫn sử dụng SSL VPN cho Android 23.pdf', '2024-08-10 03:49:06', '2024-08-10 03:49:06', NULL, NULL),
(203, 132, '1723286946_danh muc.xlsx', '3', 'documents/1723286946_danh muc.xlsx', '2024-08-10 03:49:06', '2024-08-10 03:49:06', NULL, NULL),
(204, 132, '1723286946_A010000000475466 - Copy.xls', '3', 'documents/1723286946_A010000000475466 - Copy.xls', '2024-08-10 03:49:06', '2024-08-10 03:49:06', NULL, NULL),
(205, 155, '1723287203_Hướng dẫn sử dụng SSL VPN Cho Máy Tính 40.pdf', '1', 'tasks/1723287203_Hướng dẫn sử dụng SSL VPN Cho Máy Tính 40.pdf', '2024-08-10 03:53:23', '2024-08-10 03:53:23', NULL, NULL),
(206, 158, '1723287208_1722785743_1_002_K24TDD_471_8507 (1).pdf', '1', 'tasks/1723287208_1722785743_1_002_K24TDD_471_8507 (1).pdf', '2024-08-10 03:53:28', '2024-08-10 03:53:28', NULL, NULL),
(207, 161, '1723287213_MINV-XB-E35-0320443394.pdf', '1', 'tasks/1723287213_MINV-XB-E35-0320443394.pdf', '2024-08-10 03:53:33', '2024-08-10 03:53:33', NULL, NULL),
(208, 161, '1723287235_Hướng dẫn sử dụng SSL VPN Cho Máy Tính 40.pdf', '1', 'tasks/1723287235_Hướng dẫn sử dụng SSL VPN Cho Máy Tính 40.pdf', '2024-08-10 03:53:55', '2024-08-10 03:53:55', NULL, NULL),
(209, 161, '1723287382_1722832340_Hướng dẫn sử dụng SSL VPN cho Android 23.pdf', '1', 'tasks/1723287382_1722832340_Hướng dẫn sử dụng SSL VPN cho Android 23.pdf', '2024-08-10 03:56:22', '2024-08-10 03:56:22', NULL, NULL),
(211, 134, '1724034351_1723005729_AHT_JP_Invoice_20240119.pdf', '3', 'documents/1724034351_1723005729_AHT_JP_Invoice_20240119.pdf', '2024-08-18 19:25:51', '2024-08-18 19:25:51', NULL, NULL),
(212, 134, '1724034351_1722915286_DataLoad_20240719091317.xlsx', '3', 'documents/1724034351_1722915286_DataLoad_20240719091317.xlsx', '2024-08-18 19:25:51', '2024-08-18 19:25:51', NULL, NULL),
(213, 187, '1724041196_SRS.pdf', '1', 'tasks/1724041196_SRS.pdf', '2024-08-18 21:19:56', '2024-08-18 21:19:56', NULL, NULL),
(214, 135, '1724041931_VM0_BBNT_2023011.docx', '3', 'documents/1724041931_VM0_BBNT_2023011.docx', '2024-08-18 21:32:11', '2024-08-18 21:32:11', NULL, NULL),
(215, 135, '1724041931_Trung_tâm_TI_v2.1.pdf', '3', 'documents/1724041931_Trung_tâm_TI_v2.1.pdf', '2024-08-18 21:32:11', '2024-08-18 21:32:11', NULL, NULL),
(216, 192, '1724042135_Nodejs Fullstack Developer.pdf', '1', 'tasks/1724042135_Nodejs Fullstack Developer.pdf', '2024-08-18 21:35:35', '2024-08-18 21:35:35', NULL, NULL),
(217, 136, '1724045208_1723004717_[VNA] Quản lý chi phí Điều hành bay.pdf', '3', 'documents/1724045208_1723004717_[VNA] Quản lý chi phí Điều hành bay.pdf', '2024-08-18 22:26:48', '2024-08-18 22:26:48', NULL, NULL),
(218, 197, '1724045981_Lab1.1.ipynb', '1', 'tasks/1724045981_Lab1.1.ipynb', '2024-08-18 22:39:41', '2024-08-18 22:39:41', NULL, NULL),
(219, 197, '1724046035_du lieu Input.xlsx', '1', 'tasks/1724046035_du lieu Input.xlsx', '2024-08-18 22:40:35', '2024-08-18 22:40:35', NULL, NULL),
(220, 201, '1724084402_pdf-test.pdf', '1', 'tasks/1724084402_pdf-test.pdf', '2024-08-19 09:20:03', '2024-08-19 09:20:03', '34', '1'),
(221, 200, '1724126776_review - sua.docx', '1', 'tasks/1724126776_review - sua.docx', '2024-08-19 21:06:17', '2024-08-19 21:06:17', '34', '1'),
(222, 137, '1724325823_Lab2.py', '3', 'documents/1724325823_Lab2.py', '2024-08-22 04:23:44', '2024-08-22 04:23:44', NULL, NULL),
(223, 219, '1724357377_DHB.pdf', '1', 'tasks/1724357377_DHB.pdf', '2024-08-22 13:09:38', '2024-08-22 13:09:38', '34', '1');

-- --------------------------------------------------------

--
-- Table structure for table `history_change_document`
--

CREATE TABLE `history_change_document` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mapping_id` bigint(20) UNSIGNED NOT NULL,
  `type_save` int(11) DEFAULT NULL,
  `result` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_cycle` int(11) DEFAULT NULL,
  `type_cycle` int(11) DEFAULT NULL,
  `update_date` timestamp NULL DEFAULT NULL,
  `update_user` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `history_change_document`
--

INSERT INTO `history_change_document` (`id`, `mapping_id`, `type_save`, `result`, `description`, `number_cycle`, `type_cycle`, `update_date`, `update_user`, `created_at`, `updated_at`) VALUES
(1, 213, 1, 'ok la 11', 'ok la 1', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:18', '2024-08-05 03:50:18'),
(2, 447, 2, 'ok la 1', 'ok la 1', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:18', '2024-08-05 03:50:18'),
(3, 448, 2, 'ok laok laok la 1', 'ok la 1', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:18', '2024-08-05 03:50:18'),
(4, 449, 2, 'ok la 1', 'ok la 1', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:18', '2024-08-05 03:50:18'),
(5, 450, 2, 'ok la 1', 'ok la 1', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:18', '2024-08-05 03:50:18'),
(6, 451, 2, 'ok la 1', 'ok la 1', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:18', '2024-08-05 03:50:18'),
(7, 213, 1, 'ok la 2', 'ok la 2', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:55', '2024-08-05 03:50:55'),
(8, 447, 2, 'ok la 2', 'ok la 2', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:55', '2024-08-05 03:50:55'),
(9, 448, 2, 'ok laok laok la2', 'ok la 2', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:55', '2024-08-05 03:50:55'),
(10, 449, 2, 'ok la 2', 'ok la 2', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:55', '2024-08-05 03:50:55'),
(11, 450, 2, 'ok la 2', 'ok la 2', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:55', '2024-08-05 03:50:55'),
(12, 451, 2, 'ok la2', 'ok la 2', 32, 1, '2024-08-04 17:00:00', 3, '2024-08-05 03:50:55', '2024-08-05 03:50:55'),
(13, 91, 1, '2ưq', 'ewqe', 8, 2, '2024-08-09 03:44:35', 4, '2024-08-09 03:44:35', '2024-08-09 03:44:35'),
(14, 116, 1, '2', 'sa', 8, 2, '2024-08-09 03:44:35', 4, '2024-08-09 03:44:35', '2024-08-09 03:44:35'),
(15, 91, 1, '2ưq123213', 'ewqe123122', 8, 2, '2024-08-09 04:01:49', 4, '2024-08-09 04:01:49', '2024-08-09 04:01:49'),
(16, 116, 1, '21', 'sa123213', 8, 2, '2024-08-09 04:01:49', 4, '2024-08-09 04:01:49', '2024-08-09 04:01:49'),
(17, 155, 1, 'ok tốt', 'làm tốt', 32, 1, '2024-08-10 03:53:40', 3, '2024-08-10 03:53:40', '2024-08-10 03:53:40'),
(18, 158, 1, 'tuyệt', 'tốt', 32, 1, '2024-08-10 03:53:40', 3, '2024-08-10 03:53:40', '2024-08-10 03:53:40'),
(19, 161, 1, '10', 'tốt', 3, 3, '2024-08-10 03:53:40', 3, '2024-08-10 03:53:40', '2024-08-10 03:53:40'),
(20, 155, 1, 'ok tốt', 'làm tốt', 32, 1, '2024-08-10 03:54:00', 3, '2024-08-10 03:54:00', '2024-08-10 03:54:00'),
(21, 158, 1, 'tuyệt', 'tốt', 32, 1, '2024-08-10 03:54:00', 3, '2024-08-10 03:54:00', '2024-08-10 03:54:00'),
(22, 161, 1, '10', 'tốt', 3, 3, '2024-08-10 03:54:00', 3, '2024-08-10 03:54:00', '2024-08-10 03:54:00'),
(23, 155, 1, 'ok tốt', 'làm tốt', 32, 1, '2024-08-10 03:56:24', 3, '2024-08-10 03:56:24', '2024-08-10 03:56:24'),
(24, 158, 1, 'tuyệt', 'tốt', 32, 1, '2024-08-10 03:56:24', 3, '2024-08-10 03:56:24', '2024-08-10 03:56:24'),
(25, 161, 1, '10', 'tốt', 3, 3, '2024-08-10 03:56:24', 3, '2024-08-10 03:56:24', '2024-08-10 03:56:24'),
(26, 155, 1, 'ok tốt', 'làm tốt', 32, 1, '2024-08-10 03:58:00', 3, '2024-08-10 03:58:00', '2024-08-10 03:58:00'),
(27, 158, 1, 'xong', 'tốt', 32, 1, '2024-08-10 03:58:00', 3, '2024-08-10 03:58:00', '2024-08-10 03:58:00'),
(28, 161, 1, '10', 'tốt', 3, 3, '2024-08-10 03:58:00', 3, '2024-08-10 03:58:00', '2024-08-10 03:58:00'),
(29, 81, 1, '5', '', 8, 2, '2024-08-18 12:55:51', 3, '2024-08-18 12:55:51', '2024-08-18 12:55:51'),
(30, 83, 1, '6', '', 8, 2, '2024-08-18 12:55:51', 3, '2024-08-18 12:55:51', '2024-08-18 12:55:51'),
(31, 85, 1, '7', '', 8, 2, '2024-08-18 12:55:51', 3, '2024-08-18 12:55:51', '2024-08-18 12:55:51'),
(32, 87, 1, '8', '', 8, 2, '2024-08-18 12:55:51', 3, '2024-08-18 12:55:51', '2024-08-18 12:55:51'),
(33, 180, 1, 'đnag thực hiện', 'ok', 33, 1, '2024-08-18 13:08:11', 3, '2024-08-18 13:08:11', '2024-08-18 13:08:11'),
(34, 181, 1, 'pos', 'ok', 33, 1, '2024-08-18 13:16:10', 9, '2024-08-18 13:16:10', '2024-08-18 13:16:10'),
(35, 186, 1, '', 'ok', 3, 3, '2024-08-18 21:18:14', 1, '2024-08-18 21:18:14', '2024-08-18 21:18:14'),
(36, 187, 1, '', 'ok', 3, 3, '2024-08-18 21:18:14', 1, '2024-08-18 21:18:14', '2024-08-18 21:18:14'),
(37, 188, 1, '', 'ok', 3, 3, '2024-08-18 21:18:14', 1, '2024-08-18 21:18:14', '2024-08-18 21:18:14'),
(38, 187, 1, '12', 'ok', 3, 3, '2024-08-18 21:19:58', 3, '2024-08-18 21:19:58', '2024-08-18 21:19:58'),
(39, 192, 1, 'Đang thực hiện thống kê. Kết quả đạt 50%', 'ok', 34, 1, '2024-08-18 21:35:36', 3, '2024-08-18 21:35:36', '2024-08-18 21:35:36'),
(40, 192, 1, 'Đã báo cáo bộ trưởng và các cơ quan liên quan.', 'ok', 34, 1, '2024-08-18 21:37:36', 3, '2024-08-18 21:37:36', '2024-08-18 21:37:36'),
(41, 197, 1, 'Đã trình báo cáo cho thủ trưởng và các đơn vị liên quan', 'ok', 8, 2, '2024-08-18 22:40:47', 3, '2024-08-18 22:40:47', '2024-08-18 22:40:47'),
(42, 197, 1, 'ok đã làm xong', 'ok', 8, 2, '2024-08-19 03:14:25', 3, '2024-08-19 03:14:25', '2024-08-19 03:14:25'),
(43, 197, 1, 'ok đã xong', 'ok', 8, 2, '2024-08-19 03:16:59', 3, '2024-08-19 03:16:59', '2024-08-19 03:16:59'),
(44, 199, 1, 'Hoàn thành', 'ok', 8, 2, '2024-08-19 03:31:01', 3, '2024-08-19 03:31:01', '2024-08-19 03:31:01'),
(45, 187, 1, '13', 'ok', 3, 3, '2024-08-19 03:31:01', 3, '2024-08-19 03:31:01', '2024-08-19 03:31:01'),
(46, 200, 1, 'ok hoàn thành', 'ok', 34, 1, '2024-08-19 03:51:55', 3, '2024-08-19 03:51:55', '2024-08-19 03:51:55'),
(47, 200, 1, 'ok đã xong', 'ok', 34, 1, '2024-08-19 04:15:28', 3, '2024-08-19 04:15:28', '2024-08-19 04:15:28'),
(48, 200, 1, 'ok xong tuần 34', 'ok', 34, 1, '2024-08-19 04:22:02', 3, '2024-08-19 04:22:02', '2024-08-19 04:22:02'),
(49, 201, 1, 'ok dc r', 'ok', 34, 1, '2024-08-19 09:20:13', 9, '2024-08-19 09:20:13', '2024-08-19 09:20:13'),
(50, 200, 1, 'ok xong tuần 34', 'ok', 34, 1, '2024-08-19 21:06:27', 5, '2024-08-19 21:06:27', '2024-08-19 21:06:27'),
(51, 201, 1, 'ok dc r', 'ok', 34, 1, '2024-08-19 21:06:27', 5, '2024-08-19 21:06:27', '2024-08-19 21:06:27'),
(52, 212, 1, '2', 'ok', 34, 1, '2024-08-22 04:25:18', 3, '2024-08-22 04:25:18', '2024-08-22 04:25:18'),
(53, 212, 1, '5', 'ok', 34, 1, '2024-08-22 04:25:36', 3, '2024-08-22 04:25:36', '2024-08-22 04:25:36'),
(54, 199, 1, 'sadsad', 'ok', 8, 2, '2024-08-22 12:18:57', 3, '2024-08-22 12:18:57', '2024-08-22 12:18:57'),
(55, 187, 1, '13', 'ok', 3, 3, '2024-08-22 12:18:57', 3, '2024-08-22 12:18:57', '2024-08-22 12:18:57'),
(56, 216, 1, '100', 'ok', 34, 1, '2024-08-22 12:32:24', 3, '2024-08-22 12:32:24', '2024-08-22 12:32:24'),
(57, 216, 1, '100', 'ok', 34, 1, '2024-08-22 12:32:52', 10, '2024-08-22 12:32:52', '2024-08-22 12:32:52'),
(58, 219, 1, 'ok đã hoàn thành', 'ok', 34, 1, '2024-08-22 13:09:41', 3, '2024-08-22 13:09:41', '2024-08-22 13:09:41'),
(59, 219, 1, 'ok đã hoàn thành', 'ok', 34, 1, '2024-08-22 13:12:34', 10, '2024-08-22 13:12:34', '2024-08-22 13:12:34'),
(60, 221, 1, 'ok đã xong', 'ok', 3, 3, '2024-08-23 00:34:19', 3, '2024-08-23 00:34:19', '2024-08-23 00:34:19'),
(61, 221, 1, 'ok đã xong', 'ok', 3, 3, '2024-08-23 00:34:54', 10, '2024-08-23 00:34:54', '2024-08-23 00:34:54');

-- --------------------------------------------------------

--
-- Table structure for table `indicator_groups`
--

CREATE TABLE `indicator_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `indicator_groups`
--

INSERT INTO `indicator_groups` (`id`, `code`, `name`, `description`, `creator_id`, `created_at`, `updated_at`) VALUES
(2, 'NCT02', 'Các chỉ tiêu về ứng dụng CNTT', 'Chỉ tiêu về số lượng dịch vụ công', 1, '2024-08-18 19:27:52', '2024-08-18 21:09:08'),
(4, 'NCT01', 'Các chỉ tiêu về phát triển kinh tế', 'Các chỉ tiêu về phát triển kinh tế', 1, '2024-08-18 21:08:42', '2024-08-18 21:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `metrics`
--

CREATE TABLE `metrics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `metric_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required_result` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(19, '2014_10_12_000000_create_users_table', 1),
(20, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(21, '2019_08_19_000000_create_failed_jobs_table', 1),
(22, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(23, '2024_07_29_153753_create_documents_table', 1),
(24, '2024_07_29_153758_create_tasks_table', 1),
(25, '2024_07_29_153807_create_metrics_table', 1),
(26, '2024_07_29_155712_create_organizations_table', 1),
(27, '2024_07_29_164001_add_release_date_to_documents_table', 1),
(28, '2024_07_29_170823_create_categories_table', 1),
(29, '2024_07_29_184625_add_organization_id_to_users_table', 1),
(30, '2024_07_30_062149_drop_document_id_from_tasks_table', 1),
(31, '2024_07_30_080844_create_tasks_document_table', 1),
(32, '2024_07_30_081501_create_files_table', 1),
(33, '2024_07_30_083156_remove_start_date_and_end_date_from_documents_table', 1),
(34, '2024_07_30_085357_create_criteria_table', 1),
(35, '2024_07_30_131022_create_criterias_task_table', 1),
(36, '2024_07_30_182810_create_organization_task_table', 1),
(37, '2024_08_01_020839_add_employee_code_phone_address_to_users_table', 2),
(38, '2024_08_01_175303_create_task_result_table', 3),
(39, '2024_08_01_175846_add_progress_and_evaluation_to_tasks_document', 3),
(40, '2024_08_01_180106_remove_taskdocument_id_from_task_results', 4),
(41, '2024_08_01_180525_add_number_type_and_type_to_task_results', 5),
(42, '2024_08_02_060455_add_type_save_to_task_result_table', 6),
(43, '2024_08_02_060559_add_progress_and_progress_evaluation_to_criterias_task_table', 6),
(44, '2024_08_03_074904_create_permission_tables', 7),
(45, '2024_08_04_031005_add_role_to_users_table', 8),
(46, '2024_08_04_062727_add_type_to_files_table', 9),
(47, '2024_08_04_064532_update_document_id_in_files_table', 10),
(48, '2024_08_04_080713_remove_tasks_document_id_from_task_result', 11),
(49, '2024_08_04_080907_add_id_task_criterias_to_task_result', 12),
(50, '2024_08_03_082353_add_fieldname_to_tasks_table', 13),
(51, '2024_08_04_164632_remove_organization_id_from_tasks_table', 13),
(52, '2024_08_05_095746_create_history_change_document_table', 14),
(53, '2024_08_05_152905_change_update_date_column_in_history_change_document_table', 15),
(54, '2024_08_09_092332_add_is_completed_to_task_target_table', 16),
(55, '2024_08_11_081435_create_task_approval_history_table', 17),
(56, '2024_08_11_192227_drop_task_approval_history_table', 17),
(57, '2024_08_11_192443_create_task_approval_history_table', 17),
(58, '2024_08_15_075904_create_document_categories_table', 17),
(59, '2024_08_15_102547_create_organization_types_table', 18),
(60, '2024_08_15_104709_update_organization_types_table', 19),
(61, '2024_08_15_111925_create_positions_table', 20),
(62, '2024_08_15_112850_add_details_to_users_table', 21),
(63, '2024_08_15_153712_update_organizations_table', 22),
(64, '2024_08_15_163432_add_organization_type_id_to_organizations_table', 23),
(65, '2024_08_15_165604_create_task_groups_table', 24),
(66, '2024_08_15_171418_create_indicator_groups_table', 25),
(67, '2024_08_16_062723_add_category_id_to_documents_table', 26),
(68, '2024_08_16_071312_add_category_id_1_to_documents_table', 27),
(69, '2024_08_16_071332_add_category_id_1_to_documents_table', 27),
(70, '2024_08_16_073025_remove_specialist_columns_from_organizations_table', 28),
(71, '2024_08_19_034013_add_type_id_to_task_target_table', 29),
(72, '2024_08_19_152557_add_columns_to_files_table', 30);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('tỉnh','bộ') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_type_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `code`, `name`, `type`, `email`, `phone`, `parent_id`, `creator`, `created_at`, `updated_at`, `address`, `website`, `organization_type_id`) VALUES
(1, 'CP', 'Chính phủ', 'tỉnh', 'chinhphu@gmail.com', '123456789', NULL, '1', '2024-08-05 02:47:01', '2024-08-18 19:59:32', 'hải bối', 'thanhnh.vn', 1),
(2, 'BQP', 'Bộ Quốc phòng', 'bộ', 'email1@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-18 19:59:41', 'abc.com', 'sss', 1),
(3, 'BCA', 'Bộ Công an', 'bộ', 'email2@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, 1),
(4, 'BNG', 'Bộ Ngoại giao', 'bộ', 'email3@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(5, 'BNV', 'Bộ Nội vụ', 'bộ', 'email4@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(6, 'BTP', 'Bộ Tư pháp', 'bộ', 'email5@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(7, 'BKHDT', 'Bộ Kế hoạch và Đầu tư', 'bộ', 'email6@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(8, 'BTC', 'Bộ Tài chính', 'bộ', 'email7@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(9, 'BCT', 'Bộ Công thương', 'bộ', 'email8@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(10, 'NNVPT', 'Bộ Nông nghiệp và Phát triển nông thôn', 'bộ', 'email9@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(11, 'BGTVT', 'Bộ Giao thông vận tải', 'bộ', 'email10@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(12, 'BXD', 'Bộ Xây dựng', 'bộ', 'email11@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(13, 'TNVMT', 'Bộ Tài nguyên và Môi trường', 'bộ', 'email12@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(14, 'TTVTT', 'Bộ Thông tin và Truyền thông', 'bộ', 'email13@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(15, 'LDVXH', 'Bộ Lao động - Thương binh và Xã hội', 'bộ', 'email14@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(16, 'VHTTD', 'Bộ Văn hóa, Thể thao và Du lịch', 'bộ', 'email15@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(17, 'KHVCN', 'Bộ Khoa học và Công nghệ', 'bộ', 'email16@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(18, 'GDVDT', 'Bộ Giáo dục và Đào tạo', 'bộ', 'email17@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(19, 'BYT', 'Bộ Y tế', 'bộ', 'email18@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(20, 'UBDT', 'Ủy ban Dân tộc', 'bộ', 'email19@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(21, 'NHNHV', 'Ngân hàng Nhà nước Việt Nam', 'bộ', 'email20@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(22, 'TTCP', 'Thanh tra Chính phủ', 'bộ', 'email21@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(23, 'VPCP', 'Văn phòng Chính phủ', 'bộ', 'email22@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(24, 'DTNVN', 'Đài Tiếng nói Việt Nam', 'bộ', 'email23@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(25, 'QLLCT', 'Ban Quản lý Lăng Chủ tịch Hồ Chí Minh', 'bộ', 'email24@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(26, 'BH', 'Bảo hiểm Xã hội Việt Nam', 'bộ', 'email25@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(27, 'TT', 'Thông tấn xã Việt Nam', 'bộ', 'email26@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(28, 'DTHVN', 'Đài Truyền hình Việt Nam', 'bộ', 'email27@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(29, 'VH', 'Viện Hàn lâm Khoa học và Công nghệ Việt Nam', 'bộ', 'email28@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(30, 'HLKHV', 'Viện Hàn lâm Khoa học Xã hội Việt Nam', 'bộ', 'email29@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(31, 'UV', 'Ủy ban quản lý vốn nhà nước tại doanh nghiệp', 'bộ', 'email30@example.com', '1234567890', 1, '1', '2024-08-05 02:54:20', '2024-08-05 02:54:20', NULL, NULL, NULL),
(32, 'AG', 'An Giang', 'tỉnh', 'email32@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(33, 'BRVT', 'Bà Rịa-Vũng Tàu', 'tỉnh', 'email33@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(34, 'BL', 'Bạc Liêu', 'tỉnh', 'email34@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(35, 'BK', 'Bắc Kạn', 'tỉnh', 'email35@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(36, 'BG', 'Bắc Giang', 'tỉnh', 'email36@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(37, 'BN', 'Bắc Ninh', 'tỉnh', 'email37@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(38, 'TBT', 'Bến Tre', 'tỉnh', 'email38@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(39, 'TBD', 'Bình Dương', 'tỉnh', 'email39@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(40, 'BD', 'Bình Định', 'tỉnh', 'email40@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(41, 'BP', 'Bình Phước', 'tỉnh', 'email41@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(42, 'BT', 'Bình Thuận', 'tỉnh', 'email42@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(43, 'CM', 'Cà Mau', 'tỉnh', 'email43@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(44, 'CB', 'Cao Bằng', 'tỉnh', 'email44@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(45, 'CT', 'Thành phố Cần Thơ', 'tỉnh', 'email45@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(46, 'TPDN', 'Thành phố Đà Nẵng', 'tỉnh', 'email46@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(47, 'DL', 'Đắk Lắk', 'tỉnh', 'email47@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(48, 'DN', 'Đắk Nông', 'tỉnh', 'email48@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(49, 'DB', 'Điện Biên', 'tỉnh', 'email49@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(50, 'TDN', 'Đồng Nai', 'tỉnh', 'email50@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(51, 'DT', 'Đồng Tháp', 'tỉnh', 'email51@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(52, 'GL', 'Gia Lai', 'tỉnh', 'email52@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(53, 'THG', 'Hà Giang', 'tỉnh', 'email53@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(54, 'HG', 'Hậu Giang', 'tỉnh', 'email54@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(55, 'HN', 'Hà Nam', 'tỉnh', 'email55@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(56, 'TPHN', 'Thành phố Hà Nội', 'tỉnh', 'email56@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(57, 'HT', 'Hà Tĩnh', 'tỉnh', 'email57@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(58, 'HD', 'Hải Dương', 'tỉnh', 'email58@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(59, 'HP', 'Thành phố Hải Phòng', 'tỉnh', 'email59@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(60, 'HB', 'Hòa Bình', 'tỉnh', 'email60@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(61, 'HCM', 'Thành phố Hồ Chí Minh', 'tỉnh', 'email61@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(62, 'HY', 'Hưng Yên', 'tỉnh', 'email62@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(63, 'KH', 'Khánh Hòa', 'tỉnh', 'email63@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(64, 'KG', 'Kiên Giang', 'tỉnh', 'email64@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(65, 'KT', 'Kon Tum', 'tỉnh', 'email65@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(66, 'LC', 'Lai Châu', 'tỉnh', 'email66@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(67, 'TLC', 'Lào Cai', 'tỉnh', 'email67@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(68, 'LS', 'Lạng Sơn', 'tỉnh', 'email68@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(69, 'LD', 'Lâm Đồng', 'tỉnh', 'email69@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(70, 'LA', 'Long An', 'tỉnh', 'email70@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(71, 'ND', 'Nam Định', 'tỉnh', 'email71@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(72, 'NA', 'Nghệ An', 'tỉnh', 'email72@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(73, 'NB', 'Ninh Bình', 'tỉnh', 'email73@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(74, 'NT', 'Ninh Thuận', 'tỉnh', 'email74@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(75, 'PT', 'Phú Thọ', 'tỉnh', 'email75@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(76, 'PY', 'Phú Yên', 'tỉnh', 'email76@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(77, 'QB', 'Quảng Bình', 'tỉnh', 'email77@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(78, 'TQN', 'Quảng Nam', 'tỉnh', 'email78@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(79, 'QN', 'Quảng Ngãi', 'tỉnh', 'email79@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(80, 'TPQN', 'Quảng Ninh', 'tỉnh', 'email80@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(81, 'QT', 'Quảng Trị', 'tỉnh', 'email81@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(82, 'ST', 'Sóc Trăng', 'tỉnh', 'email82@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(83, 'SL', 'Sơn La', 'tỉnh', 'email83@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(84, 'TN', 'Tây Ninh', 'tỉnh', 'email84@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(85, 'TB', 'Thái Bình', 'tỉnh', 'email85@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(86, 'TTN', 'Thái Nguyên', 'tỉnh', 'email86@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(87, 'TH', 'Thanh Hóa', 'tỉnh', 'email87@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(88, 'TTH', 'Thừa Thiên Huế', 'tỉnh', 'email88@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(89, 'TG', 'Tiền Giang', 'tỉnh', 'email89@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(90, 'TV', 'Trà Vinh', 'tỉnh', 'email90@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(91, 'TQ', 'Tuyên Quang', 'tỉnh', 'email91@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(92, 'VL', 'Vĩnh Long', 'tỉnh', 'email92@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(93, 'VP', 'Vĩnh Phúc', 'tỉnh', 'email93@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(94, 'YB', 'Yên Bái', 'tỉnh', 'email94@example.com', '1234567890', 1, '1', '2024-08-05 03:01:35', '2024-08-05 03:01:35', NULL, NULL, NULL),
(101, 'BCHTW', 'Ban Chấp Hành Trung Ương', 'bộ', NULL, NULL, NULL, '1', '2024-08-15 23:35:38', '2024-08-15 23:35:38', NULL, NULL, NULL),
(102, 'CQ1s', 'Cơ quan test', 'tỉnh', 'kak@gmail.com', '0355668062', 1, '1', '2024-08-16 19:20:45', '2024-08-16 19:20:45', 'Ha Noi', 'abc.com', NULL),
(103, 'CQ22', 'Test', 'tỉnh', 'Test@gmail.com', '0355668062', 1, '1', '2024-08-16 19:23:37', '2024-08-16 19:23:37', 'Ha Noi', 'sss.com', NULL),
(104, 'wsd', 'dev dev', 'tỉnh', 'kak@gmail.com', '0355668062', NULL, '1', '2024-08-16 20:14:35', '2024-08-16 20:14:35', 'Ha Noi', 'sadsad', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `organization_task`
--

CREATE TABLE `organization_task` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tasks_document_id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED NOT NULL,
  `creator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `users_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_types`
--

CREATE TABLE `organization_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_types`
--

INSERT INTO `organization_types` (`id`, `code`, `type_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'BCT', 'Bộ Chính trị', 'Bộ Chính trị', '2024-08-15 03:54:17', '2024-08-18 20:07:36'),
(2, 'BCHTW', 'Ban chấp hành TW', 'Ban chấp hành TW', '2024-08-15 03:54:27', '2024-08-18 20:06:35'),
(3, 'BBTHƯ', 'Ban Bí thư', 'Ban Bí thư', '2024-08-18 20:11:42', '2024-08-18 20:11:42'),
(4, 'CP', 'Chính Phủ', 'Chính Phủ', '2024-08-18 20:12:31', '2024-08-18 20:12:31'),
(5, 'TTg', 'Thủ tưởng Chính phủ', 'Thủ tưởng Chính phủ', '2024-08-18 20:12:55', '2024-08-18 20:12:55'),
(6, 'BCSCQTW', 'Ban Cán sự Đảng CQTW', 'Ban Cán sự Đảng CQTW', '2024-08-18 20:13:16', '2024-08-18 20:13:16'),
(7, 'Bo', 'Bộ', 'Bộ', '2024-08-18 20:13:30', '2024-08-18 20:13:30'),
(8, 'CQNB', 'Cơ quan ngang bộ', 'Cơ quan ngang bộ', '2024-08-18 20:13:46', '2024-08-18 20:13:46'),
(9, 'CQCP', 'Cơ quan thuộc Chính Phủ', 'Cơ quan thuộc Chính Phủ', '2024-08-18 20:14:09', '2024-08-18 20:14:09'),
(10, 'TU', 'Thành ủy/Tỉnh ủy', 'Thành ủy/Tỉnh ủy', '2024-08-18 20:14:29', '2024-08-18 20:14:29'),
(11, 'HDND', 'Hội đồng nhân dân Thành phố, Tỉnh', 'Hội đồng nhân dân Thành phố, Tỉnh', '2024-08-18 20:15:20', '2024-08-18 20:15:20'),
(12, 'UBND', 'Ủy ban nhân dân các tỉnh, thành phố trực thuộc Trung ưng', 'Ủy ban nhân dân các tỉnh, thành phố trực thuộc Trung ưng', '2024-08-18 20:16:49', '2024-08-18 20:16:49');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `code`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'CT', 'Cục trưởng', 'Cục trưởng', '2024-08-15 04:28:02', '2024-08-15 04:28:02'),
(2, 'BT', 'Bộ Trưởng', 'Bộ Trưởng các bộ', '2024-08-18 22:23:33', '2024-08-18 22:23:33');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reporting_cycle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required_result` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `creator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_code`, `task_name`, `reporting_cycle`, `category`, `required_result`, `start_date`, `end_date`, `creator`, `created_at`, `updated_at`) VALUES
(18, 'DV1', 'Tổ chức các phiên họp, hội nghị của Ủy ban', '2', '2', 'Các phiên họp, hội nghị được tổ chức', '2024-04-19', '2024-12-31', 'admin', '2024-08-05 11:47:40', '2024-08-05 11:47:40'),
(19, 'DV2', 'Xây dựng Kế hoạch kiểm tra, giám sát tình hình thực hiện các nhiệm vụ chuyển đổi số tại bộ, ngành, địa phương', '2', '2', 'Kế hoạch kiểm tra, giám sát của BCĐ chuyển đổi số trong phạm vi ngành, lĩnh vực của bộ, ngành, địa phương được ban hành. Yêu cầu có thời gian, nội dung, địa điểm kiểm tra, giám sát cụ thể', '2024-04-19', '2024-04-30', 'admin', '2024-08-05 11:47:40', '2024-08-05 11:47:40'),
(20, 'DV3', 'Tổ chức thực hiện kiểm tra, giám sát theo Kế hoạch đã được ban hành', '2', '2', 'Các đoàn kiểm tra, giám sát được tổ chức thực hiện', '2024-07-31', '2024-08-23', 'admin', '2024-08-05 11:47:40', '2024-08-05 11:47:40'),
(21, 'DV4', 'Xây dựng cơ chế, công cụ đo lường, giám sát việc triển khai Kế hoạch hoạt động của Ủy ban Quốc gia về chuyển đổi số', '2', '2', 'Công cụ quản lý, đo lường các nhiệm vụ ', '2024-04-19', '2024-04-30', 'admin', '2024-08-05 11:47:40', '2024-08-05 11:47:40'),
(22, 'DV10', 'Phối hợp một địa phương, triển khai thí điểm số hóa các ngành kinh tế, đánh giá, xây dựng mô hình, đưa ra công thức thành công, phổ biến cho các địa phương trên toàn quốc', '2', '3', '\'- Chọn 1 tỉnh triển khai điểm, đến khí có kết quả; - Đánh giá, ra mô hình, hướng dẫn; - Phổ biến, nhân rộng', '2024-07-31', '2024-08-30', 'admin', '2024-08-05 12:08:30', '2024-08-05 12:08:30'),
(23, 'DV11', 'Xây dựng Nghị định sửa đổi, bổ sung một số điều của Nghị định số 73/2019/NĐ-CP của Chính phủ quy định quản lý đầu tư ứng dụng công nghệ thông tin sử dụng nguồn vốn ngân sách nhà nước', '2', '2', 'Hoàn thiện dự thảo, trình Chính phủ ban hành', '2024-07-29', '2024-08-15', 'admin', '2024-08-05 12:08:30', '2024-08-05 12:08:30');

-- --------------------------------------------------------

--
-- Table structure for table `tasks_document`
--

CREATE TABLE `tasks_document` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `task_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `task_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reporting_cycle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required_result` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `creator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','assign') COLLATE utf8mb4_unicode_ci NOT NULL,
  `progress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `progress_evaluation` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `organization_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_approval_history`
--

CREATE TABLE `task_approval_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_target_id` bigint(20) UNSIGNED NOT NULL,
  `approver_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_type` int(11) DEFAULT NULL,
  `task_result_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_approval_history`
--

INSERT INTO `task_approval_history` (`id`, `task_target_id`, `approver_id`, `status`, `remarks`, `created_at`, `updated_at`, `type`, `number_type`, `task_result_id`) VALUES
(8, 219, 10, 'approved', 'duyệt', '2024-08-22 13:12:30', '2024-08-22 13:12:30', '1', 34, 101),
(9, 221, 10, 'approved', 'ok ổn', '2024-08-23 00:34:52', '2024-08-23 00:34:52', '3', 3, 102);

-- --------------------------------------------------------

--
-- Table structure for table `task_groups`
--

CREATE TABLE `task_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creator_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_groups`
--

INSERT INTO `task_groups` (`id`, `code`, `name`, `description`, `creator_id`, `created_at`, `updated_at`) VALUES
(1, 'NNV03', 'Nhóm nhiệm vụ về văn hóa xã hội', 'Nhóm nhiệm vụ về văn hóa xã hội', 1, '2024-08-15 10:08:56', '2024-08-18 21:07:44'),
(2, 'NNV02', 'Nhóm nhiệm vụ về an ninh quốc phòng', 'Nhóm nhiệm vụ về an ninh quốc phòng', 1, '2024-08-15 10:24:59', '2024-08-18 21:07:01'),
(3, 'NNV01', 'Nhóm các nhiệm vụ về công tác KTXH', 'Nhóm các nhiệm vụ về công tác KTXH', 1, '2024-08-15 10:27:16', '2024-08-18 21:06:24'),
(4, 'NNV09', 'Các nhiệm vụ khác', 'Các nhiệm vụ khác', 1, '2024-08-18 21:08:10', '2024-08-18 21:08:10');

-- --------------------------------------------------------

--
-- Table structure for table `task_result`
--

CREATE TABLE `task_result` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `id_task_criteria` bigint(20) UNSIGNED NOT NULL,
  `result` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_save` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `number_type` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_result`
--

INSERT INTO `task_result` (`id`, `document_id`, `id_task_criteria`, `result`, `description`, `type_save`, `created_at`, `updated_at`, `number_type`, `type`) VALUES
(101, 134, 219, 'ok đã hoàn thành', 'ok', '1', '2024-08-22 13:09:41', '2024-08-22 13:09:41', 34, '1'),
(102, 138, 221, 'ok đã xong', 'ok', '1', '2024-08-23 00:34:19', '2024-08-23 00:34:19', 3, '3');

-- --------------------------------------------------------

--
-- Table structure for table `task_target`
--

CREATE TABLE `task_target` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cycle_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `request_results` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `creator` int(11) NOT NULL,
  `status` enum('new','assign','complete','reject') COLLATE utf8mb4_unicode_ci NOT NULL,
  `results` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('task','target') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `type_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_target`
--

INSERT INTO `task_target` (`id`, `document_id`, `code`, `name`, `cycle_type`, `category_id`, `request_results`, `start_date`, `end_date`, `creator`, `status`, `results`, `description`, `organization_id`, `type`, `created_at`, `updated_at`, `is_completed`, `type_id`) VALUES
(218, 134, 'KHCNTT2004-NV-3841673', 'NV 1', '1', 4, 'NV 1', '2024-08-19', '2024-08-25', 10, 'assign', 'Đang thực hiện', NULL, 1, 'task', '2024-08-22 13:09:09', '2024-08-22 13:09:14', 0, 1),
(219, 134, 'KHCNTT2004-NV-3841673', 'NV 1', '1', 4, 'NV 1', '2024-08-19', '2024-08-25', 10, 'complete', 'Hoàn Thành', 'Trong hạn', 2, 'task', '2024-08-22 13:09:14', '2024-08-22 13:09:41', 1, 1),
(220, 134, 'KHCNTT2004-NV-3841673', 'NV 1', '1', 4, 'NV 1', '2024-08-19', '2024-08-25', 10, 'assign', 'Đang thực hiện', NULL, 3, 'task', '2024-08-22 13:09:14', '2024-08-22 13:09:14', 0, 1),
(221, 138, 'VB1231232-NV-7458469', 'test duyệt 2', '3', 6, 'test duyệt 2', '2024-08-13', '2024-08-29', 1, 'complete', 'Hoàn Thành', 'Trong hạn', 2, 'task', '2024-08-23 00:33:35', '2024-08-23 00:34:19', 1, 2),
(222, 138, 'VB1231232-NV-7458469', 'test duyệt 2', '3', 6, 'test duyệt 2', '2024-08-13', '2024-08-29', 1, 'assign', 'Đang thực hiện', NULL, 3, 'task', '2024-08-23 00:33:43', '2024-08-23 00:33:43', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `organization_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `code`, `phone`, `address`, `unit_id`, `position_id`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin', NULL, NULL, '$2y$12$a0c0lWTMDqqSaXTjLgaJRef/8b./HAI0O029p0M79p9qLQqmVznEK', NULL, '2024-07-31 09:27:37', '2024-08-07 02:21:10', 'admin', '035558062', 'HN231231', NULL, NULL),
(2, 'User Bộ Ngoại Giao', 'user_bng@gmail.com', 'staff', 4, NULL, '$2y$12$0rjy7wEUCslmAqaTCk9EOexfdGlcw1Nzl9BdT7E9mpRiRkP.lIMVS', NULL, '2024-08-01 14:27:55', '2024-08-04 20:16:23', 'NV001', '0355668062', 'Ha Noi', NULL, NULL),
(3, 'User Bộ Quốc phòng', 'user_bqp@gmail.com', 'staff', 2, NULL, '$2y$12$Q/4e5uk07jDOXJk1Qxd2F.FyH.byjyjqCqgBX06DjOsfOmoQMvZBq', 'GBLsfqLchExyfj0E3XbvVq7ziJD5qVw0OMTgELYShggoVUfNajDnrPaazxSf', '2024-08-03 20:01:35', '2024-08-04 20:15:57', 'NV002', '0355668062', 'Ha Noi', NULL, NULL),
(4, 'User Hà Nội', 'user_hn@gmail.com', 'staff', 56, NULL, '$2y$12$H4PJJUnhA68rvgJ.eBNA.euh.SR/.CSEfZ9YOjmFsBu5NdK7JCTke', NULL, '2024-08-03 20:14:47', '2024-08-04 20:15:19', 'NV003', '0355668062', 'Ha Noi', NULL, NULL),
(5, 'User Chính Phủ', 'supper_user@gmail.com', 'admin', 1, NULL, '$2y$12$wrxNLpErYrvEWfiJV7QUH.6aEBfQeLnC4y8xS6D9GpUFlvG8Rqi3G', NULL, '2024-08-07 02:23:04', '2024-08-18 13:10:11', 'NV0005', '0355668062', 'Ha Noi', NULL, NULL),
(6, 'user_test', 'user_test@gmail.com', 'staff', 6, NULL, '$2y$12$PPff5aZc8EBtgxJKlOtxAOleRuyqcTpFhaT7TfarjB0p78Kzb/Xku', NULL, '2024-08-10 03:44:34', '2024-08-10 03:44:34', 'NV00001', '0355668062', 'Ha Noi', NULL, NULL),
(7, 'user_test01', 'user_test01@gmail.com', 'staff', 6, NULL, '$2y$12$xwAqgP.HxAIGr9wXmDUewOOKGf4a7Ztei9bCbuvSuMReuitUFZoxa', NULL, '2024-08-10 03:45:54', '2024-08-10 03:45:54', 'NV01232', '0355668062', 'Ha Noi', NULL, NULL),
(8, 'dev dev3213213', 'kaka@gmail.com', 'admin', 10, NULL, '$2y$12$NezhihpoSvY79UzIg4XJVOaL7ls6aD5fc/QPhNfqW.T/vNBGg4ze6', NULL, '2024-08-15 08:06:21', '2024-08-15 08:10:20', 'NV2', '0355668062', 'Ha Noi', NULL, 1),
(9, 'user Bộ công an', 'user_bca@gmail.com', 'staff', 3, NULL, '$2y$12$khGaA1SgDt9VnI0p9606zeD3SQUV5qDr0ll8FF68kxjyVLsnY3ytG', NULL, '2024-08-18 13:15:45', '2024-08-18 13:15:45', 'NV22222', '0355668062', 'Ha Noi', NULL, 1),
(10, 'Sub-Admin-Bộ quốc phòng', 'sub-admin-bqp@gmail.com', 'sub_admin', 2, NULL, '$2y$12$SkpC0slp3UAtWITTs.rOmuFv2hHRx1NotohUxkiyoILWgByTkffLC', NULL, '2024-08-19 19:25:41', '2024-08-22 12:13:57', 'sub-admin-nv01', '0355668062', 'Ha Noi', NULL, 1),
(11, 'sub-admin BCA', 'sub-admin-bca@gmail.com', 'supper_admin', 3, NULL, '$2y$12$fLAuBrGvW7hdKpTRsgmgkO8HMyCEVRwtjiY7TlBHorXiuMkXNCLX2', NULL, '2024-08-23 00:38:35', '2024-08-23 00:38:35', 'NV123213', '0355668062', 'Ha Noi', NULL, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `criteria_code_unique` (`code`);

--
-- Indexes for table `criterias_task`
--
ALTER TABLE `criterias_task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `documents_category_id_foreign` (`category_id`);

--
-- Indexes for table `document_categories`
--
ALTER TABLE `document_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `document_categories_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_document_id_foreign` (`document_id`);

--
-- Indexes for table `history_change_document`
--
ALTER TABLE `history_change_document`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `indicator_groups`
--
ALTER TABLE `indicator_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `indicator_groups_code_unique` (`code`),
  ADD KEY `indicator_groups_creator_id_foreign` (`creator_id`);

--
-- Indexes for table `metrics`
--
ALTER TABLE `metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metrics_task_id_foreign` (`task_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizations_parent_id_foreign` (`parent_id`),
  ADD KEY `organizations_organization_type_id_foreign` (`organization_type_id`);

--
-- Indexes for table `organization_task`
--
ALTER TABLE `organization_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_task_tasks_document_id_foreign` (`tasks_document_id`),
  ADD KEY `organization_task_document_id_foreign` (`document_id`),
  ADD KEY `organization_task_organization_id_foreign` (`organization_id`),
  ADD KEY `organization_task_users_id_foreign` (`users_id`);

--
-- Indexes for table `organization_types`
--
ALTER TABLE `organization_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `organization_types_code_unique` (`code`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `positions_code_unique` (`code`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks_document`
--
ALTER TABLE `tasks_document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_document_document_id_foreign` (`document_id`);

--
-- Indexes for table `task_approval_history`
--
ALTER TABLE `task_approval_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_approval_history_task_target_id_foreign` (`task_target_id`),
  ADD KEY `task_approval_history_approver_id_foreign` (`approver_id`);

--
-- Indexes for table `task_groups`
--
ALTER TABLE `task_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `task_groups_code_unique` (`code`),
  ADD KEY `task_groups_creator_id_foreign` (`creator_id`);

--
-- Indexes for table `task_result`
--
ALTER TABLE `task_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_result_document_id_foreign` (`document_id`);

--
-- Indexes for table `task_target`
--
ALTER TABLE `task_target`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_target_document_id_foreign` (`document_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_organization_id_foreign` (`organization_id`),
  ADD KEY `users_unit_id_foreign` (`unit_id`),
  ADD KEY `users_position_id_foreign` (`position_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `criterias_task`
--
ALTER TABLE `criterias_task`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=467;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `document_categories`
--
ALTER TABLE `document_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT for table `history_change_document`
--
ALTER TABLE `history_change_document`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `indicator_groups`
--
ALTER TABLE `indicator_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `metrics`
--
ALTER TABLE `metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `organization_task`
--
ALTER TABLE `organization_task`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `organization_types`
--
ALTER TABLE `organization_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tasks_document`
--
ALTER TABLE `tasks_document`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `task_approval_history`
--
ALTER TABLE `task_approval_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `task_groups`
--
ALTER TABLE `task_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `task_result`
--
ALTER TABLE `task_result`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `task_target`
--
ALTER TABLE `task_target`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `document_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `indicator_groups`
--
ALTER TABLE `indicator_groups`
  ADD CONSTRAINT `indicator_groups_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `metrics`
--
ALTER TABLE `metrics`
  ADD CONSTRAINT `metrics_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_organization_type_id_foreign` FOREIGN KEY (`organization_type_id`) REFERENCES `organization_types` (`id`),
  ADD CONSTRAINT `organizations_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `organizations` (`id`);

--
-- Constraints for table `organization_task`
--
ALTER TABLE `organization_task`
  ADD CONSTRAINT `organization_task_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`),
  ADD CONSTRAINT `organization_task_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `organization_task_tasks_document_id_foreign` FOREIGN KEY (`tasks_document_id`) REFERENCES `tasks_document` (`id`),
  ADD CONSTRAINT `organization_task_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks_document`
--
ALTER TABLE `tasks_document`
  ADD CONSTRAINT `tasks_document_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`);

--
-- Constraints for table `task_approval_history`
--
ALTER TABLE `task_approval_history`
  ADD CONSTRAINT `task_approval_history_approver_id_foreign` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_approval_history_task_target_id_foreign` FOREIGN KEY (`task_target_id`) REFERENCES `task_target` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_groups`
--
ALTER TABLE `task_groups`
  ADD CONSTRAINT `task_groups_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `task_result`
--
ALTER TABLE `task_result`
  ADD CONSTRAINT `task_result_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`);

--
-- Constraints for table `task_target`
--
ALTER TABLE `task_target`
  ADD CONSTRAINT `task_target_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`),
  ADD CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `organizations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
