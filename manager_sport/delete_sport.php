<?php
require_once '../config/database.php';
header('Content-Type: application/json');

// 1. Kiểm tra đầu vào
if (!isset($_POST['sportID']) || empty($_POST['sportID'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu ID môn thể thao']);
    exit;
}

$sportID = intval($_POST['sportID']);

// 2. KIỂM TRA: Môn này có câu lạc bộ nào đang tham gia không?
// Chúng ta đếm số dòng trong bảng club_sports có sportID này
$checkSql = "SELECT COUNT(*) as total FROM club_sports WHERE sportID = ?";
$stmtCheck = $conn->prepare($checkSql);
$stmtCheck->bind_param("i", $sportID);
$stmtCheck->execute();
$result = $stmtCheck->get_result();
$row = $result->fetch_assoc();
$totalClubs = $row['total'];
$stmtCheck->close();

if ($totalClubs > 0) {
    // NẾU CÓ CLB: Thông báo và dừng lại, không xóa
    echo json_encode([
        'success' => false,
        'message' => "Không thể xóa! Môn thể thao này đang có $totalClubs câu lạc bộ tham gia."
    ]);
    exit;
}

// 3. THỰC HIỆN XÓA: Nếu chạy xuống đến đây nghĩa là môn này không có CLB nào
$deleteSql = "DELETE FROM sports WHERE sportID = ?";
$stmtDelete = $conn->prepare($deleteSql);
$stmtDelete->bind_param("i", $sportID);

if ($stmtDelete->execute()) {
    if ($stmtDelete->affected_rows > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Xóa môn thể thao thành công (vì không có CLB nào)'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Môn thể thao không tồn tại để xóa'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi hệ thống khi xóa: ' . $conn->error
    ]);
}

$stmtDelete->close();
$conn->close();
?>