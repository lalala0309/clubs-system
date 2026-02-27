<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$bookingID = $data['bookingID'] ?? null;
$userID = $_SESSION['userID'] ?? null;

if (!$bookingID || !$userID) {
    echo json_encode(["status" => "error", "message" => "Thiếu dữ liệu"]);
    exit;
}

/* 
   Chỉ cho phép huỷ booking của chính mình
*/
$sql = "DELETE FROM bookings WHERE bookingID = ? AND userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bookingID, $userID);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Không thể huỷ hoặc không phải booking của bạn"
    ]);
}