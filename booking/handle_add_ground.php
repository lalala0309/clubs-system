<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$name = trim($_POST['name']);
$location = trim($_POST['location']);
$sportID = (int) $_POST['sportID'];

if (!$name || !$sportID) {
    echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO grounds (name, location, status, sportID)
    VALUES (?, ?, 1, ?)
");

$stmt->bind_param("ssi", $name, $location, $sportID);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
