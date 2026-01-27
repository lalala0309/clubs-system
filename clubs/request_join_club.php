<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['userID'])) {
    echo json_encode(['error' => 'Chưa đăng nhập']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$clubID = (int)($data['clubID'] ?? 0);
$userID = (int)$_SESSION['userID'];

if (!$clubID) {
    echo json_encode(['error' => 'Thiếu clubID']);
    exit;
}

/* Kiểm tra đã tồn tại chưa */
$check = $conn->prepare("
    SELECT status FROM club_members 
    WHERE userID = ? AND clubID = ?
");
$check->bind_param("ii", $userID, $clubID);
$check->execute();
$res = $check->get_result();

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    if ($row['status'] == 0) {
        echo json_encode(['error' => 'Yêu cầu đang chờ duyệt']);
    } else {
        echo json_encode(['error' => 'Bạn đã là thành viên của CLB này']);
    }
    exit;
}

/* Insert yêu cầu */
$stmt = $conn->prepare("
    INSERT INTO club_members (userID, clubID, request_date, status)
    VALUES (?, ?, CURDATE(), 0)
");
$stmt->bind_param("ii", $userID, $clubID);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Không thể gửi yêu cầu']);
}
