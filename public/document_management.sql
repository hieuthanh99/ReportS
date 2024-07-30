-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 30, 2024 lúc 10:19 PM
-- Phiên bản máy phục vụ: 10.4.22-MariaDB
-- Phiên bản PHP: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `document_management`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
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
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `CreatedBy`, `CreatedDTG`, `UpdatedBy`, `UpdatedDTG`, `created_at`, `updated_at`) VALUES
(1, 'Công nghiệp CNS 2', 'admin', '2024-07-29 10:14:30', NULL, '2024-07-30 08:20:32', '2024-07-29 10:14:30', '2024-07-30 08:20:32'),
(2, 'Số hoá các ngành kinh tế', NULL, '2024-07-29 10:26:19', NULL, NULL, '2024-07-29 10:26:19', '2024-07-29 10:26:19'),
(3, 'Công nghiệp 5.0', NULL, '2024-07-29 10:28:39', NULL, NULL, '2024-07-29 10:28:39', '2024-07-29 10:28:39'),
(4, 'Tự động hóa', NULL, '2024-07-29 10:29:47', NULL, NULL, '2024-07-29 10:29:47', '2024-07-29 10:29:47'),
(5, 'Tin học', NULL, '2024-07-29 10:30:20', NULL, NULL, '2024-07-29 10:30:20', '2024-07-29 10:30:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `criteria`
--

CREATE TABLE `criteria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `criteria`
--

INSERT INTO `criteria` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
(1, 'CT1', 'Chỉ tiêu 1', '2024-07-30 10:23:40', NULL),
(2, 'CT2', 'Chỉ tiêu 2', '2024-07-30 10:23:40', NULL),
(3, 'CT3', 'Chỉ tiêu 3', '2024-07-30 10:23:40', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `criterias_task`
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `criterias_task`
--

INSERT INTO `criterias_task` (`id`, `TaskID`, `CriteriaID`, `CriteriaCode`, `CriteriaName`, `CreatedBy`, `UpdatedBy`, `DocumentID`, `TaskCode`, `RequestResult`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'CT1', 'Chỉ tiêu 1', NULL, NULL, 4, 'DV1', 'OK}', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(2, 1, 2, 'CT2', 'Chỉ tiêu 2', NULL, NULL, 4, 'DV1', 'OK}', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(3, 1, 3, 'CT3', 'Chỉ tiêu 3', NULL, NULL, 4, 'DV1', 'OK}', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(4, 2, 1, 'CT1', 'Chỉ tiêu 1', NULL, NULL, 4, 'DV2', 'OK}', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(5, 2, 2, 'CT2', 'Chỉ tiêu 2', NULL, NULL, 4, 'DV2', 'OK}', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(6, 2, 3, 'CT3', 'Chỉ tiêu 3', NULL, NULL, 4, 'DV2', 'OK}', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(7, 1, 1, 'CT1', 'Chỉ tiêu 1', NULL, NULL, 5, 'DV1', 'OK', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(8, 1, 2, 'CT2', 'Chỉ tiêu 2', NULL, NULL, 5, 'DV1', 'OK', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(9, 1, 3, 'CT3', 'Chỉ tiêu 3', NULL, NULL, 5, 'DV1', 'OK', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(10, 3, 1, 'CT1', 'Chỉ tiêu 1', NULL, NULL, 5, 'DV3', 'OK', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(21, 1, 1, 'CT1', 'Chỉ tiêu 1', NULL, NULL, 8, 'DV1', 'OK', '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(22, 1, 2, 'CT2', 'Chỉ tiêu 2', NULL, NULL, 8, 'DV1', 'OK', '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(23, 1, 3, 'CT3', 'Chỉ tiêu 3', NULL, NULL, 8, 'DV1', 'OK', '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(24, 2, 1, 'CT1', 'Chỉ tiêu 1', NULL, NULL, 8, 'DV2', 'OK', '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(25, 2, 2, 'CT2', 'Chỉ tiêu 2', NULL, NULL, 8, 'DV2', 'OK', '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(26, 2, 3, 'CT3', 'Chỉ tiêu 3', NULL, NULL, 8, 'DV2', 'OK', '2024-07-30 13:03:48', '2024-07-30 13:03:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `issuing_department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `creator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `documents`
--

INSERT INTO `documents` (`id`, `document_code`, `document_name`, `issuing_department`, `creator`, `release_date`, `created_at`, `updated_at`) VALUES
(1, 'VB1', 'văn bản 1', '1', 'admin', '2024-08-03', '2024-07-30 01:32:24', '2024-07-30 01:32:24'),
(2, 'VB2', 'Văn bản 2', '1', 'admin', '2024-07-25', '2024-07-30 07:33:11', '2024-07-30 07:33:11'),
(3, 'VB2', 'Văn bản 2', '1', 'admin', '2024-07-25', '2024-07-30 07:33:40', '2024-07-30 07:33:40'),
(4, 'VB2', 'Văn bản 2', '1', 'admin', '2024-07-25', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(5, 'VB3', 'Văn bản 3', '4', 'admin', '2024-08-03', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(8, 'VB21', 'VB21', '4', 'admin', '2024-08-01', '2024-07-30 13:03:48', '2024-07-30 13:03:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
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
-- Cấu trúc bảng cho bảng `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `files`
--

INSERT INTO `files` (`id`, `document_id`, `file_name`, `file_path`, `created_at`, `updated_at`) VALUES
(1, 1, '1722328344_CV_NGUYEN_HIEU_THANH.pdf', 'documents/1722328344_CV_NGUYEN_HIEU_THANH.pdf', '2024-07-30 01:32:24', '2024-07-30 01:32:24'),
(2, 2, '1722349991_CV_NGUYEN_HIEU_THANH.pdf', 'documents/1722349991_CV_NGUYEN_HIEU_THANH.pdf', '2024-07-30 07:33:11', '2024-07-30 07:33:11'),
(3, 3, '1722350020_CV_NGUYEN_HIEU_THANH.pdf', 'documents/1722350020_CV_NGUYEN_HIEU_THANH.pdf', '2024-07-30 07:33:40', '2024-07-30 07:33:40'),
(4, 4, '1722350034_CV_NGUYEN_HIEU_THANH.pdf', 'documents/1722350034_CV_NGUYEN_HIEU_THANH.pdf', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(5, 5, '1722352977_DSS09.01_Luồng+đăng+ký+License+&+Client.doc', 'documents/1722352977_DSS09.01_Luồng+đăng+ký+License+&+Client.doc', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(8, 8, '1722369828_CV_NGUYEN_HIEU_THANH.pdf', 'documents/1722369828_CV_NGUYEN_HIEU_THANH.pdf', '2024-07-30 13:03:48', '2024-07-30 13:03:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `metrics`
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
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_07_29_153753_create_documents_table', 1),
(6, '2024_07_29_153758_create_tasks_table', 1),
(7, '2024_07_29_153807_create_metrics_table', 1),
(8, '2024_07_29_155712_create_organizations_table', 2),
(9, '2024_07_29_164001_add_release_date_to_documents_table', 3),
(10, '2024_07_29_170823_create_categories_table', 4),
(11, '2024_07_29_184625_add_organization_id_to_users_table', 5),
(12, '2024_07_30_062149_drop_document_id_from_tasks_table', 6),
(13, '2024_07_30_080844_create_tasks_document_table', 7),
(14, '2024_07_30_081501_create_files_table', 8),
(15, '2024_07_30_083156_remove_start_date_and_end_date_from_documents_table', 9),
(16, '2024_07_30_085357_create_criteria_table', 10),
(17, '2024_07_30_131022_create_criterias_task_table', 11),
(18, '2024_07_30_182810_create_organization_task_table', 12);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('tỉnh','bộ') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `creator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `organizations`
--

INSERT INTO `organizations` (`id`, `code`, `name`, `type`, `email`, `phone`, `parent_id`, `creator`, `created_at`, `updated_at`) VALUES
(1, 'VietNam', 'Việt Nam', 'tỉnh', 'kak@gmail.com', '0355668062', NULL, '1', '2024-07-29 10:48:41', '2024-07-29 10:48:41'),
(2, 'Hanoi', 'Hà Nội', 'tỉnh', 'kak@gmail.com', '0355668062', 1, '1', '2024-07-29 10:49:00', '2024-07-29 10:49:00'),
(3, 'HoChiMinh', 'Hồ Chí Minh', 'tỉnh', 'kak@gmail.com', '0355668062', 1, '1', '2024-07-29 10:49:27', '2024-07-29 10:49:27'),
(4, 'Caugiay', 'Cầu giấy', 'tỉnh', 'kak@gmail.com', '0355668062', 2, '1', '2024-07-29 10:49:45', '2024-07-29 10:49:45'),
(5, 'Korea', 'Hàn quốc', 'tỉnh', 'kak@gmail.com', '0355668062', NULL, '1', '2024-07-29 11:21:51', '2024-07-29 11:21:51'),
(6, 'QH', 'Quan hoa', 'bộ', 'kak@gmail.com', '0355668062', 4, '1', '2024-07-30 09:11:43', '2024-07-30 09:11:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `organization_task`
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

--
-- Đang đổ dữ liệu cho bảng `organization_task`
--

INSERT INTO `organization_task` (`id`, `tasks_document_id`, `document_id`, `organization_id`, `creator`, `users_id`, `created_at`, `updated_at`) VALUES
(1, 27, 8, 2, 'admin', 1, '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(2, 27, 8, 3, 'admin', 1, '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(3, 27, 8, 4, 'admin', 1, '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(4, 27, 8, 6, 'admin', 1, '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(5, 28, 8, 2, 'admin', 1, '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(6, 28, 8, 3, 'admin', 1, '2024-07-30 13:03:48', '2024-07-30 13:03:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
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
-- Cấu trúc bảng cho bảng `tasks`
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
-- Đang đổ dữ liệu cho bảng `tasks`
--

INSERT INTO `tasks` (`id`, `task_code`, `task_name`, `reporting_cycle`, `category`, `required_result`, `start_date`, `end_date`, `creator`, `created_at`, `updated_at`) VALUES
(1, 'DV1', 'Đầu việc 1', '1', '2', 'OK', '2024-07-29', '2024-07-31', 'admin', '2024-07-30 06:22:54', NULL),
(2, 'DV2', 'Đầu việc 2', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', '2024-07-30 06:25:11', NULL),
(3, 'DV3', 'Đầu việc 3', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', '2024-07-30 06:25:11', NULL),
(4, 'DV4', 'Đầu việc 4', '1', '1', 'OK', '2024-07-30', '2024-07-31', 'admin', '2024-07-30 06:25:11', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tasks_document`
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tasks_document`
--

INSERT INTO `tasks_document` (`id`, `document_id`, `task_code`, `task_name`, `reporting_cycle`, `category`, `required_result`, `start_date`, `end_date`, `creator`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'DV1', 'Đầu việc 1', '1', '2', 'OK', '2024-07-29', '2024-07-31', 'admin', 'draft', '2024-07-30 01:32:24', '2024-07-30 01:32:24'),
(2, 1, 'DV2', 'Đầu việc 2', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 01:32:24', '2024-07-30 01:32:24'),
(3, 1, 'DV3', 'Đầu việc 3', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 01:32:24', '2024-07-30 01:32:24'),
(4, 1, 'DV4', 'Đầu việc 4', '1', '1', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 01:32:24', '2024-07-30 01:32:24'),
(5, 2, 'DV1', 'Đầu việc 1', '1', '2', 'OK', '2024-07-29', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:11', '2024-07-30 07:33:11'),
(6, 2, 'DV2', 'Đầu việc 2', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:11', '2024-07-30 07:33:11'),
(7, 2, 'DV3', 'Đầu việc 3', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:11', '2024-07-30 07:33:11'),
(8, 2, 'DV4', 'Đầu việc 4', '1', '1', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:11', '2024-07-30 07:33:11'),
(9, 3, 'DV1', 'Đầu việc 1', '1', '2', 'OK', '2024-07-29', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:40', '2024-07-30 07:33:40'),
(10, 3, 'DV2', 'Đầu việc 2', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:40', '2024-07-30 07:33:40'),
(11, 3, 'DV3', 'Đầu việc 3', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:40', '2024-07-30 07:33:40'),
(12, 3, 'DV4', 'Đầu việc 4', '1', '1', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:40', '2024-07-30 07:33:40'),
(13, 4, 'DV1', 'Đầu việc 1', '1', '2', 'OK', '2024-07-29', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(14, 4, 'DV2', 'Đầu việc 2', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(15, 4, 'DV3', 'Đầu việc 3', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(16, 4, 'DV4', 'Đầu việc 4', '1', '1', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 07:33:54', '2024-07-30 07:33:54'),
(17, 5, 'DV1', 'Đầu việc 1', '1', '2', 'OK', '2024-07-29', '2024-07-31', 'admin', 'draft', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(18, 5, 'DV2', 'Đầu việc 2', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(19, 5, 'DV3', 'Đầu việc 3', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(20, 5, 'DV4', 'Đầu việc 4', '1', '1', 'OK', '2024-07-30', '2024-07-31', 'admin', 'draft', '2024-07-30 08:22:57', '2024-07-30 08:22:57'),
(27, 8, 'DV1', 'Đầu việc 1', '1', '2', 'OK', '2024-07-29', '2024-07-31', 'admin', 'assign', '2024-07-30 13:03:48', '2024-07-30 13:03:48'),
(28, 8, 'DV2', 'Đầu việc 2', '2', '3', 'OK', '2024-07-30', '2024-07-31', 'admin', 'assign', '2024-07-30 13:03:48', '2024-07-30 13:03:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `organization_id`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, NULL, '$2y$12$56bmN.iTbM/WQ7ZIh0F8XODuuhOnOWWsOTi6HTbQCHpREsysFsBcm', NULL, '2024-07-29 09:08:44', '2024-07-29 09:08:44');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Chỉ mục cho bảng `criteria`
--
ALTER TABLE `criteria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `criteria_code_unique` (`code`);

--
-- Chỉ mục cho bảng `criterias_task`
--
ALTER TABLE `criterias_task`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `files_document_id_foreign` (`document_id`);

--
-- Chỉ mục cho bảng `metrics`
--
ALTER TABLE `metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `metrics_task_id_foreign` (`task_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizations_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `organization_task`
--
ALTER TABLE `organization_task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organization_task_tasks_document_id_foreign` (`tasks_document_id`),
  ADD KEY `organization_task_document_id_foreign` (`document_id`),
  ADD KEY `organization_task_organization_id_foreign` (`organization_id`),
  ADD KEY `organization_task_users_id_foreign` (`users_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tasks_document`
--
ALTER TABLE `tasks_document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_document_document_id_foreign` (`document_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_organization_id_foreign` (`organization_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `criteria`
--
ALTER TABLE `criteria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `criterias_task`
--
ALTER TABLE `criterias_task`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `metrics`
--
ALTER TABLE `metrics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `organization_task`
--
ALTER TABLE `organization_task`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `tasks_document`
--
ALTER TABLE `tasks_document`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `metrics`
--
ALTER TABLE `metrics`
  ADD CONSTRAINT `metrics_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

--
-- Các ràng buộc cho bảng `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `organizations_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `organizations` (`id`);

--
-- Các ràng buộc cho bảng `organization_task`
--
ALTER TABLE `organization_task`
  ADD CONSTRAINT `organization_task_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`),
  ADD CONSTRAINT `organization_task_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `organization_task_tasks_document_id_foreign` FOREIGN KEY (`tasks_document_id`) REFERENCES `tasks_document` (`id`),
  ADD CONSTRAINT `organization_task_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `tasks_document`
--
ALTER TABLE `tasks_document`
  ADD CONSTRAINT `tasks_document_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
