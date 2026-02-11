<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$userID = $_SESSION['userID'];
$groundID = $_GET['groundID'];
$date = $_GET['date']; // yyyy-mm-dd (ngày bất kỳ trong tuần)

/* ========= LẤY LIMIT ========= */
$sqlLimit = "
SELECT COALESCE(gs.weekly_limit, 4) AS weekly_limit
FROM grounds g
LEFT JOIN ground_settings gs ON g.groundID = gs.groundID
WHERE g.groundID = ?
";
$stmt = $conn->prepare($sqlLimit);
$stmt->bind_param("i", $groundID);
$stmt->execute();

$limit = $stmt->get_result()->fetch_assoc()['weekly_limit'];


/* ========= TÍNH TUẦN ========= */
$weekDate = new DateTime($date);

$monday = clone $weekDate;
$monday->modify('monday this week');

$startWeek = $monday->format('Y-m-d');

$sunday = clone $monday;
$sunday->modify('+6 days');

$endWeek = $sunday->format('Y-m-d');


/* ========= ĐẾM ========= */
$sqlCount = "
SELECT COUNT(*) AS total
FROM bookings
WHERE userID = ?
AND groundID = ?
AND booking_date BETWEEN ? AND ?
";

$stmt = $conn->prepare($sqlCount);
$stmt->bind_param("iiss", $userID, $groundID, $startWeek, $endWeek);
$stmt->execute();

$total = $stmt->get_result()->fetch_assoc()['total'];

echo json_encode([
    'limit' => $limit,
    'used' => $total,
    'remain' => $limit - $total
]);
