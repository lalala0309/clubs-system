-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 22, 2026 lúc 03:19 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `clubs-system`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `groundID` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('approved','cancelled') DEFAULT 'approved'
) ;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `userID`, `booking_date`, `groundID`, `start_time`, `end_time`, `status`) VALUES
(107, 3, '2026-02-10', 16, '07:00:00', '08:00:00', 'approved'),
(108, 3, '2026-02-11', 16, '06:00:00', '07:00:00', 'approved'),
(109, 3, '2026-02-11', 16, '07:00:00', '08:00:00', 'approved'),
(110, 3, '2026-02-11', 16, '08:00:00', '09:00:00', 'approved'),
(111, 3, '2026-02-17', 16, '06:00:00', '07:00:00', 'approved'),
(112, 3, '2026-02-17', 16, '07:00:00', '08:00:00', 'approved'),
(113, 3, '2026-02-17', 16, '08:00:00', '09:00:00', 'approved'),
(114, 3, '2026-02-19', 17, '13:00:00', '14:00:00', 'approved'),
(115, 3, '2026-02-09', 26, '06:00:00', '07:00:00', 'approved'),
(116, 3, '2026-02-09', 26, '07:00:00', '08:00:00', 'approved'),
(117, 3, '2026-02-09', 26, '08:00:00', '09:00:00', 'approved'),
(118, 3, '2026-02-09', 26, '09:00:00', '10:00:00', 'approved'),
(119, 2, '2026-02-09', 26, '13:00:00', '14:00:00', 'approved'),
(120, 2, '2026-02-09', 26, '14:00:00', '15:00:00', 'approved'),
(121, 2, '2026-02-09', 26, '15:00:00', '16:00:00', 'approved'),
(122, 2, '2026-02-09', 26, '16:00:00', '17:00:00', 'approved'),
(123, 2, '2026-02-10', 26, '13:00:00', '14:00:00', 'approved'),
(124, 2, '2026-02-08', 26, '19:00:00', '20:00:00', 'approved'),
(125, 3, '2026-02-13', 26, '15:00:00', '16:00:00', 'approved'),
(126, 3, '2026-02-13', 26, '16:00:00', '17:00:00', 'approved'),
(127, 3, '2026-02-17', 26, '07:00:00', '08:00:00', 'approved'),
(128, 3, '2026-02-18', 26, '07:00:00', '08:00:00', 'approved'),
(129, 3, '2026-02-19', 26, '08:00:00', '09:00:00', 'approved'),
(130, 3, '2026-02-19', 26, '07:00:00', '08:00:00', 'approved'),
(131, 3, '2026-02-18', 26, '08:00:00', '09:00:00', 'approved'),
(132, 3, '2026-02-17', 26, '08:00:00', '09:00:00', 'approved'),
(133, 3, '2026-02-16', 16, '06:00:00', '07:00:00', 'approved'),
(134, 3, '2026-02-24', 16, '08:00:00', '09:00:00', 'approved'),
(135, 1, '2026-02-20', 26, '07:00:00', '08:00:00', 'cancelled'),
(136, 1, '2026-02-21', 26, '08:00:00', '09:00:00', 'cancelled'),
(137, 1, '2026-02-21', 26, '09:00:00', '10:00:00', 'cancelled');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `clubs`
--

