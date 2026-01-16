<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(['error' => 'Bạn chưa đăng nhập']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$clubID = (int)($data['clubID'] ?? 0);
$userID = $_SESSION['userID'];

if (!$clubID) {
    echo json_encode(['error' => 'Thiếu clubID']);
    exit;
}

/* Kiểm tra đã là thành viên chưa */
$stmt = $conn->prepare("
    SELECT 1 FROM club_members 
    WHERE userID = ? AND clubID = ?
");
$stmt->bind_param("ii", $userID, $clubID);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['error' => 'Bạn đã là thành viên của CLB này']);
    exit;
}

/* Đăng ký */
$stmt = $conn->prepare("
    INSERT INTO club_members (userID, clubID, join_date, status)
    VALUES (?, ?, CURDATE(), 1)
");
$stmt->bind_param("ii", $userID, $clubID);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Không thể đăng ký']);
}
?>