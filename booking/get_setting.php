<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$sql = "SELECT setting_value FROM system_settings WHERE setting_key='weekly_booking_limit'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

echo json_encode([
    'limit' => $row['setting_value'] ?? 4
]);
