-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 30, 2024 at 06:59 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db-si-apotek`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_stok_masuk`
--

CREATE TABLE `laporan_stok_masuk` (
  `id` bigint UNSIGNED NOT NULL,
  `obat_id` bigint UNSIGNED NOT NULL,
  `jumlah_masuk` int NOT NULL,
  `expired` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_stok_masuk`
--

INSERT INTO `laporan_stok_masuk` (`id`, `obat_id`, `jumlah_masuk`, `expired`, `created_at`, `updated_at`) VALUES
(9, 4, 100, '2024-12-26', '2024-12-25 00:24:17', '2024-12-25 00:24:17'),
(10, 4, 2, '2024-12-30', '2024-12-25 00:38:14', '2024-12-25 00:38:14'),
(11, 18, 69, '2024-12-26', '2024-12-25 00:42:33', '2024-12-25 00:42:33'),
(12, 15, 1, '2024-12-26', '2024-12-25 04:33:05', '2024-12-25 04:33:05'),
(13, 4, 10, '2025-01-03', '2024-12-25 23:48:25', '2024-12-25 23:48:25'),
(14, 19, 10, '2024-12-29', '2024-12-28 08:13:35', '2024-12-28 08:13:35'),
(15, 4, 21, '2024-12-31', '2024-12-29 21:37:37', '2024-12-29 21:37:37'),
(16, 18, 22, '2025-01-09', '2024-12-29 21:40:47', '2024-12-29 21:40:47'),
(17, 15, 21, '2025-01-11', '2024-12-29 21:40:47', '2024-12-29 21:40:47'),
(18, 4, 10, '2024-12-31', '2024-12-29 21:40:47', '2024-12-29 21:40:47'),
(19, 15, 21, '2025-01-10', '2024-12-29 21:41:33', '2024-12-29 21:41:33');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2014_10_12_100000_create_password_resets_table', 2),
(6, '2024_10_18_060439_create_obat_table', 2),
(7, '2024_10_18_083924_create_obat_table', 3),
(8, '2024_10_21_133832_add_role_to_users_table', 4),
(9, '2024_10_21_143523_add_role_to_users_table', 5),
(10, '2024_10_22_124750_add_ceklevel_to_users_table', 6),
(11, '2024_12_15_132829_create_obat_keluar_table', 7),
(12, '2024_12_15_140211_add_stok_keluar_to_obat_table', 8),
(13, '2024_12_17_140042_create_obat_keluars_table', 9),
(14, '2024_12_17_142015_add_obat_id_to_obat_keluars_table', 10),
(15, '2024_12_17_142149_add_default_to_nama_obat_in_obat_keluars_table', 10),
(16, '2024_12_17_145722_modify_jumlah_keluar_in_obat_keluars_table', 11),
(17, '2024_12_22_150343_create_stok_masuk_table', 12),
(18, '2024_12_24_131937_create_stok_masuk_table', 13),
(19, '2024_12_25_063630_create_stok_masuk_table', 14),
(20, '2024_12_25_065856_create_laporan_stok_masuk_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_obat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unknown',
  `harga_beli` decimal(10,2) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `expired` date DEFAULT NULL,
  `stok` int NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stok_keluar` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `nama_obat`, `harga_beli`, `harga_jual`, `expired`, `stok`, `created_at`, `updated_at`, `stok_keluar`) VALUES
