-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2025 at 03:22 PM
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
-- Database: `htql_ttan`
--

-- --------------------------------------------------------

--
-- Table structure for table `binhluan`
--

CREATE TABLE `binhluan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `noidung` text NOT NULL,
  `doituonglienquan_type` varchar(255) NOT NULL,
  `doituonglienquan_id` bigint(20) UNSIGNED NOT NULL,
  `binhluancha_id` bigint(20) UNSIGNED DEFAULT NULL,
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
-- Table structure for table `cahoc`
--

CREATE TABLE `cahoc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenca` varchar(100) NOT NULL,
  `thoigianbatdau` time NOT NULL,
  `thoigianketthuc` time NOT NULL,
  `ghichu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cahoc`
--

INSERT INTO `cahoc` (`id`, `tenca`, `thoigianbatdau`, `thoigianketthuc`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, 'cas sang', '00:00:07', '00:00:08', NULL, NULL, NULL),
(2, 'ads', '07:32:41', '00:00:00', NULL, NULL, '2025-06-15 18:18:49'),
(3, 'Chieeuf', '14:20:00', '18:00:00', 'oke', '2025-06-15 17:46:07', '2025-06-15 17:46:07'),
(7, 'Toios', '17:00:00', '19:30:00', 'ad', '2025-06-18 14:11:07', '2025-06-18 14:11:07');

-- --------------------------------------------------------

--
-- Table structure for table `chucdanh`
--

CREATE TABLE `chucdanh` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chucdanh`
--

