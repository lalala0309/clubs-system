<?php
require_once __DIR__ . '/../config/database.php';

$groundID = intval($_GET['groundID']);

$sql = "
    SELECT 
        DATE_FORMAT(booking_date, '%d/%m') AS booking_date,
        DATE_FORMAT(start_time, '%H:%i') AS start_time,
        DATE_FORMAT(end_time, '%H:%i') AS end_time
    FROM bookings
    WHERE groundID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $groundID);
$stmt->execute();

$result = $stmt->get_result();
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>