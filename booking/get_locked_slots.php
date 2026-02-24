<?php
require_once '../config/database.php';

$groundID = $_GET['groundID'];
$week_offset = isset($_GET['week_offset']) ? (int) $_GET['week_offset'] : 0;

$monday = new DateTime();
$monday->modify('monday this week');

if ($week_offset !== 0) {
    $monday->modify(($week_offset > 0 ? '+' : '') . $week_offset . ' week');
}

$start = $monday->format('Y-m-d');
$monday->modify('+6 day');
$end = $monday->format('Y-m-d');

$sql = "
SELECT * FROM ground_locks
WHERE groundID=?
AND lock_date BETWEEN ? AND ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $groundID, $start, $end);
$stmt->execute();

$res = $stmt->get_result();

echo json_encode($res->fetch_all(MYSQLI_ASSOC));