INSERT INTO `chucdanh` (`id`, `ten`, `mota`, `create_at`, `update_at`) VALUES
(1, 'Nhân viên tư vấn', NULL, NULL, NULL),
(2, 'Giáo vụ	', NULL, NULL, NULL),
(3, 'Giáo viên	', NULL, NULL, NULL),
(4, 'Học viên	', NULL, NULL, NULL),
(5, 'Kế toán	', NULL, NULL, NULL),
(6, 'Quản lý trung tâm (Center Manager)	', NULL, NULL, NULL),
(7, 'Giảng viên chính', NULL, NULL, NULL),
(8, 'Trợ giảng (Assistant Teacher)', NULL, NULL, NULL),
(9, 'Lễ tân / Tư vấn viên', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chuyenmon`
--

CREATE TABLE `chuyenmon` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenchuyenmon` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chuyenmon`
--

INSERT INTO `chuyenmon` (`id`, `tenchuyenmon`, `mota`, `create_at`, `update_at`) VALUES
(1, 'Tiếng Anh giao tiếp	', NULL, NULL, NULL),
(2, 'Tiếng Anh thiếu nhi	', NULL, NULL, NULL),
(3, 'Tiếng Anh thiếu niên	', NULL, NULL, NULL),
(4, 'IELTS', NULL, NULL, NULL),
(5, 'TOEIC', NULL, NULL, NULL),
(6, 'TOEFL', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coso`
--

CREATE TABLE `coso` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tencoso` varchar(255) NOT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coso`
--

INSERT INTO `coso` (`id`, `tencoso`, `diachi`, `sdt`, `email`, `mota`, `create_at`, `update_at`) VALUES
(1, 'Cosoa', 'ct', NULL, NULL, NULL, NULL, NULL),
(2, 'ABc', 'ct', 'Sang123@gmail.com', 'Sang123@gmail.com', 'ád', '2025-06-16 23:17:21', '2025-06-16 23:17:21'),
(3, 'ABc', 'ct', 'Sang123@gmail.com', 'Sang123@gmail.com', NULL, '2025-06-16 23:18:03', '2025-06-16 23:18:03'),
(4, 'ABc', 'ct', '09394', 'Sang123@gmail.com', NULL, '2025-06-16 23:18:22', '2025-06-16 23:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `diemdanh`
--

CREATE TABLE `diemdanh` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lophoc_id` bigint(20) UNSIGNED NOT NULL,
  `hocvien_id` bigint(20) UNSIGNED NOT NULL,
  `giaovien_id` bigint(20) UNSIGNED NOT NULL,
  `thoikhoabieu_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngaydiemdanh` date NOT NULL,
  `thoigiandiemdanh` time DEFAULT NULL,
  `trangthaidiemdanh` enum('co_mat','vang_mat','co_phep','di_muon') NOT NULL,
  `ghichu` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dongia`
--

CREATE TABLE `dongia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trinhdo_id` bigint(20) UNSIGNED NOT NULL,
  `namhoc_id` bigint(20) UNSIGNED NOT NULL,
  `hocphi` decimal(10,2) NOT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dongia`
--

INSERT INTO `dongia` (`id`, `trinhdo_id`, `namhoc_id`, `hocphi`, `create_at`, `update_at`) VALUES
(1, 11, 1, 2700000.00, '2025-06-18 14:34:00', '2025-06-18 14:34:00'),
(2, 17, 1, 2900000.00, '2025-06-18 14:59:37', '2025-06-18 15:26:39');

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
-- Table structure for table `giaovien`
--

CREATE TABLE `giaovien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `magiaovien` varchar(50) DEFAULT NULL,
  `chucdanh_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hocvi_id` bigint(20) UNSIGNED DEFAULT NULL,
  `chuyenmon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ten` varchar(255) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `gioitinh` enum('nam','nữ') DEFAULT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `stk` varchar(50) DEFAULT NULL,
  `trangthai` varchar(50) DEFAULT 'đang dạy',
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giaovien`
--

INSERT INTO `giaovien` (`id`, `user_id`, `magiaovien`, `chucdanh_id`, `hocvi_id`, `chuyenmon_id`, `ten`, `sdt`, `ngaysinh`, `gioitinh`, `diachi`, `stk`, `trangthai`, `create_at`, `update_at`) VALUES
(1, 1, 'GV01', NULL, NULL, NULL, 'cuong', '01909123912', NULL, 'nam', 'ct', NULL, 'đang dạy', NULL, NULL),
(2, 5, 'GV02', NULL, NULL, NULL, 'dat một lích', '01909123912', NULL, NULL, NULL, NULL, 'đang dạy', NULL, NULL),
(3, 11, 'GV03', 3, 6, 4, 'Cuóngadấd', '12313', '2025-06-19', 'nam', 'haf noioj', NULL, 'đang dạy', '2025-06-17 16:21:57', '2025-06-17 16:37:03'),
(4, 10, 'GV04', 2, 2, 3, 'Dat mootj lich', NULL, NULL, NULL, NULL, NULL, 'đang dạy', '2025-06-18 14:03:08', '2025-06-18 14:03:30');

-- --------------------------------------------------------

--
-- Table structure for table `hocvi`
--

CREATE TABLE `hocvi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenhocvi` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hocvi`
--

INSERT INTO `hocvi` (`id`, `tenhocvi`, `mota`, `create_at`, `update_at`) VALUES
(1, 'Cử nhân\r\n', NULL, NULL, NULL),
(2, 'Thạc sĩ\r\n', NULL, NULL, NULL),
(3, 'Tiến sĩ\r\n', NULL, NULL, NULL),
(4, 'TESOL\r\n', 'Rất phổ biến, dùng cho cả giáo viên Việt Nam và nước ngoài', NULL, NULL),
(5, 'CELTA', 'Của Đại học Cambridge, được đánh giá cao', NULL, NULL),
(6, 'TEFL', 'Được nhiều giáo viên nước ngoài sử dụng', NULL, NULL),
(7, 'DELTA', NULL, NULL, NULL),
(8, 'IELTS/TOEFL/TOEIC ', 'Không phải chứng chỉ giảng dạy nhưng thường dùng để đánh giá năng lực giáo viên', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hocvien`
--

CREATE TABLE `hocvien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `mahocvien` varchar(50) DEFAULT NULL,
  `ten` varchar(255) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `gioitinh` enum('nam','nữ') DEFAULT NULL,
  `ngaydangki` date DEFAULT NULL,
  `trangthai` varchar(100) DEFAULT 'đang học',
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hocvien`
--

INSERT INTO `hocvien` (`id`, `user_id`, `mahocvien`, `ten`, `sdt`, `diachi`, `ngaysinh`, `gioitinh`, `ngaydangki`, `trangthai`, `create_at`, `update_at`) VALUES
(1, 1, 'HV001', 'CƯờng', '12', '12', '2025-06-04', NULL, '2025-06-14', 'Đã tốt nghiệp', NULL, '2025-06-10 06:59:30'),
(4, 1, NULL, 'ada', '12', '12', '2025-06-04', NULL, '2025-06-14', 'Đang học', NULL, '2025-06-10 06:59:55'),
(8, 5, 'HV02', 'cuong', 'ad', 'asd', '2025-06-12', 'nam', '2025-06-14', 'Đang học', '2025-06-17 00:23:48', '2025-06-17 00:23:48'),
(9, 8, 'HV03', 'cuong', 'ad', 'asd', '2025-06-12', 'nam', '2025-06-14', 'Đang học', '2025-06-17 00:25:35', '2025-06-17 00:25:35'),
(10, 9, 'HV04', 'cuong', 'ad', 'asd', '2025-06-12', 'nữ', '2025-06-14', 'Đang học', '2025-06-17 00:27:04', '2025-06-17 00:27:04'),
(11, 10, 'HV05', 'cuong', 'ad', 'asd', '2025-06-12', 'nam', '2025-06-14', 'Đang học', '2025-06-17 00:27:36', '2025-06-17 00:27:36');

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
-- Table structure for table `khoahoc`
--

CREATE TABLE `khoahoc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma` varchar(20) NOT NULL,
  `ten` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `thoiluong` varchar(100) DEFAULT NULL,
  `sobuoi` varchar(10) DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `khoahoc`
--

INSERT INTO `khoahoc` (`id`, `ma`, `ten`, `mota`, `thoiluong`, `sobuoi`, `create_at`, `update_at`) VALUES
(1, 'KH01', 'cáp tóc', '<p>ađá</p>', '30', '12', NULL, '2025-06-09 09:32:47'),
(2, 'KH02', 'asdasd', '<p>asdsd</p>', '12', '12', NULL, '2025-06-10 07:31:06'),
(8, 'KH006', 'áđasdầ', 'đâs', NULL, NULL, NULL, NULL),
(9, 'KH007', 'Anh văn tăng cường', '<p><strong>oke</strong> adjakđá</p>', '12 tuần', '20', '2025-06-09 08:51:39', '2025-06-09 08:51:39'),
(11, 'KH06', 'Anh văn tăng cườngg', '<p>áđá<strong>sdâđa</strong></p>', '12 tuần', '20', '2025-06-09 08:57:48', '2025-06-09 08:57:48'),
(12, 'KH07', 'Anh văn tăng cường 2', '<p>sadsad ádád <u>áđâsd</u></p>', '12 tuần', '22', '2025-06-09 09:00:02', '2025-06-09 09:00:02'),
(13, 'KH08', 'Anh văn tăng cường 22', '<p><br></p>', '12 tuần', '22', '2025-06-09 09:04:03', '2025-06-09 09:04:03'),
(14, 'KH09', 'Anh văn tăng cường 222', '<p>ád</p>', '12 tuần', '22', '2025-06-09 09:05:02', '2025-06-09 09:05:02'),
(15, 'KH10', 'Anh văn tăng cường 222s', '<p>ád</p>', '12 tuần', '22', '2025-06-09 09:05:57', '2025-06-09 09:05:57'),
(16, 'KH11', 'Anh văn tăng cường 2223s', '<p><strong>adấđa</strong></p>', '12 tuần', '22', '2025-06-09 13:17:53', '2025-06-09 13:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `kynang`
--

CREATE TABLE `kynang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma` varchar(20) NOT NULL,
  `ten` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kynang`
--

INSERT INTO `kynang` (`id`, `ma`, `ten`, `mota`, `create_at`, `update_at`) VALUES
(1, 'Kn01', 'nghe', 'ád', NULL, NULL),
(2, 'KN02', 'nói', NULL, NULL, NULL),
(3, 'Kn03', 'dsa', NULL, NULL, NULL),
(4, 'KN04', 'ád', NULL, NULL, NULL),
(7, 'KN07', 'adsad', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lophoc`
--

CREATE TABLE `lophoc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `khoahoc_id` bigint(20) UNSIGNED NOT NULL,
  `trinhdo_id` bigint(20) UNSIGNED NOT NULL,
  `giaovien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tenlophoc` varchar(255) NOT NULL,
  `malophoc` varchar(50) DEFAULT NULL,
  `ngaybatdau` date NOT NULL,
  `ngayketthuc` date NOT NULL,
  `soluonghocvientoida` int(11) DEFAULT NULL,
  `soluonghocvienhientai` int(11) DEFAULT 0,
  `trangthai` enum('sap_khai_giang','dang_hoat_dong','da_ket_thuc','da_huy') DEFAULT 'sap_khai_giang',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lichoc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lophoc`
--

INSERT INTO `lophoc` (`id`, `khoahoc_id`, `trinhdo_id`, `giaovien_id`, `tenlophoc`, `malophoc`, `ngaybatdau`, `ngayketthuc`, `soluonghocvientoida`, `soluonghocvienhientai`, `trangthai`, `created_at`, `updated_at`, `lichoc`) VALUES
(2, 9, 3, 1, 'A1 sáng', 'LH02', '2025-06-05', '2025-06-27', 30, 0, 'sap_khai_giang', NULL, NULL, NULL),
(3, 8, 5, 1, 'B1( tối)', 'LH03', '2025-06-12', '2025-06-20', 20, 0, 'sap_khai_giang', NULL, '2025-06-15 10:15:03', NULL),
(4, 9, 3, 1, 'ada', 'KH04', '2025-06-05', '2025-06-21', NULL, 0, 'sap_khai_giang', NULL, '2025-06-12 13:42:56', NULL),
(8, 8, 5, NULL, 'ad', 'LH08', '2025-06-14', '2025-06-14', NULL, 0, 'sap_khai_giang', '2025-06-11 05:32:25', '2025-06-11 05:32:25', NULL),
(9, 9, 5, NULL, 'adem', 'LH09', '2025-06-14', '2025-06-14', NULL, 0, 'sap_khai_giang', '2025-06-11 05:32:43', '2025-06-11 05:32:43', NULL),
(10, 11, 6, 1, 'oke', 'l', '2025-06-28', '2025-06-28', NULL, 0, 'sap_khai_giang', '2025-06-11 06:13:37', '2025-06-12 18:23:03', NULL),
(11, 2, 3, 2, 'ád', 'ads', '2025-06-27', '2025-06-28', NULL, 0, 'sap_khai_giang', '2025-06-12 09:59:31', '2025-06-12 20:11:25', NULL),
(12, 9, 5, NULL, 'áda', 'ád', '2025-06-07', '2025-06-19', NULL, 0, 'sap_khai_giang', '2025-06-17 15:11:31', '2025-06-17 15:11:31', NULL),
(13, 8, 6, NULL, 'ágdgdlfkglkgldg', 'ghfg', '2025-06-28', '2025-07-05', NULL, 0, 'sap_khai_giang', '2025-06-17 15:20:21', '2025-06-17 15:20:21', NULL),
(14, 12, 9, NULL, 'apoptpttpetp', 's', '2025-07-05', '2025-08-01', NULL, 0, 'sap_khai_giang', '2025-06-17 15:21:30', '2025-06-17 15:21:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lophoc_hocvien`
--

CREATE TABLE `lophoc_hocvien` (
  `lophoc_id` bigint(20) UNSIGNED NOT NULL,
  `hocvien_id` bigint(20) UNSIGNED NOT NULL,
  `ngaydangky` date NOT NULL,
  `trangthai` varchar(50) DEFAULT 'da_dang_ky',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lophoc_hocvien`
--

INSERT INTO `lophoc_hocvien` (`lophoc_id`, `hocvien_id`, `ngaydangky`, `trangthai`, `created_at`, `updated_at`) VALUES
(2, 1, '2025-06-26', 'da_dang_ky', NULL, NULL),
(2, 4, '2025-06-13', 'da_dang_ky', '2025-06-12 20:12:26', '2025-06-12 20:12:26'),
(3, 1, '2025-06-15', 'da_dang_ky', '2025-06-15 10:14:20', '2025-06-15 10:14:20'),
(3, 4, '2025-06-15', 'da_dang_ky', '2025-06-15 10:14:20', '2025-06-15 10:14:20'),
(4, 1, '2025-06-16', 'da_dang_ky', '2025-06-15 17:09:15', '2025-06-15 17:09:15'),
(4, 4, '2025-06-16', 'da_dang_ky', '2025-06-15 17:09:15', '2025-06-15 17:09:15'),
(10, 1, '2025-06-17', 'da_dang_ky', '2025-06-17 00:27:56', '2025-06-17 00:27:56'),
(10, 4, '2025-06-17', 'da_dang_ky', '2025-06-17 00:27:56', '2025-06-17 00:27:56'),
(10, 8, '2025-06-17', 'da_dang_ky', '2025-06-17 00:27:56', '2025-06-17 00:27:56'),
(10, 9, '2025-06-17', 'da_dang_ky', '2025-06-17 00:27:56', '2025-06-17 00:27:56'),
(10, 10, '2025-06-17', 'da_dang_ky', '2025-06-17 00:27:56', '2025-06-17 00:27:56'),
(10, 11, '2025-06-17', 'da_dang_ky', '2025-06-17 00:27:56', '2025-06-17 00:27:56'),
(11, 1, '2025-06-13', 'da_dang_ky', '2025-06-12 19:48:16', '2025-06-12 19:48:16'),
(11, 4, '2025-06-13', 'da_dang_ky', '2025-06-12 19:48:16', '2025-06-12 19:48:16'),
(13, 1, '2025-06-17', 'da_dang_ky', '2025-06-17 16:50:03', '2025-06-17 16:50:03'),
(13, 4, '2025-06-17', 'da_dang_ky', '2025-06-17 16:50:03', '2025-06-17 16:50:03');

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
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_06_08_020824_create_sessions_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `namhoc`
--

CREATE TABLE `namhoc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nam` varchar(50) NOT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `namhoc`
--

INSERT INTO `namhoc` (`id`, `nam`, `create_at`, `update_at`) VALUES
(1, '2024 - 2025', NULL, NULL),
(2, '2025 - 2026', NULL, NULL),
(3, '2026 - 2027', NULL, NULL),
(4, '2027 -2028', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nhahoc`
--

CREATE TABLE `nhahoc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma` varchar(50) NOT NULL,
  `coso_id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(255) NOT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nhahoc`
--

INSERT INTO `nhahoc` (`id`, `ma`, `coso_id`, `ten`, `diachi`, `mota`, `create_at`, `update_at`) VALUES
(1, '', 1, 'Nhaf hocj A1', NULL, NULL, NULL, NULL),
(2, '', 1, 'nhà học A2', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `manhanvien` varchar(50) DEFAULT NULL,
  `chucdanh_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ten` varchar(255) NOT NULL,
  `sdt` varchar(50) DEFAULT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `gioitinh` enum('nam','nữ') DEFAULT NULL,
  `trangthai` varchar(100) DEFAULT 'dang lam viec',
  `creat_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieuthu`
--

CREATE TABLE `phieuthu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hocvien_id` bigint(20) UNSIGNED NOT NULL,
  `lophoc_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sotien` decimal(10,2) NOT NULL,
  `ngaythanhtoan` date NOT NULL,
  `phuongthuc` varchar(100) DEFAULT NULL,
  `ghichu` text DEFAULT NULL,
  `nhanvien_id` bigint(20) UNSIGNED DEFAULT NULL,
  `trangthai` enum('da_thanh_toan','cho_thanh_toan','da_huy') DEFAULT 'da_thanh_toan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phonghoc`
--

CREATE TABLE `phonghoc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tang_id` bigint(20) UNSIGNED NOT NULL,
  `tenphong` varchar(255) DEFAULT NULL,
  `succhua` int(11) DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phonghoc`
--

INSERT INTO `phonghoc` (`id`, `tang_id`, `tenphong`, `succhua`, `create_at`, `update_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL),
(4, 1, 'oke', 31, NULL, '2025-06-17 00:15:29'),
(5, 1, 'Phogf A1', 30, '2025-06-17 00:07:25', '2025-06-17 00:07:25'),
(6, 2, '202', 30, '2025-06-18 15:30:24', '2025-06-18 15:30:24');

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
('EjcNFkYhi15NeyLsPAgKgHpYuiWvCVZvJftCrYuh', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYUVIZTQwaFk4bjRCQTRTWlM1YzJ6ZkJGaThBM2VEWmZiRU94NUlGdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9raG9haG9jIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9', 1750338437);

-- --------------------------------------------------------

--
-- Table structure for table `tang`
--

CREATE TABLE `tang` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nhahoc_id` bigint(20) UNSIGNED NOT NULL,
  `ten` varchar(50) NOT NULL,
  `mota` text DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tang`
--

INSERT INTO `tang` (`id`, `nhahoc_id`, `ten`, `mota`, `create_at`, `update_at`) VALUES
(1, 1, 'tầng 1', NULL, NULL, NULL),
(2, 2, 'tầng 1', 'tầng 1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `thoikhoabieu`
--

CREATE TABLE `thoikhoabieu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lophoc_id` bigint(20) UNSIGNED NOT NULL,
  `giaovien_id` bigint(20) UNSIGNED NOT NULL,
  `phonghoc_id` bigint(20) UNSIGNED NOT NULL,
  `thu_id` bigint(20) UNSIGNED NOT NULL,
  `cahoc_id` bigint(20) UNSIGNED NOT NULL,
  `kynang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngayhoc` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thoikhoabieu`
--

INSERT INTO `thoikhoabieu` (`id`, `lophoc_id`, `giaovien_id`, `phonghoc_id`, `thu_id`, `cahoc_id`, `kynang_id`, `ngayhoc`, `created_at`, `updated_at`) VALUES
(3, 2, 1, 4, 1, 1, 2, '2025-06-12', NULL, NULL),
(4, 10, 1, 4, 3, 1, 2, '2025-06-13', NULL, NULL),
(5, 14, 1, 4, 3, 2, 2, NULL, '2025-06-17 15:34:57', '2025-06-17 15:34:57'),
(6, 14, 2, 4, 2, 2, 1, NULL, '2025-06-17 15:37:33', '2025-06-17 15:37:33'),
(7, 13, 3, 5, 3, 1, 1, NULL, '2025-06-18 14:07:03', '2025-06-18 14:07:03');

-- --------------------------------------------------------

--
-- Table structure for table `thongbao`
--

CREATE TABLE `thongbao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tieude` varchar(255) NOT NULL,
  `noidung` text NOT NULL,
  `nguoigui_id` bigint(20) UNSIGNED NOT NULL,
  `loaidoituongnhan` enum('tat_ca_hoc_vien','lop_hoc','hoc_vien_cu_the') NOT NULL,
  `doituongnhan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ngaydang` timestamp NULL DEFAULT NULL,
  `trangthai` enum('nhap','da_gui') DEFAULT 'nhap',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thu`
--

CREATE TABLE `thu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tenthu` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `thu`
--

INSERT INTO `thu` (`id`, `tenthu`, `created_at`, `updated_at`) VALUES
(1, 'Thứ 2', NULL, NULL),
(2, 'Thứ 3', NULL, NULL),
(3, 'Thứ 4', NULL, NULL),
(4, 'Thứ 5', NULL, NULL),
(5, 'Thứ 6', NULL, NULL),
(6, 'Thứ 7', NULL, NULL),
(7, 'Chủ nhật', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tintuc`
--

CREATE TABLE `tintuc` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tieude` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `noidung` text NOT NULL,
  `ngaydang` timestamp NULL DEFAULT NULL,
  `hinhanh` varchar(255) DEFAULT NULL,
  `tacgia_id` bigint(20) UNSIGNED DEFAULT NULL,
  `trang_thai` enum('nhap','da_dang','luu_tru') DEFAULT 'nhap',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trinhdo`
--

CREATE TABLE `trinhdo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ma` varchar(50) NOT NULL,
  `ten` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL,
  `kynang_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trinhdo`
--

INSERT INTO `trinhdo` (`id`, `ma`, `ten`, `mota`, `create_at`, `update_at`, `kynang_id`) VALUES
(3, 'TD03', 'B2', '<p>oke</p>', NULL, '2025-06-11 05:13:00', NULL),
(5, 'TD05', 'C3', '<p>fad</p>', NULL, '2025-06-10 00:40:59', NULL),
(6, 'TD06', 'A2', '<p>ád</p>', NULL, '2025-06-18 15:20:53', 2),
(7, 'TD07', 'A4', '<p>ad</p>', '2025-06-09 13:25:43', '2025-06-09 13:25:43', NULL),
(9, 'TD08', 'Anh văn tăng cường 35', '<p>ad</p>', '2025-06-10 00:47:09', '2025-06-10 00:50:23', 2),
(11, 'TD09', 'B15', '<p>ád</p>', '2025-06-18 14:34:00', '2025-06-18 14:51:44', NULL),
(17, 'TD14', 'Anh văn tăng cường', '<p>oke</p><p><br></p>', '2025-06-18 14:59:37', '2025-06-18 15:26:39', 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_xacthuc` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','nhanvien','hocvien','giaovien') DEFAULT NULL,
  `create_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `email_xacthuc`, `password`, `role`, `create_at`, `update_at`) VALUES
(1, 'thanhcuongstudentt@gmail.com', NULL, '$2y$10$OmZopqaw31ENFFDIDOEbBeqVzKzc2O.GhzEnBm./JRLT0NzTc1xTC', 'hocvien', NULL, '2025-06-10 07:05:18'),
(2, 'cuong@gmail.com', NULL, '$2y$10$umH/nMmbKCK5qvTNeAd06.RvT3XsWqf/wRtPNqm2/Wo0eanfVslS6', 'hocvien', NULL, NULL),
(3, 'td@gmail.com', NULL, '$2y$10$A7nqevAZj6N78KSqz7d.0uyagWK1e5AUee5M5HXn9QeRjlO8ZZ6wO', '', NULL, NULL),
(4, 'td@gmail.com', NULL, '$2y$10$A7nqevAZj6N78KSqz7d.0uyagWK1e5AUee5M5HXn9QeRjlO8ZZ6wO', 'hocvien', NULL, NULL),
(5, 'dat123@gmail.com', NULL, NULL, NULL, '2025-06-10 06:20:33', '2025-06-10 06:20:33'),
(6, 'Cuongb21@gmail.com', NULL, '$2y$12$dsZZeZaQUK3N.sze8rPP2OrrzDaLWyLl4DEd0z1ywgcrWytnnfI/C', 'admin', NULL, '2025-06-15 16:48:18'),
(7, 'dats123@gmail.com', NULL, NULL, NULL, '2025-06-17 00:18:18', '2025-06-17 00:18:18'),
(8, 'dat12s3@gmail.com', NULL, '$2y$12$H/YqQC3ComScKWsvKj5PZu7V2M6iiUjzyM6s2z59pf2MpFQsCEsrK', NULL, '2025-06-17 00:25:35', '2025-06-17 00:25:35'),
(9, 'dat12ss3@gmail.com', NULL, '$2y$12$owGLST8N2OgsIMxIhwDp1.gGDkAspd5O5a/1pai/ug4jMnPeUdHGK', NULL, '2025-06-17 00:27:04', '2025-06-17 00:27:04'),
(10, 'dat12sss3@gmail.com', NULL, '$2y$12$LEEmuH9l610/H6MEL17WI.trUQ6sTrksCIGrKFsUmtdHtcPGJN1y6', NULL, '2025-06-17 00:27:36', '2025-06-17 00:27:36'),
(11, 'cuong2@gmail.com', NULL, '$2y$12$2lzXHX6draqw8Y8Cy.zBUec.M/fodVWifBAoWtMPxN0WvKFpWn0vK', 'giaovien', '2025-06-17 16:21:57', '2025-06-17 16:21:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `binhluan`
--
ALTER TABLE `binhluan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_binh_luan_nguoi_dung_id` (`user_id`),
  ADD KEY `fk_binh_luan_cha_id` (`binhluancha_id`);

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
-- Indexes for table `cahoc`
--
ALTER TABLE `cahoc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenca` (`tenca`);

--
-- Indexes for table `chucdanh`
--
ALTER TABLE `chucdanh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten` (`ten`);

--
-- Indexes for table `chuyenmon`
--
ALTER TABLE `chuyenmon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenchuyenmon` (`tenchuyenmon`);

--
-- Indexes for table `coso`
--
ALTER TABLE `coso`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diemdanh`
--
ALTER TABLE `diemdanh`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_diem_danh_lop_hoc_id` (`lophoc_id`),
  ADD KEY `fk_diem_danh_hoc_vien_id` (`hocvien_id`),
  ADD KEY `fk_diem_danh_giao_vien_id` (`giaovien_id`),
  ADD KEY `fk_diem_danh_tkb_id` (`thoikhoabieu_id`);

--
-- Indexes for table `dongia`
--
ALTER TABLE `dongia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dg_td` (`trinhdo_id`),
  ADD KEY `fk_dg_nh` (`namhoc_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `giaovien`
--
ALTER TABLE `giaovien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `magiaovien` (`magiaovien`),
  ADD KEY `fk_giaovien_userid` (`user_id`),
  ADD KEY `fk_giaovien_chucdanhid` (`chucdanh_id`),
  ADD KEY `fk_giaovien_hocviid` (`hocvi_id`),
  ADD KEY `fk_giaovien_chuyenmonid` (`chuyenmon_id`);

--
-- Indexes for table `hocvi`
--
ALTER TABLE `hocvi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenhocvi` (`tenhocvi`);

--
-- Indexes for table `hocvien`
--
ALTER TABLE `hocvien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mahocvien` (`mahocvien`),
  ADD KEY `fk_hocvien_userid` (`user_id`);

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
-- Indexes for table `khoahoc`
--
ALTER TABLE `khoahoc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma` (`ma`),
  ADD UNIQUE KEY `ten` (`ten`);

--
-- Indexes for table `kynang`
--
ALTER TABLE `kynang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma` (`ma`),
  ADD UNIQUE KEY `ten` (`ten`);

--
-- Indexes for table `lophoc`
--
ALTER TABLE `lophoc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `malophoc` (`malophoc`),
  ADD KEY `fk_lop_hoc_khoa_hoc_id` (`khoahoc_id`),
  ADD KEY `fk_lop_hoc_trinh_do_id` (`trinhdo_id`),
  ADD KEY `fk_lop_hoc_giao_vien_id` (`giaovien_id`);

--
-- Indexes for table `lophoc_hocvien`
--
ALTER TABLE `lophoc_hocvien`
  ADD PRIMARY KEY (`lophoc_id`,`hocvien_id`),
  ADD KEY `fk_lhhv_hoc_vien_id` (`hocvien_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `namhoc`
--
ALTER TABLE `namhoc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nam` (`nam`);

--
-- Indexes for table `nhahoc`
--
ALTER TABLE `nhahoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_nhahoc_cosoid` (`coso_id`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `manhanvien` (`manhanvien`),
  ADD KEY `fk_nhanvien_userid` (`user_id`),
  ADD KEY `fk_nhanvien_chcudanhid` (`chucdanh_id`);

--
-- Indexes for table `phieuthu`
--
ALTER TABLE `phieuthu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_phieu_thu_hoc_vien_id` (`hocvien_id`),
  ADD KEY `fk_phieu_thu_lop_hoc_id` (`lophoc_id`),
  ADD KEY `fk_phieu_thu_nhan_vien_id` (`nhanvien_id`);

--
-- Indexes for table `phonghoc`
--
ALTER TABLE `phonghoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_phong_tangid` (`tang_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tang`
--
ALTER TABLE `tang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tang_nhahocid` (`nhahoc_id`);

--
-- Indexes for table `thoikhoabieu`
--
ALTER TABLE `thoikhoabieu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lophoc_id` (`lophoc_id`,`thu_id`,`cahoc_id`),
  ADD KEY `fk_tkb_giao_vien_id` (`giaovien_id`),
  ADD KEY `fk_tkb_phong_id` (`phonghoc_id`),
  ADD KEY `fk_tkb_thu_id` (`thu_id`),
  ADD KEY `fk_tkb_ca_hoc_id` (`cahoc_id`),
  ADD KEY `fk_tkb_ky_nang_id` (`kynang_id`);

--
-- Indexes for table `thongbao`
--
ALTER TABLE `thongbao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_thong_bao_nguoi_gui_id` (`nguoigui_id`);

--
-- Indexes for table `thu`
--
ALTER TABLE `thu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tenthu` (`tenthu`);

--
-- Indexes for table `tintuc`
--
ALTER TABLE `tintuc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_tin_tuc_tac_gia_id` (`tacgia_id`);

--
-- Indexes for table `trinhdo`
--
ALTER TABLE `trinhdo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ma` (`ma`),
  ADD KEY `fk_trinhdo_kynang` (`kynang_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `binhluan`
--
ALTER TABLE `binhluan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cahoc`
--
ALTER TABLE `cahoc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `chucdanh`
--
ALTER TABLE `chucdanh`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chuyenmon`
--
ALTER TABLE `chuyenmon`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coso`
--
ALTER TABLE `coso`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `diemdanh`
--
ALTER TABLE `diemdanh`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dongia`
--
ALTER TABLE `dongia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `giaovien`
--
ALTER TABLE `giaovien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hocvi`
--
ALTER TABLE `hocvi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hocvien`
--
ALTER TABLE `hocvien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `khoahoc`
--
ALTER TABLE `khoahoc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `kynang`
--
ALTER TABLE `kynang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lophoc`
--
ALTER TABLE `lophoc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `namhoc`
--
ALTER TABLE `namhoc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nhahoc`
--
ALTER TABLE `nhahoc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phieuthu`
--
ALTER TABLE `phieuthu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phonghoc`
--
ALTER TABLE `phonghoc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tang`
--
ALTER TABLE `tang`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `thoikhoabieu`
--
ALTER TABLE `thoikhoabieu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `thongbao`
--
ALTER TABLE `thongbao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thu`
--
ALTER TABLE `thu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tintuc`
--
ALTER TABLE `tintuc`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trinhdo`
--
ALTER TABLE `trinhdo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `binhluan`
--
ALTER TABLE `binhluan`
  ADD CONSTRAINT `fk_binh_luan_cha_id` FOREIGN KEY (`binhluancha_id`) REFERENCES `binhluan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_binh_luan_nguoi_dung_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `diemdanh`
--
ALTER TABLE `diemdanh`
  ADD CONSTRAINT `fk_diem_danh_giao_vien_id` FOREIGN KEY (`giaovien_id`) REFERENCES `giaovien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_diem_danh_hoc_vien_id` FOREIGN KEY (`hocvien_id`) REFERENCES `hocvien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_diem_danh_lop_hoc_id` FOREIGN KEY (`lophoc_id`) REFERENCES `lophoc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_diem_danh_tkb_id` FOREIGN KEY (`thoikhoabieu_id`) REFERENCES `thoikhoabieu` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dongia`
--
ALTER TABLE `dongia`
  ADD CONSTRAINT `fk_dg_nh` FOREIGN KEY (`namhoc_id`) REFERENCES `namhoc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dg_td` FOREIGN KEY (`trinhdo_id`) REFERENCES `trinhdo` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `giaovien`
--
ALTER TABLE `giaovien`
  ADD CONSTRAINT `fk_giaovien_chucdanhid` FOREIGN KEY (`chucdanh_id`) REFERENCES `chucdanh` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_giaovien_chuyenmonid` FOREIGN KEY (`chuyenmon_id`) REFERENCES `chuyenmon` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_giaovien_hocviid` FOREIGN KEY (`hocvi_id`) REFERENCES `hocvi` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_giaovien_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hocvien`
--
ALTER TABLE `hocvien`
  ADD CONSTRAINT `fk_hocvien_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lophoc`
--
ALTER TABLE `lophoc`
  ADD CONSTRAINT `fk_lop_hoc_giao_vien_id` FOREIGN KEY (`giaovien_id`) REFERENCES `giaovien` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_lop_hoc_khoa_hoc_id` FOREIGN KEY (`khoahoc_id`) REFERENCES `khoahoc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lop_hoc_trinh_do_id` FOREIGN KEY (`trinhdo_id`) REFERENCES `trinhdo` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lophoc_hocvien`
--
ALTER TABLE `lophoc_hocvien`
  ADD CONSTRAINT `fk_lhhv_hoc_vien_id` FOREIGN KEY (`hocvien_id`) REFERENCES `hocvien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lhhv_lop_hoc_id` FOREIGN KEY (`lophoc_id`) REFERENCES `lophoc` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nhahoc`
--
ALTER TABLE `nhahoc`
  ADD CONSTRAINT `fk_nhahoc_cosoid` FOREIGN KEY (`coso_id`) REFERENCES `coso` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `fk_nhanvien_chcudanhid` FOREIGN KEY (`chucdanh_id`) REFERENCES `chucdanh` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_nhanvien_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phieuthu`
--
ALTER TABLE `phieuthu`
  ADD CONSTRAINT `fk_phieu_thu_hoc_vien_id` FOREIGN KEY (`hocvien_id`) REFERENCES `hocvien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_phieu_thu_lop_hoc_id` FOREIGN KEY (`lophoc_id`) REFERENCES `lophoc` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_phieu_thu_nhan_vien_id` FOREIGN KEY (`nhanvien_id`) REFERENCES `nhanvien` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `phonghoc`
--
ALTER TABLE `phonghoc`
  ADD CONSTRAINT `fk_phong_tangid` FOREIGN KEY (`tang_id`) REFERENCES `tang` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tang`
--
ALTER TABLE `tang`
  ADD CONSTRAINT `fk_tang_nhahocid` FOREIGN KEY (`nhahoc_id`) REFERENCES `nhahoc` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `thoikhoabieu`
--
ALTER TABLE `thoikhoabieu`
  ADD CONSTRAINT `fk_tkb_ca_hoc_id` FOREIGN KEY (`cahoc_id`) REFERENCES `cahoc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tkb_giao_vien_id` FOREIGN KEY (`giaovien_id`) REFERENCES `giaovien` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tkb_ky_nang_id` FOREIGN KEY (`kynang_id`) REFERENCES `kynang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_tkb_lop_hoc_id` FOREIGN KEY (`lophoc_id`) REFERENCES `lophoc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tkb_phong_id` FOREIGN KEY (`phonghoc_id`) REFERENCES `phonghoc` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_tkb_thu_id` FOREIGN KEY (`thu_id`) REFERENCES `thu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `thongbao`
--
ALTER TABLE `thongbao`
  ADD CONSTRAINT `fk_thong_bao_nguoi_gui_id` FOREIGN KEY (`nguoigui_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tintuc`
--
ALTER TABLE `tintuc`
  ADD CONSTRAINT `fk_tin_tuc_tac_gia_id` FOREIGN KEY (`tacgia_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `trinhdo`
--
ALTER TABLE `trinhdo`
  ADD CONSTRAINT `fk_trinhdo_kynang` FOREIGN KEY (`kynang_id`) REFERENCES `kynang` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
