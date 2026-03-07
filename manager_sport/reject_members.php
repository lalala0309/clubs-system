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

$stmt = $conn->prepare("
    DELETE FROM club_members
    WHERE clubID = ? AND userID = ? AND status = 0
");

foreach ($users as $userID) {
    $stmt->bind_param("ii", $clubID, $userID);
    $stmt->execute();
}

echo json_encode([
    "success" => true,
    "message" => "Đã từ chối " . count($users) . " yêu cầu"
]);