<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$clubID = $data['clubID'] ?? null;
$users = $data['users'] ?? [];

if (!$clubID || empty($users)) {
    echo json_encode([
        "success" => false,
        "message" => "Thiếu dữ liệu"
    ]);
    exit;
}

/* duyệt thành viên */
$stmt = $conn->prepare("
    UPDATE club_members
    SET status = 1, join_date = NOW()
    WHERE userID = ? AND clubID = ? AND status = 0
");

foreach ($users as $userID) {
    $stmt->bind_param("ii", $userID, $clubID);
    $stmt->execute();
}

/* lấy thông tin user vừa duyệt */
$members = [];

$placeholders = implode(',', array_fill(0, count($users), '?'));

$query = "
    SELECT userID, full_name, email, student_code
    FROM users
    WHERE userID IN ($placeholders)
";

$stmt2 = $conn->prepare($query);

/* bind động */
$types = str_repeat('i', count($users));
$stmt2->bind_param($types, ...$users);

$stmt2->execute();
$result = $stmt2->get_result();

while ($row = $result->fetch_assoc()) {
    $row['join_date'] = date('Y-m-d');
    $members[] = $row;
}

echo json_encode([
    "success" => true,
    "message" => "Đã duyệt " . count($users) . " thành viên",
    "members" => $members
]);