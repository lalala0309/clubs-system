<?php
header('Content-Type: application/json');
require_once '../config/pdo.php';
date_default_timezone_set('Asia/Ho_Chi_Minh');
$sportID = $_GET['sportID'] ?? null;

if (!$sportID) {
    echo json_encode([
        "grounds" => [],
        "slots" => [],
        "bookings" => []
    ]);
    exit;
}

/* ======================
   TẠO KHUNG GIỜ
====================== */

$now = new DateTime();
$minute = (int) $now->format('i');

if ($minute > 0) {
    $now->modify('+1 hour');
}

$now->setTime((int) $now->format('H'), 0);

$timeSlots = [];

for ($i = 0; $i < 8; $i++) {

    $start = clone $now;
    $end = clone $now;
    $end->modify('+1 hour');

    if ((int) $start->format('H') >= 22)
        break;

    $timeSlots[] = [
        'start' => $start->format('H:i:s'),
        'end' => $end->format('H:i:s'),
        'label' => $start->format('H:i') . '-' . $end->format('H:i')
    ];

    $now->modify('+1 hour');
}

/* ======================
   LẤY DANH SÁCH SÂN
====================== */

$stmt = $pdo->prepare("
    SELECT groundID, name
    FROM grounds
    WHERE sportID = ?
    AND status = 1
");
$stmt->execute([$sportID]);
$grounds = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ======================
   LẤY BOOKING HÔM NAY
====================== */

$stmt = $pdo->prepare("
    SELECT 
        b.groundID,
        b.start_time,
        u.full_name,
        u.email
    FROM bookings b
    JOIN users u ON b.userID = u.userID
    JOIN grounds g ON b.groundID = g.groundID
    WHERE g.sportID = ?
    AND b.booking_date = CURDATE()
    AND b.status = 'approved'
");
$stmt->execute([$sportID]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "grounds" => $grounds,
    "slots" => $timeSlots,
    "bookings" => $bookings
]);