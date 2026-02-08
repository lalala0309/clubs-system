-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 05, 2026 lúc 09:38 AM
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
  `end_time` time NOT NULL
) ;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `userID`, `booking_date`, `groundID`, `start_time`, `end_time`) VALUES
(44, 3, '2026-01-19', 16, '07:00:00', '08:00:00'),
(45, 3, '2026-01-26', 16, '07:00:00', '08:00:00'),
(46, 3, '2026-01-26', 18, '07:00:00', '08:00:00'),
(47, 3, '2026-01-26', 18, '08:00:00', '09:00:00'),
(48, 3, '2026-01-27', 16, '07:00:00', '08:00:00'),
(49, 3, '2026-01-29', 16, '07:00:00', '08:00:00'),
(50, 3, '2026-02-02', 16, '06:00:00', '07:00:00'),
(51, 3, '2026-02-03', 16, '06:00:00', '07:00:00'),
(52, 3, '2026-01-23', 16, '07:00:00', '08:00:00'),
(53, 3, '2026-01-19', 16, '06:00:00', '07:00:00'),
(54, 3, '2026-02-10', 16, '08:00:00', '09:00:00'),
(55, 3, '2026-01-29', 16, '08:00:00', '09:00:00'),
(56, 3, '2026-02-03', 16, '09:00:00', '10:00:00'),
(57, 3, '2026-02-04', 16, '07:00:00', '08:00:00'),
(58, 3, '2026-02-05', 16, '06:00:00', '07:00:00'),
(59, 3, '2026-02-03', 26, '07:00:00', '08:00:00'),
(60, 3, '2026-01-26', 27, '06:00:00', '07:00:00'),
(61, 3, '2026-01-26', 26, '06:00:00', '07:00:00'),
(62, 3, '2026-01-26', 16, '06:00:00', '07:00:00'),
(63, 3, '2026-01-26', 17, '06:00:00', '07:00:00'),
(64, 3, '2026-02-02', 17, '06:00:00', '07:00:00'),
(65, 3, '2026-01-27', 17, '07:00:00', '08:00:00'),
(66, 3, '2026-01-22', 16, '07:00:00', '08:00:00'),
(67, 3, '2026-01-30', 16, '07:00:00', '08:00:00'),
(68, 3, '2026-02-06', 16, '08:00:00', '09:00:00'),
(69, 3, '2026-02-02', 17, '07:00:00', '08:00:00'),
(70, 3, '2026-02-03', 16, '07:00:00', '08:00:00'),
(71, 3, '2026-02-04', 16, '06:00:00', '07:00:00'),
(72, 3, '2026-01-28', 16, '06:00:00', '07:00:00'),
(73, 3, '2026-01-21', 17, '09:00:00', '10:00:00'),
(74, 3, '2026-01-27', 21, '06:00:00', '07:00:00'),
(75, 3, '2026-01-20', 21, '06:00:00', '07:00:00'),
(76, 3, '2026-01-29', 16, '14:00:00', '15:00:00'),
(77, 3, '2026-01-29', 16, '15:00:00', '16:00:00'),
(78, 3, '2026-01-29', 21, '14:00:00', '15:00:00'),
(79, 3, '2026-01-29', 21, '15:00:00', '16:00:00'),
(80, 3, '2026-02-12', 21, '14:00:00', '15:00:00'),
(81, 3, '2026-01-29', 21, '16:00:00', '17:00:00'),
(82, 3, '2026-02-09', 21, '06:00:00', '07:00:00'),
(83, 3, '2026-02-09', 21, '07:00:00', '08:00:00'),
(84, 3, '2026-02-10', 21, '07:00:00', '08:00:00'),
(85, 3, '2026-02-02', 21, '06:00:00', '07:00:00'),
(86, 3, '2026-02-03', 21, '06:00:00', '07:00:00'),
(87, 3, '2026-02-04', 21, '06:00:00', '07:00:00'),
(88, 3, '2026-02-05', 21, '06:00:00', '07:00:00'),
(89, 3, '2026-02-06', 21, '06:00:00', '07:00:00'),
(90, 3, '2026-02-07', 21, '06:00:00', '07:00:00'),
(91, 3, '2026-02-08', 21, '06:00:00', '07:00:00'),
(92, 3, '2026-01-31', 21, '09:00:00', '10:00:00'),
(93, 3, '2026-02-09', 21, '08:00:00', '09:00:00'),
(94, 3, '2026-02-10', 21, '09:00:00', '10:00:00'),
(95, 3, '2026-02-10', 21, '08:00:00', '09:00:00'),
(96, 1, '2026-02-05', 16, '07:00:00', '08:00:00'),
(97, 1, '2026-02-05', 16, '08:00:00', '09:00:00'),
(98, 1, '2026-02-02', 16, '07:00:00', '08:00:00'),
(99, 1, '2026-02-03', 16, '08:00:00', '09:00:00'),
(100, 1, '2026-02-11', 16, '08:00:00', '09:00:00'),
(101, 1, '2026-02-11', 17, '07:00:00', '08:00:00'),
(102, 3, '2026-02-05', 21, '07:00:00', '08:00:00'),
(103, 3, '2026-02-06', 21, '07:00:00', '08:00:00'),
(104, 3, '2026-02-16', 21, '13:00:00', '14:00:00'),
(105, 3, '2026-02-17', 21, '14:00:00', '15:00:00');

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
(31, 'Sân bóng bàn C6', 'Nhà đa năng', 1, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `ma_nguoi_dung` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ho_ten` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `ma_vai_tro` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`userID`, `email`, `full_name`, `google_id`, `roleID`, `status`, `created_at`) VALUES
(1, 'kietb2204944@student.ctu.edu.vn', 'Nguyen Tan Kiet B2204944', '110113513674435867031', 2, 1, '2026-01-15 09:12:35'),
(2, 'lalala030904@gmail.com', 'Kiệt Nguyễn Tấn', '110504404248111745804', 1, 1, '2026-01-18 21:52:07'),
(3, 'tankietn322@gmail.com', 'Tan Kiet Nguyen', '115919598977222801732', 3, 1, '2026-01-26 18:23:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vai_tro`
--

CREATE TABLE `vai_tro` (
  `ma_vai_tro` int(11) NOT NULL,
  `ten_vai_tro` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`ma_nguoi_dung`),
  ADD UNIQUE KEY `UKmajqh5g4djy2tp3p9dvr64brp` (`email`),
  ADD KEY `FKmmv89ljypmv25yi44275p7qmg` (`ma_vai_tro`);

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
-- Chỉ mục cho bảng `vai_tro`
--
ALTER TABLE `vai_tro`
  ADD PRIMARY KEY (`ma_vai_tro`);

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
  MODIFY `groundID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `ma_nguoi_dung` int(11) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT cho bảng `vai_tro`
--
ALTER TABLE `vai_tro`
  MODIFY `ma_vai_tro` int(11) NOT NULL AUTO_INCREMENT;

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
-- Các ràng buộc cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD CONSTRAINT `FKmmv89ljypmv25yi44275p7qmg` FOREIGN KEY (`ma_vai_tro`) REFERENCES `vai_tro` (`ma_vai_tro`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `roles` (`roleID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
