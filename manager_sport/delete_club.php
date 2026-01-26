<?php
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_POST['clubID'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Thiếu ID câu lạc bộ'
    ]);
    exit;
}

$clubID = intval($_POST['clubID']);

/* KIỂM TRA CLB CÓ THÀNH VIÊN KHÔNG */
$checkMembers = $conn->prepare(
    "SELECT COUNT(*) FROM club_members WHERE clubID = ?"
);
$checkMembers->bind_param("i", $clubID);
$checkMembers->execute();
$checkMembers->bind_result($totalMembers);
$checkMembers->fetch();
$checkMembers->close();

if ($totalMembers > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Không thể xóa vì câu lạc bộ đã có thành viên'
    ]);
    exit;
}

/* XÓA LIÊN KẾT MÔN */
$conn->query("DELETE FROM club_sports WHERE clubID = $clubID");

/* XÓA CLB */
$delete = $conn->prepare(
    "DELETE FROM clubs WHERE clubID = ?"
);
$delete->bind_param("i", $clubID);

if ($delete->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Đã xóa câu lạc bộ'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Xóa thất bại'
    ]);
}