CREATE TABLE `clubs` (
  `clubID` int(11) NOT NULL,
  `club_name` varchar(100) NOT NULL,
  `founded_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `clubs`
--

INSERT INTO `clubs` (`clubID`, `club_name`, `founded_date`) VALUES
(1, 'Câu lạc bộ Bóng đá', '2020-09-01'),
(2, 'Câu lạc bộ Bóng bàn', '2021-03-15'),
(3, 'Câu lạc bộ Cầu lông', '2023-01-01'),
(6, 'Câu lạc bộ Rồng rắn lên mây', '2026-01-27'),
(7, 'Câu lạc bộ Cá sấu lên bờ', '2026-01-27'),
(8, 'Câu lạc bộ bóng đá Khoa Y', '2026-01-27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `club_members`
--

CREATE TABLE `club_members` (
  `userID` int(11) NOT NULL,
  `clubID` int(11) NOT NULL,
  `join_date` date DEFAULT NULL,
  `fee_paid_date` date DEFAULT NULL,
  `fee_expire_date` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `request_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `club_members`
--

INSERT INTO `club_members` (`userID`, `clubID`, `join_date`, `fee_paid_date`, `fee_expire_date`, `status`, `request_date`) VALUES
(1, 1, '2026-01-29', NULL, NULL, 1, '2026-01-27'),
(1, 2, '2026-01-27', NULL, NULL, 1, '2026-01-27'),
(1, 6, '2026-01-27', NULL, NULL, 1, '2026-01-27'),
(2, 2, '2026-01-27', NULL, NULL, 1, '2026-01-27'),
(2, 6, '2026-01-27', NULL, NULL, 1, '2026-01-27'),
(3, 1, '2026-02-04', NULL, NULL, 1, '2026-02-04'),
(3, 2, '2026-02-04', NULL, NULL, 1, '2026-02-04'),
(3, 3, '2026-01-27', NULL, NULL, 1, NULL),
(3, 6, '2026-01-27', NULL, NULL, 1, '2026-01-27'),
(3, 7, '2026-02-04', NULL, NULL, 1, '2026-02-04'),
(3, 8, '2026-01-27', NULL, NULL, 1, '2026-01-27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `club_sports`
--

CREATE TABLE `club_sports` (
  `id` int(11) NOT NULL,
  `clubID` int(11) NOT NULL,
  `sportID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `club_sports`
--

INSERT INTO `club_sports` (`id`, `clubID`, `sportID`) VALUES
(3, 1, 1),
(4, 2, 3),
(5, 3, 2),
(8, 6, 12),
(9, 7, 7),
(10, 8, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `grounds`
--

CREATE TABLE `grounds` (
  `groundID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `sportID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `grounds`
--

INSERT INTO `grounds` (`groundID`, `name`, `location`, `status`, `sportID`) VALUES
(16, 'Sân bóng đá A1', 'Khu thể thao trung tâm', 1, 1),
(17, 'Sân bóng đá A2', 'Khu thể thao trung tâm', 1, 1),
(18, 'Sân bóng đá A3', 'Khu thể thao trung tâm', 1, 1),
(19, 'Sân bóng đá A4', 'Khu thể thao trung tâm', 1, 1),
(20, 'Sân bóng đá A5', 'Khu thể thao trung tâm', 1, 1),
(21, 'Sân cầu lông B1', 'Nhà thi đấu số 1', 1, 2),
(22, 'Sân cầu lông B2', 'Nhà thi đấu số 1', 1, 2),
(23, 'Sân cầu lông B3', 'Nhà thi đấu số 1', 1, 2),
(24, 'Sân cầu lông B4', 'Nhà thi đấu số 1', 1, 2),
(25, 'Sân cầu lông B5', 'Nhà thi đấu số 1', 1, 2),
(26, 'Sân bóng bàn C1', 'Nhà đa năng', 1, 3),
(27, 'Sân bóng bàn C2', 'Nhà đa năng', 1, 3),
(28, 'Sân bóng bàn C3', 'Nhà đa năng', 1, 3),
(29, 'Sân bóng bàn C4', 'Nhà đa năng', 1, 3),
(30, 'Sân bóng bàn C5', 'Nhà đa năng', 1, 3),
(31, 'Sân bóng bàn C6', 'Nhà đa năng', 1, 3),
(32, 'Sân số 7', 'hihi', 1, 1),
(33, 'Sân số 7', 'Nhà thi đấu', 1, 3),
(34, 'Sân số 8', 'Nhà thi đấu', 1, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ground_locks`
--

CREATE TABLE `ground_locks` (
  `id` int(11) NOT NULL,
  `groundID` int(11) NOT NULL,
  `lock_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ground_locks`
--

INSERT INTO `ground_locks` (`id`, `groundID`, `lock_date`, `start_time`, `end_time`, `note`, `created_at`) VALUES
(33, 26, '2026-02-10', '14:00:00', '23:59:59', NULL, '2026-02-09 14:20:55'),
(34, 26, '2026-02-11', '00:00:00', '23:59:59', NULL, '2026-02-09 14:20:55'),
(35, 26, '2026-02-12', '00:00:00', '23:59:59', NULL, '2026-02-09 14:20:55'),
(36, 26, '2026-02-13', '00:00:00', '15:00:00', NULL, '2026-02-09 14:20:55'),
(37, 27, '2026-02-10', '14:00:00', '23:59:59', NULL, '2026-02-09 14:20:55'),
(38, 27, '2026-02-11', '00:00:00', '23:59:59', NULL, '2026-02-09 14:20:55'),
(39, 27, '2026-02-12', '00:00:00', '23:59:59', NULL, '2026-02-09 14:20:55'),
(40, 27, '2026-02-13', '00:00:00', '15:00:00', NULL, '2026-02-09 14:20:55'),
(41, 28, '2026-02-10', '14:00:00', '23:59:59', NULL, '2026-02-09 14:20:55'),
(42, 28, '2026-02-11', '00:00:00', '23:59:59', NULL, '2026-02-09 14:20:55'),
(43, 28, '2026-02-12', '00:00:00', '23:59:59', NULL, '2026-02-09 14:20:55'),
(44, 28, '2026-02-13', '00:00:00', '15:00:00', NULL, '2026-02-09 14:20:55'),
(45, 26, '2026-02-18', '14:30:00', '03:30:00', NULL, '2026-02-18 14:30:54'),
(46, 26, '2026-02-18', '14:37:00', '23:59:59', NULL, '2026-02-18 14:37:57'),
(47, 26, '2026-02-19', '00:00:00', '15:37:00', NULL, '2026-02-18 14:37:57'),
(48, 27, '2026-02-18', '14:40:00', '15:40:00', NULL, '2026-02-18 14:40:46'),
(49, 27, '2026-02-21', '14:40:00', '23:59:59', NULL, '2026-02-18 14:41:10'),
(50, 27, '2026-02-22', '00:00:00', '15:40:00', NULL, '2026-02-18 14:41:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ground_settings`
--

CREATE TABLE `ground_settings` (
  `groundID` int(11) NOT NULL,
  `weekly_limit` int(11) DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `ground_settings`
--

INSERT INTO `ground_settings` (`groundID`, `weekly_limit`) VALUES
(26, 10),
(27, 4),
(28, 1),
(29, 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `roleID` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`roleID`, `role_name`) VALUES
(1, 'ADMIN'),
(2, 'MANAGER'),
(3, 'MEMBER');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sports`
--

CREATE TABLE `sports` (
  `sportID` int(11) NOT NULL,
  `sport_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sports`
--

INSERT INTO `sports` (`sportID`, `sport_name`) VALUES
(1, 'Bóng đá'),
(2, 'Cầu lông'),
(3, 'Bóng bàn'),
(4, 'Bóng chuyền'),
(5, 'Bóng nhảy'),
(6, 'Nhảy dây'),
(7, 'Cá sấu lên bờ'),
(8, 'Ken'),
(12, 'rồng rắn lên mây');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `roleID` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `avatar_url` varchar(255) DEFAULT NULL,
  `student_code` varchar(50) DEFAULT NULL,
  `hobbies` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`userID`, `email`, `full_name`, `google_id`, `roleID`, `status`, `created_at`, `avatar_url`, `student_code`, `hobbies`) VALUES
(1, 'kietb2204944@student.ctu.edu.vn', 'Nguyen Tan Kiet B2204944', '110113513674435867031', 2, 1, '2026-01-15 09:12:35', NULL, NULL, NULL),
(2, 'lalala030904@gmail.com', 'Kiệt Nguyễn Tấn', '110504404248111745804', 1, 1, '2026-01-18 21:52:07', NULL, NULL, NULL),
(3, 'tankietn322@gmail.com', 'Tan Kiet Nguyen', '115919598977222801732', 3, 1, '2026-01-26 18:23:44', NULL, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groundID` (`groundID`,`booking_date`,`start_time`,`end_time`),
  ADD KEY `userID` (`userID`);

--
-- Chỉ mục cho bảng `clubs`
--
ALTER TABLE `clubs`
  ADD PRIMARY KEY (`clubID`);

--
-- Chỉ mục cho bảng `club_members`
--
ALTER TABLE `club_members`
  ADD PRIMARY KEY (`userID`,`clubID`),
  ADD KEY `clubID` (`clubID`);

--
-- Chỉ mục cho bảng `club_sports`
--
ALTER TABLE `club_sports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clubID` (`clubID`,`sportID`),
  ADD KEY `sportID` (`sportID`);

--
-- Chỉ mục cho bảng `grounds`
--
ALTER TABLE `grounds`
  ADD PRIMARY KEY (`groundID`),
  ADD KEY `fk_ground_sport` (`sportID`);

--
-- Chỉ mục cho bảng `ground_locks`
--
ALTER TABLE `ground_locks`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `ground_settings`
--
ALTER TABLE `ground_settings`
  ADD PRIMARY KEY (`groundID`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleID`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Chỉ mục cho bảng `sports`
--
ALTER TABLE `sports`
  ADD PRIMARY KEY (`sportID`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`),
  ADD KEY `roleID` (`roleID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `clubs`
--
ALTER TABLE `clubs`
  MODIFY `clubID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `club_sports`
--
ALTER TABLE `club_sports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `grounds`
--
ALTER TABLE `grounds`
  MODIFY `groundID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `ground_locks`
--
ALTER TABLE `ground_locks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `sports`
--
ALTER TABLE `sports`
  MODIFY `sportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`groundID`) REFERENCES `grounds` (`groundID`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Các ràng buộc cho bảng `club_members`
--
ALTER TABLE `club_members`
  ADD CONSTRAINT `club_members_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `club_members_ibfk_2` FOREIGN KEY (`clubID`) REFERENCES `clubs` (`clubID`);

--
-- Các ràng buộc cho bảng `club_sports`
--
ALTER TABLE `club_sports`
  ADD CONSTRAINT `club_sports_ibfk_1` FOREIGN KEY (`clubID`) REFERENCES `clubs` (`clubID`) ON DELETE CASCADE,
  ADD CONSTRAINT `club_sports_ibfk_2` FOREIGN KEY (`sportID`) REFERENCES `sports` (`sportID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `grounds`
--
ALTER TABLE `grounds`
  ADD CONSTRAINT `fk_ground_sport` FOREIGN KEY (`sportID`) REFERENCES `sports` (`sportID`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `ground_settings`
--
ALTER TABLE `ground_settings`
  ADD CONSTRAINT `ground_settings_ibfk_1` FOREIGN KEY (`groundID`) REFERENCES `grounds` (`groundID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `roles` (`roleID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
