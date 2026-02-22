<?php
require_once __DIR__ . '/../config/database.php';

$groundID = intval($_GET['groundID']);
$week_offset = isset($_GET['week_offset']) ? (int) $_GET['week_offset'] : 0;

// Lấy thứ 2 của tuần đang xem
$monday = new DateTime();
$monday->modify('monday this week');
if ($week_offset !== 0) {
    $monday->modify(($week_offset > 0 ? '+' : '') . $week_offset . ' week');
}

$sunday = clone $monday;
$sunday->modify('+6 day');

$startDate = $monday->format('Y-m-d');
$endDate = $sunday->format('Y-m-d');

$sql = "
    SELECT 
        b.id,
        DATE_FORMAT(b.booking_date, '%d/%m') AS booking_date,
        DATE_FORMAT(b.start_time, '%H:%i') AS start_time,
        DATE_FORMAT(b.end_time, '%H:%i') AS end_time,
        u.full_name,
        u.email
    FROM bookings b
    JOIN users u ON b.userID = u.userID
    WHERE b.groundID = ?
      AND b.booking_date BETWEEN ? AND ?
      AND b.status = 'approved'
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $groundID, $startDate, $endDate);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
