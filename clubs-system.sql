-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 07, 2026 lúc 11:28 AM
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
  `status` enum('approved','cancelled') DEFAULT 'approved',
  `priority` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `userID`, `booking_date`, `groundID`, `start_time`, `end_time`, `status`, `priority`) VALUES
(3, 3, '2026-03-06', 38, '09:00:00', '10:00:00', 'approved', 1),
(5, 3, '2026-03-06', 38, '13:00:00', '14:00:00', 'approved', 1),
(7, 1, '2026-03-06', 38, '14:00:00', '15:00:00', 'approved', 1),
(8, 3, '2026-03-06', 38, '08:00:00', '09:00:00', 'approved', 1),
(9, 1, '2026-03-05', 38, '22:00:00', '23:00:00', 'approved', 1),
(10, 3, '2026-03-06', 38, '17:00:00', '18:00:00', 'approved', 1),
(11, 3, '2026-03-06', 38, '18:00:00', '19:00:00', 'approved', 1),
(12, 3, '2026-03-06', 38, '20:00:00', '21:00:00', 'approved', 1),
(13, 3, '2026-03-06', 38, '21:00:00', '22:00:00', 'approved', 0),
(14, 3, '2026-03-07', 40, '05:00:00', '06:00:00', 'approved', 1),
(15, 3, '2026-03-07', 40, '06:00:00', '07:00:00', 'approved', 1),
(16, 3, '2026-03-07', 40, '07:00:00', '08:00:00', 'approved', 0),
(18, 3, '2026-03-07', 40, '08:00:00', '09:00:00', 'approved', 0),
(19, 3, '2026-03-09', 40, '22:00:00', '23:00:00', 'approved', 1),
(20, 3, '2026-03-09', 40, '05:00:00', '06:00:00', 'approved', 1),
(21, 3, '2026-03-09', 40, '06:00:00', '07:00:00', 'approved', 0),
(22, 3, '2026-03-09', 40, '12:00:00', '13:00:00', 'approved', 0),
(23, 3, '2026-03-09', 40, '11:00:00', '12:00:00', 'approved', 0),
(24, 3, '2026-03-10', 40, '05:00:00', '06:00:00', 'approved', 0),
(25, 3, '2026-03-07', 38, '05:00:00', '06:00:00', 'approved', 0),
(26, 3, '2026-03-08', 38, '05:00:00', '06:00:00', 'approved', 0),
(27, 3, '2026-03-07', 38, '22:00:00', '23:00:00', 'approved', 0),
(28, 3, '2026-03-09', 38, '05:00:00', '06:00:00', 'approved', 1),
(29, 3, '2026-03-07', 38, '17:00:00', '18:00:00', 'approved', 0),
(30, 3, '2026-03-07', 38, '18:00:00', '19:00:00', 'approved', 0),
(31, 3, '2026-03-07', 38, '19:00:00', '20:00:00', 'approved', 0);

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
(9, 'Câu lạc bộ Cầu lông', '2026-03-04'),
(10, 'Câu lạc Bóng đá', '2026-03-06');

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
(1, 9, NULL, NULL, NULL, 0, '2026-03-07'),
(1, 10, '2026-03-07', NULL, NULL, 1, '2026-03-07'),
(3, 9, '2026-03-07', '2026-03-06', '2026-04-05', 1, '2026-03-07'),
(3, 10, '2026-03-07', NULL, NULL, 1, '2026-03-07');

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
(1, 9, 24),
(2, 10, 23);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `grounds`
--

CREATE TABLE `grounds` (
  `groundID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `sportID` int(11) NOT NULL,
  `open_time` time DEFAULT '05:00:00',
  `close_time` time DEFAULT '23:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `grounds`
--

INSERT INTO `grounds` (`groundID`, `name`, `location`, `status`, `sportID`, `open_time`, `close_time`) VALUES
(38, 'Sân số 1', 'Khu A', 1, 24, '05:00:00', '23:00:00'),
(39, 'Sân số 2', 'Khu A', 1, 24, '05:00:00', '23:00:00'),
(40, 'Sân số 1', 'Khu A', 1, 23, '05:00:00', '23:00:00');

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
  `sport_name` varchar(100) NOT NULL,
  `weekly_limit` int(11) DEFAULT 4
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sports`
--

INSERT INTO `sports` (`sportID`, `sport_name`, `weekly_limit`) VALUES
(23, 'Bóng đá', 2),
(24, 'Cầu lông', 6);

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
(1, 'kietb2204944@student.ctu.edu.vn', 'B2204944 Nguyen Tan Kiet', '110113513674435867031', 3, 1, '2026-01-15 09:12:35', 'https://lh3.googleusercontent.com/a/ACg8ocL_Xtb2AfZ3FN6hXtizcrdcVbcDAvggFHiwchiIa3oBkQVqCpVD=s96-c?sz=200', 'kietb2204944', 'Bóng chuyền, Bóng nhảy, Ken'),
(2, 'lalala030904@gmail.com', 'Nguyễn Tấn Kiệt', '110504404248111745804', 1, 1, '2026-01-18 21:52:07', 'https://lh3.googleusercontent.com/a/ACg8ocJ7u-EvZSeNMPqyVl_3UsBDM8iYYiWU-Bj2qCvIKsZyc6RlnnU=s96-c?sz=200', NULL, NULL),
(3, 'tankietn322@gmail.com', 'Nguyen Tan Kiet', '115919598977222801732', 3, 1, '2026-01-26 18:23:44', 'https://lh3.googleusercontent.com/a/ACg8ocIHSn-de4OfzBUt7e3vsOpqwERRmXPA4SdCHO39QYOEXEtX5rI2=s96-c?sz=200', 'tankietn322', 'Cầu lông, Bóng bàn, Nhảy dây, Ken');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `clubs`
--
ALTER TABLE `clubs`
  MODIFY `clubID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `club_sports`
--
ALTER TABLE `club_sports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `grounds`
--
ALTER TABLE `grounds`
  MODIFY `groundID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `ground_locks`
--
ALTER TABLE `ground_locks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `sports`
--
ALTER TABLE `sports`
  MODIFY `sportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

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
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `roles` (`roleID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
