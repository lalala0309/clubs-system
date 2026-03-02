<?php
require_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$sportID = (int) $data['sportID'];
$limit = (int) $data['weekly_limit'];

if ($sportID <= 0 || $limit <= 0) {
    echo json_encode(["success" => false]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE sports
    SET weekly_limit = ?
    WHERE sportID = ?
");

$stmt->bind_param("ii", $limit, $sportID);
$stmt->execute();

echo json_encode(["success" => true]);
?>