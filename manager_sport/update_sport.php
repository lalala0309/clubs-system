<?php
require_once '../config/database.php';
header('Content-Type: application/json');

/* LẤY DỮ LIỆU */
$sportID = isset($_POST['sportID']) ? (int)$_POST['sportID'] : 0;
$name    = isset($_POST['sport_name']) ? trim($_POST['sport_name']) : '';

if ($sportID <= 0 || $name === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Dữ liệu không hợp lệ'
    ]);
    exit;
}

/* KIỂM TRA TRÙNG TÊN (TRỪ CHÍNH NÓ) */
$check = $conn->prepare(
    "SELECT sportID FROM sports 
     WHERE sport_name = ? AND sportID != ? 
     LIMIT 1"
);
$check->bind_param("si", $name, $sportID);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Tên môn thể thao đã tồn tại'
    ]);
    $check->close();
    exit;
}
$check->close();

/* UPDATE */
$update = $conn->prepare(
    "UPDATE sports SET sport_name = ? WHERE sportID = ?"
);
$update->bind_param("si", $name, $sportID);

if ($update->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Đổi tên môn thể thao thành công'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Không thể cập nhật'
    ]);
}

$update->close();
$conn->close();
?>