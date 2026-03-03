<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

// Lấy dữ liệu đầu vào
$userID = $_SESSION['userID'];
$groundID = intval($_GET['groundID']);
$date = $_GET['date'];

// Lấy limit của môn thể thao
$sqlLimit = "
SELECT s.weekly_limit
FROM grounds g
JOIN sports s ON g.sportID = s.sportID
WHERE g.groundID = ?
";

$stmt = $conn->prepare($sqlLimit);
$stmt->bind_param("i", $groundID);
$stmt->execute();
$limit = $stmt->get_result()->fetch_assoc()['weekly_limit'];

// Tính tuần của ngày user chọn
$weekDate = new DateTime($date);
$monday = clone $weekDate;
$monday->modify('monday this week');
$startWeek = $monday->format('Y-m-d');

$sunday = clone $monday;
$sunday->modify('+6 day');
$endWeek = $sunday->format('Y-m-d');

// Đếm số lượt user đã đặt trong một tuần
$sqlCount = "
            SELECT COUNT(*) AS total
            FROM bookings b
            JOIN grounds g ON b.groundID = g.groundID
            WHERE b.userID = ?
            AND g.sportID = (
                SELECT sportID FROM grounds WHERE groundID = ?
            )
            AND b.booking_date BETWEEN ? AND ?
            AND b.priority = 1
            ";

$stmt = $conn->prepare($sqlCount);
$stmt->bind_param("iiss", $userID, $groundID, $startWeek, $endWeek);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];

echo json_encode([
    'limit' => (int) $limit,
    'used' => (int) $total,
    'remain' => max(0, $limit - $total)
]);