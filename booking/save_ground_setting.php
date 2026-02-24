<?php
require_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$ids = $data['groundIDs'];
$limit = (int) $data['weekly_limit'];

$stmt = $conn->prepare("
INSERT INTO ground_settings (groundID, weekly_limit)
VALUES (?, ?)
ON DUPLICATE KEY UPDATE weekly_limit = VALUES(weekly_limit)
");

foreach ($ids as $id) {
    $stmt->bind_param("ii", $id, $limit);
    $stmt->execute();
}

echo json_encode(["success" => true]);
