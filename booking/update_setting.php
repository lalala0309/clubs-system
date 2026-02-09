<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$limit = (int) $_POST['limit'];

$sql = "
    UPDATE system_settings
    SET setting_value = ?
    WHERE setting_key = 'weekly_booking_limit'
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $limit);
$stmt->execute();

echo json_encode(['message' => 'Đã cập nhật thành công']);
