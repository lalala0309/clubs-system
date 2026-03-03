<?php
require_once '../config/database.php';
header('Content-Type: application/json');


// Lấy dữ liệu từ url
$groundID = isset($_GET['groundID']) ? (int) $_GET['groundID'] : 0;
$week_offset = isset($_GET['week_offset']) ? (int) $_GET['week_offset'] : 0;

// Lấy ngày thứ hai của tuần hiện tại
$monday = new DateTime();
$monday->modify('monday this week');

if ($week_offset !== 0) {
    $monday->modify(($week_offset > 0 ? '+' : '') . $week_offset . ' week');
}

$start = $monday->format('Y-m-d');
$monday->modify('+6 day');
$end = $monday->format('Y-m-d');

// Lấy danh sách các khoảng thời gian bị khoá trong tuần
$sql = "
SELECT id, groundID, lock_date, start_time, end_time
FROM ground_locks
WHERE groundID = ?
AND lock_date BETWEEN ? AND ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $groundID, $start, $end);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_all(MYSQLI_ASSOC);

echo json_encode($data);