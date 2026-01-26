<?php
require_once '../config/database.php';
header('Content-Type: application/json');

/* CHỈ NHẬN POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Phương thức không hợp lệ'
    ]);
    exit;
}

/* LẤY DỮ LIỆU */
$club_name = trim($_POST['club_name'] ?? '');
$sport_id  = intval($_POST['sport_id'] ?? 0);

/* VALIDATE */
if ($club_name === '' || $sport_id === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng nhập đầy đủ thông tin'
    ]);
    exit;
}

/* KIỂM TRA TRÙNG TÊN CLB */
$check = $conn->prepare(
    "SELECT clubID FROM clubs WHERE club_name = ? LIMIT 1"
);
$check->bind_param("s", $club_name);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Tên câu lạc bộ đã tồn tại'
    ]);
    exit;
}
$check->close();

/* NGÀY THÀNH LẬP = NGÀY HIỆN TẠI */
$founded_date = date('Y-m-d');

/* THÊM CLB */
$insertClub = $conn->prepare(
    "INSERT INTO clubs (club_name, founded_date) VALUES (?, ?)"
);
$insertClub->bind_param("ss", $club_name, $founded_date);

if (!$insertClub->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi khi thêm câu lạc bộ'
    ]);
    exit;
}

$clubID = $insertClub->insert_id;
$insertClub->close();

/* GẮN CLB – MÔN THỂ THAO */
$insertSport = $conn->prepare(
    "INSERT INTO club_sports (clubID, sportID) VALUES (?, ?)"
);
$insertSport->bind_param("ii", $clubID, $sport_id);

if (!$insertSport->execute()) {
    echo json_encode([
        'success' => false,
        'message' => 'Lỗi khi gắn môn thể thao'
    ]);
    exit;
}

$insertSport->close();

/* THÀNH CÔNG */
echo json_encode([
    'success' => true,
    'message' => 'Thêm câu lạc bộ thành công'
]);
?>