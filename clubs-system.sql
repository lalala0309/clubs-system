-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 27, 2026 lúc 09:06 AM
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
(7, 'Câu lạc bộ Cá sấu lên bờ', '2026-01-27');

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
(1, 2, '2026-01-27', NULL, NULL, 1, '2026-01-27'),
(1, 6, NULL, NULL, NULL, 0, '2026-01-27'),
(2, 2, NULL, NULL, NULL, 0, '2026-01-27'),
(2, 6, '2026-01-27', NULL, NULL, 1, '2026-01-27'),
(3, 3, '2026-01-27', NULL, NULL, 1, NULL);

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
(9, 7, 7);

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
(2, 'lalala030904@gmail.com', 'Kiệt Nguyễn Tấn', '110504404248111745804', 3, 1, '2026-01-18 21:52:07'),
(3, 'tankietn322@gmail.com', 'Tan Kiet Nguyen', '115919598977222801732', 3, 1, '2026-01-26 18:23:44');

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
  MODIFY `clubID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `club_sports`
--
ALTER TABLE `club_sports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `grounds`
--
ALTER TABLE `grounds`
  MODIFY `groundID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `roles` (`roleID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
