<?php
require_once '../config/database.php';
header('Content-Type: application/json');

/* KIỂM TRA INPUT */
if (!isset($_POST['sport_name']) || trim($_POST['sport_name']) === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Tên môn thể thao không được rỗng'
    ]);
    exit;
}

$sport_name = trim($_POST['sport_name']);

/* KIỂM TRA TRÙNG TÊN */
$check = $conn->prepare(
    "SELECT sportID FROM sports WHERE sport_name = ? LIMIT 1"
);
$check->bind_param("s", $sport_name);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Môn thể thao đã tồn tại'
    ]);
    exit;
}
$check->close();

/* THÊM MỚI */
$stmt = $conn->prepare(
    "INSERT INTO sports (sport_name) VALUES (?)"
);
$stmt->bind_param("s", $sport_name);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Thêm môn thể thao thành công',
        'sportID' => $conn->insert_id
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi MySQL: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>