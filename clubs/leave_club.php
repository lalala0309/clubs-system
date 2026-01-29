<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['userID'])) {
    echo json_encode(['error' => 'Bạn chưa đăng nhập']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$clubID = (int) ($data['clubID'] ?? 0);
$userID = (int) $_SESSION['userID'];

if (!$clubID) {
    echo json_encode(['error' => 'Thiếu mã câu lạc bộ']);
    exit;
}

/* Thực hiện xóa bản ghi (Rời CLB) */
/* Thực hiện xóa bản ghi */
$stmt = $conn->prepare("DELETE FROM club_members WHERE userID = ? AND clubID = ?");
$stmt->bind_param("ii", $userID, $clubID);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        // Trả về thêm ID để bạn kiểm tra xem có đúng ID không
        echo json_encode([
            'error' => "Không tìm thấy bản ghi thành viên. (User: $userID, Club: $clubID)"
        ]);
    }
}
?>