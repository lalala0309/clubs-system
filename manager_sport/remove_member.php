<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$clubID = $data['clubID'] ?? null;
$userID = $data['userID'] ?? null;

if (!$clubID || !$userID) {
    echo json_encode([
        "success" => false,
        "message" => "Thiếu dữ liệu"
    ]);
    exit;
}

$stmt = $conn->prepare("
    DELETE FROM club_members
    WHERE clubID = ? AND userID = ?
");

$stmt->bind_param("ii", $clubID, $userID);
$stmt->execute();

echo json_encode([
    "success" => true,
    "message" => "Đã xoá thành viên"
]);