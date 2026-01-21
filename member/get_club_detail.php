<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'], $_GET['clubID'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userID = (int)$_SESSION['userID'];
$clubID = (int)$_GET['clubID'];

/* ================== THÔNG TIN CLB + THÀNH VIÊN ================== */
$sql = "
SELECT 
    c.club_name,
    c.founded_date,
    cm.join_date,
    cm.fee_paid_date,
    cm.fee_expire_date,
    (
        SELECT COUNT(*) FROM club_members WHERE clubID = c.clubID
    ) AS member_count,
    (
        SELECT COUNT(*) FROM grounds WHERE clubID = c.clubID
    ) AS ground_count
FROM clubs c
JOIN club_members cm ON cm.clubID = c.clubID
WHERE c.clubID = ? AND cm.userID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $clubID, $userID);
$stmt->execute();

$clubData = $stmt->get_result()->fetch_assoc();

if (!$clubData) {
    echo json_encode(['error' => 'No permission']);
    exit;
}

/* ================== LỊCH TẬP TUẦN NÀY ================== */
$sqlSchedule = "
SELECT 
    b.booking_date,
    b.start_time,
    b.end_time,
    g.name AS ground_name
FROM bookings b
JOIN grounds g ON b.groundID = g.groundID
WHERE g.clubID = ?
  AND b.booking_date BETWEEN
      DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
      AND DATE_ADD(
          DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY),
          INTERVAL 6 DAY
      )
ORDER BY b.booking_date, b.start_time
";

$stmt2 = $conn->prepare($sqlSchedule);
$stmt2->bind_param("i", $clubID);
$stmt2->execute();
$result2 = $stmt2->get_result();

$weeklySchedule = [];
while ($row = $result2->fetch_assoc()) {
    $weeklySchedule[] = $row;
}

/* ================== TRẢ JSON ================== */
echo json_encode([
    'club_name'       => $clubData['club_name'],
    'founded_date'    => $clubData['founded_date'],
    'member_count'    => $clubData['member_count'],
    'ground_count'    => $clubData['ground_count'],
    'join_date'       => $clubData['join_date'],
    'fee_paid_date'   => $clubData['fee_paid_date'],
    'fee_expire_date' => $clubData['fee_expire_date'],
    'schedule'        => $weeklySchedule
]);
?>