(4, 'KOMIX', '4000.00', '6000.00', '2024-12-30', 2330, '0000-00-00 00:00:00', '2024-12-29 23:20:05', 0),
(15, 'GENOINT SK 15G', '6044.00', '11000.00', '2024-12-26', 1573, '2024-12-17 06:46:15', '2024-12-29 21:41:33', 0),
(16, 'ACYCLOVIR CREAM 5G', '3885.00', '7500.00', '2024-12-25', 10100, '2024-12-17 06:46:45', '2024-12-25 00:09:16', 0),
(18, 'OSKADON SP', '2500.00', '3000.00', '2025-01-03', 362, '2024-12-22 08:02:25', '2024-12-29 21:40:47', 0),
(19, 'Combantrin', '7000.00', '10000.00', '2025-01-02', 260, '2024-12-25 06:15:19', '2024-12-28 08:13:35', 0),
(20, 'Test', '12000.00', '15000.00', '2025-01-10', 200, '2024-12-29 22:57:33', '2024-12-29 22:57:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `obat_keluars`
--

CREATE TABLE `obat_keluars` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_obat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Unknown',
  `jumlah_keluar` int DEFAULT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `obat_keluars`
--

INSERT INTO `obat_keluars` (`id`, `nama_obat`, `jumlah_keluar`, `tanggal_keluar`, `created_at`, `updated_at`) VALUES
(14, 'KOMIX', 2, '2024-12-18', '2024-12-17 15:00:07', '2024-12-17 15:00:07'),
(15, 'GENOINT SK 15G', 2, '2024-12-18', '2024-12-17 15:03:37', '2024-12-17 15:03:37'),
(16, 'ACYCLOVIR CREAM 5G', 1, '2024-12-20', '2024-12-17 15:04:37', '2024-12-17 15:04:37'),
(17, 'ACYCLOVIR CREAM 5G', 1, '2024-12-20', '2024-12-17 15:05:47', '2024-12-17 15:05:47'),
(18, 'KOMIX', 2, '2024-12-17', '2024-12-17 15:07:09', '2024-12-17 15:07:09'),
(19, 'ACYCLOVIR CREAM 5G', 3, '2024-12-19', '2024-12-17 15:07:37', '2024-12-17 15:07:37'),
(20, 'KOMIX', 1, '2024-12-17', '2024-12-17 15:17:54', '2024-12-17 15:17:54'),
(21, 'GENOINT SK 15G', 2, '2024-12-17', '2024-12-17 15:18:58', '2024-12-17 15:18:58'),
(22, 'ACYCLOVIR CREAM 5G', 1, '2024-12-17', '2024-12-17 15:19:16', '2024-12-17 15:19:16'),
(23, 'ACYCLOVIR CREAM 5G', 1, '2024-12-17', '2024-12-17 15:19:48', '2024-12-17 15:19:48'),
(24, 'GENOINT SK 15G', 2, '2024-12-17', '2024-12-17 15:20:14', '2024-12-17 15:20:14'),
(25, 'KOMIX', 1, '2024-12-17', '2024-12-17 15:29:12', '2024-12-17 15:29:12'),
(26, 's', 2, '2024-12-17', '2024-12-17 15:40:47', '2024-12-17 15:40:47'),
(27, 'KOMIX', 3, '2024-12-17', '2024-12-17 15:49:30', '2024-12-17 15:49:30'),
(28, 'KOMIX', 3, '2024-12-17', '2024-12-17 15:49:31', '2024-12-17 15:49:31'),
(29, 'KOMIX', 2, '2024-12-17', '2024-12-17 15:54:40', '2024-12-17 15:54:40'),
(30, 'ACYCLOVIR CREAM 5G', 1, '2024-12-17', '2024-12-17 16:07:23', '2024-12-17 16:07:23'),
(31, 'KOMIX', 2, '2024-12-18', '2024-12-17 18:24:24', '2024-12-17 18:24:24'),
(32, 'KOMIX', 2, '2024-12-20', '2024-12-19 20:05:28', '2024-12-19 20:05:28'),
(33, 'GENOINT SK 15G', 10, '2024-12-20', '2024-12-19 20:06:17', '2024-12-19 20:06:17'),
(34, 'GENOINT SK 15G', 5, '2024-12-20', '2024-12-19 20:11:20', '2024-12-19 20:11:20'),
(35, 'GENOINT SK 15G', 2, '2024-12-20', '2024-12-19 20:11:48', '2024-12-19 20:11:48'),
(36, 'KOMIX', 2, '2024-12-20', '2024-12-19 20:16:31', '2024-12-19 20:16:31'),
(37, 'KOMIX', 3, '2024-12-20', '2024-12-19 20:19:22', '2024-12-19 20:19:22'),
(38, 'KOMIX', 3, '2024-12-20', '2024-12-19 20:21:05', '2024-12-19 20:21:05'),
(39, 'GENOINT SK 15G', 2, '2024-12-20', '2024-12-19 20:21:09', '2024-12-19 20:21:09'),
(40, 'GENOINT SK 15G', 1, '2024-12-20', '2024-12-19 20:25:38', '2024-12-19 20:25:38'),
(41, 'KOMIX', 1, '2024-12-20', '2024-12-19 20:25:44', '2024-12-19 20:25:44'),
(42, 'KOMIX', 1, '2024-12-21', '2024-12-20 20:37:50', '2024-12-20 20:37:50'),
(43, 'GENOINT SK 15G', 2, '2024-12-21', '2024-12-20 20:37:56', '2024-12-20 20:37:56'),
(44, 'ACYCLOVIR CREAM 5G', 10, '2024-12-22', '2024-12-22 07:24:43', '2024-12-22 07:24:43'),
(45, 'KOMIX', 25, '2024-12-22', '2024-12-22 07:49:47', '2024-12-22 07:49:47'),
(46, 'OSKADON SP', 6, '2024-12-23', '2024-12-23 05:47:24', '2024-12-23 05:47:24'),
(47, 'ACYCLOVIR CREAM 5G', 90, '2024-12-25', '2024-12-25 00:05:17', '2024-12-25 00:05:17'),
(48, 'KOMIX', 4, '2024-12-25', '2024-12-25 01:07:40', '2024-12-25 01:07:40'),
(49, 'GENOINT SK 15G', 1, '2024-12-25', '2024-12-25 01:07:45', '2024-12-25 01:07:45'),
(50, 'OSKADON SP', 2, '2024-12-25', '2024-12-25 01:07:48', '2024-12-25 01:07:48'),
(51, 'KOMIX', 1, '2024-12-25', '2024-12-25 01:09:31', '2024-12-25 01:09:31'),
(52, 'KOMIX', 1, '2024-12-25', '2024-12-25 01:13:00', '2024-12-25 01:13:00'),
(53, 'KOMIX', 8, '2024-12-26', '2024-12-25 23:48:40', '2024-12-25 23:48:40'),
(54, 'KOMIX', 1, '2024-12-30', '2024-12-29 23:20:05', '2024-12-29 23:20:05');

-- --------------------------------------------------------

--
-- Table structure for table `obat_masuks`
--

CREATE TABLE `obat_masuks` (
  `id` bigint UNSIGNED NOT NULL,
  `obat_id` bigint UNSIGNED DEFAULT NULL,
  `jumlah_masuk` int NOT NULL,
  `expired` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_obat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stok_obat`
--

CREATE TABLE `stok_obat` (
  `id` bigint UNSIGNED NOT NULL,
  `obat_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `expired` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stok_obat`
--

INSERT INTO `stok_obat` (`id`, `obat_id`, `jumlah`, `expired`, `created_at`, `updated_at`) VALUES
(1, 4, 20, '2025-01-01', '2024-12-22 07:44:00', '2024-12-22 07:44:00'),
(2, 4, 18, '2024-12-23', '2024-12-22 07:44:10', '2024-12-22 07:44:47');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` bigint UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `total`, `created_at`, `updated_at`) VALUES
(1, '0.00', '2024-12-17 22:41:44', '2024-12-17 22:41:44');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_final`
--

CREATE TABLE `transaksi_final` (
  `id` bigint UNSIGNED NOT NULL,
  `total_belanja` decimal(10,2) NOT NULL,
  `uang_pembayaran` decimal(10,2) NOT NULL DEFAULT 0,
  `kembalian` decimal(10,2) NOT NULL DEFAULT 0,
  `detail_obat` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_final`
--

INSERT INTO `transaksi_final` (`id`, `total_belanja`, `uang_pembayaran`, `kembalian`, `detail_obat`, `created_at`, `updated_at`) VALUES
(4, '18000.00', '20000.00', '2000.00', '[{\"id\":1,\"nama_obat\":\"KOMIX\",\"jumlah\":3,\"harga\":\"6000.00\",\"subtotal\":\"18000.00\",\"created_at\":\"2024-12-20 03:19:22\",\"updated_at\":\"2024-12-20 03:19:22\"}]', '2024-12-19 20:19:37', '2024-12-19 20:19:37'),
(5, '40000.00', '50000.00', '10000.00', '[{\"id\":1,\"nama_obat\":\"KOMIX\",\"jumlah\":3,\"harga\":\"6000.00\",\"subtotal\":\"18000.00\",\"created_at\":\"2024-12-20 03:21:05\",\"updated_at\":\"2024-12-20 03:21:05\"},{\"id\":2,\"nama_obat\":\"GENOINT SK 15G\",\"jumlah\":2,\"harga\":\"11000.00\",\"subtotal\":\"22000.00\",\"created_at\":\"2024-12-20 03:21:09\",\"updated_at\":\"2024-12-20 03:21:09\"}]', '2024-12-19 20:21:19', '2024-12-19 20:21:19'),
(6, '17000.00', '20000.00', '3000.00', '[{\"id\":1,\"nama_obat\":\"GENOINT SK 15G\",\"jumlah\":1,\"harga\":\"11000.00\",\"subtotal\":\"11000.00\",\"created_at\":\"2024-12-20 03:25:38\",\"updated_at\":\"2024-12-20 03:25:38\"},{\"id\":2,\"nama_obat\":\"KOMIX\",\"jumlah\":1,\"harga\":\"6000.00\",\"subtotal\":\"6000.00\",\"created_at\":\"2024-12-20 03:25:44\",\"updated_at\":\"2024-12-20 03:25:44\"}]', '2024-12-19 20:25:53', '2024-12-19 20:25:53'),
(7, '271000.00', '299999.00', '28999.00', '[{\"id\":1,\"nama_obat\":\"KOMIX\",\"jumlah\":1,\"harga\":\"6000.00\",\"subtotal\":\"6000.00\",\"created_at\":\"2024-12-21 03:37:50\",\"updated_at\":\"2024-12-21 03:37:50\"},{\"id\":2,\"nama_obat\":\"GENOINT SK 15G\",\"jumlah\":2,\"harga\":\"11000.00\",\"subtotal\":\"22000.00\",\"created_at\":\"2024-12-21 03:37:56\",\"updated_at\":\"2024-12-21 03:37:56\"},{\"id\":3,\"nama_obat\":\"ACYCLOVIR CREAM 5G\",\"jumlah\":10,\"harga\":\"7500.00\",\"subtotal\":\"75000.00\",\"created_at\":\"2024-12-22 14:24:43\",\"updated_at\":\"2024-12-22 14:24:43\"},{\"id\":4,\"nama_obat\":\"KOMIX\",\"jumlah\":25,\"harga\":\"6000.00\",\"subtotal\":\"150000.00\",\"created_at\":\"2024-12-22 14:49:47\",\"updated_at\":\"2024-12-22 14:49:47\"},{\"id\":5,\"nama_obat\":\"OSKADON SP\",\"jumlah\":6,\"harga\":\"3000.00\",\"subtotal\":\"18000.00\",\"created_at\":\"2024-12-23 12:47:24\",\"updated_at\":\"2024-12-23 12:47:24\"}]', '2024-12-23 05:47:36', '2024-12-23 05:47:36'),
(8, '716000.00', '1000000.00', '284000.00', '[{\"id\":1,\"nama_obat\":\"ACYCLOVIR CREAM 5G\",\"jumlah\":90,\"harga\":\"7500.00\",\"subtotal\":\"675000.00\",\"created_at\":\"2024-12-25 07:05:17\",\"updated_at\":\"2024-12-25 07:05:17\"},{\"id\":2,\"nama_obat\":\"KOMIX\",\"jumlah\":4,\"harga\":\"6000.00\",\"subtotal\":\"24000.00\",\"created_at\":\"2024-12-25 08:07:40\",\"updated_at\":\"2024-12-25 08:07:40\"},{\"id\":3,\"nama_obat\":\"GENOINT SK 15G\",\"jumlah\":1,\"harga\":\"11000.00\",\"subtotal\":\"11000.00\",\"created_at\":\"2024-12-25 08:07:45\",\"updated_at\":\"2024-12-25 08:07:45\"},{\"id\":4,\"nama_obat\":\"OSKADON SP\",\"jumlah\":2,\"harga\":\"3000.00\",\"subtotal\":\"6000.00\",\"created_at\":\"2024-12-25 08:07:48\",\"updated_at\":\"2024-12-25 08:07:48\"}]', '2024-12-25 01:08:17', '2024-12-25 01:08:17'),
(9, '6000.00', '10000.00', '4000.00', '[{\"id\":1,\"nama_obat\":\"KOMIX\",\"jumlah\":1,\"harga\":\"6000.00\",\"subtotal\":\"6000.00\",\"created_at\":\"2024-12-25 08:09:31\",\"updated_at\":\"2024-12-25 08:09:31\"}]', '2024-12-25 01:09:42', '2024-12-25 01:09:42'),
(10, '54000.00', '60000.00', '6000.00', '[{\"id\":1,\"nama_obat\":\"KOMIX\",\"jumlah\":1,\"harga\":\"6000.00\",\"subtotal\":\"6000.00\",\"created_at\":\"2024-12-25 08:13:00\",\"updated_at\":\"2024-12-25 08:13:00\"},{\"id\":2,\"nama_obat\":\"KOMIX\",\"jumlah\":8,\"harga\":\"6000.00\",\"subtotal\":\"48000.00\",\"created_at\":\"2024-12-26 06:48:40\",\"updated_at\":\"2024-12-26 06:48:40\"}]', '2024-12-25 23:48:54', '2024-12-25 23:48:54'),
(11, '6000.00', '7000.00', '1000.00', '[{\"id\":1,\"nama_obat\":\"KOMIX\",\"jumlah\":1,\"harga\":\"6000.00\",\"subtotal\":\"6000.00\",\"created_at\":\"2024-12-30 06:20:05\",\"updated_at\":\"2024-12-30 06:20:05\"}]', '2024-12-29 23:22:07', '2024-12-29 23:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_sementara`
--

CREATE TABLE `transaksi_sementara` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_obat` varchar(255) NOT NULL,
  `jumlah` int NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `level`) VALUES
(1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$EFKbB9J.HZEnvRPq7ugQpue1TO/q08/uUSxAqjdegvHGxCbrNXlg6', NULL, '2024-10-21 07:07:08', '2024-10-21 07:07:08', 'admin'),
(2, 'test', 'test@gmail.com', NULL, '$2y$12$o7KjeTnFXv.HPTErKGlr9OCEuRji/ZQWe6z7xVCnj8plbpLVUunAe', NULL, '2024-10-21 07:07:09', '2024-10-21 07:07:09', 'user'),
(3, 'achil', 'achil@gmail.com', NULL, '$2y$12$8m7Fr7K2gNaWZIPoqpgcMOQyBykP5pZvcv8cDuhdRK1e84iXVpUEu', NULL, '2024-10-22 06:30:58', '2024-10-22 06:30:58', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `laporan_stok_masuk`
--
ALTER TABLE `laporan_stok_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_stok_masuk_obat_id_foreign` (`obat_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `obat_keluars`
--
ALTER TABLE `obat_keluars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `obat_masuks`
--
ALTER TABLE `obat_masuks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `obat_id` (`obat_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

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
-- Indexes for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_obat`
--
ALTER TABLE `stok_obat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `obat_id` (`obat_id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi_final`
--
ALTER TABLE `transaksi_final`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi_sementara`
--
ALTER TABLE `transaksi_sementara`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_stok_masuk`
--
ALTER TABLE `laporan_stok_masuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `obat_keluars`
--
ALTER TABLE `obat_keluars`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `obat_masuks`
--
ALTER TABLE `obat_masuks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stok_obat`
--
ALTER TABLE `stok_obat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi_final`
--
ALTER TABLE `transaksi_final`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaksi_sementara`
--
ALTER TABLE `transaksi_sementara`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporan_stok_masuk`
--
ALTER TABLE `laporan_stok_masuk`
  ADD CONSTRAINT `laporan_stok_masuk_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `obat_masuks`
--
ALTER TABLE `obat_masuks`
  ADD CONSTRAINT `obat_masuks_ibfk_1` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stok_obat`
--
ALTER TABLE `stok_obat`
  ADD CONSTRAINT `stok_obat_ibfk_1` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
