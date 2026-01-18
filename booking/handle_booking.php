<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Bạn chưa đăng nhập'
    ]);
    exit;
}

$userID = $_SESSION['userID'];

$groundID      = $_POST['groundID'] ?? null;
$booking_date  = $_POST['booking_date'] ?? null;
$start_time    = $_POST['start_time'] ?? null;
$end_time      = $_POST['end_time'] ?? null;

if (!$groundID || !$booking_date || !$start_time || !$end_time) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu dữ liệu đặt sân'
    ]);
    exit;
}

/* CHUYỂN 19/01 → 2026-01-19 (ví dụ) */
$booking_date = DateTime::createFromFormat('d/m', $booking_date);
$booking_date = $booking_date->format('Y-m-d');

/* KIỂM TRA TRÙNG GIỜ */
$sqlCheck = "
    SELECT id FROM bookings
    WHERE groundID = ?
      AND booking_date = ?
      AND start_time < ?
      AND end_time > ?
";
$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("isss", $groundID, $booking_date, $end_time, $start_time);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Khung giờ này đã được đặt'
    ]);
    exit;
}

/* LƯU BOOKING */
$sqlInsert = "
    INSERT INTO bookings (userID, groundID, booking_date, start_time, end_time)
    VALUES (?, ?, ?, ?, ?)
";
$stmt = $conn->prepare($sqlInsert);
$stmt->bind_param(
    "iisss",
    $userID,
    $groundID,
    $booking_date,
    $start_time,
    $end_time
);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Không thể lưu đặt sân'
    ]);
}
?>