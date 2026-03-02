<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$bookingID = $data['bookingID'] ?? null;
$userID = $_SESSION['userID'];

if (!$bookingID) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu bookingID']);
    exit;
}

// CHỈ XOÁ NẾU LÀ CỦA MÌNH
$sql = "
DELETE FROM bookings
WHERE id = ?
AND userID = ?
AND status = 'approved'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bookingID, $userID);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không thể xoá lịch này']);
}