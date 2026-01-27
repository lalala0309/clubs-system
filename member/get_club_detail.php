<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'], $_GET['clubID'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userID = (int) $_SESSION['userID'];
$clubID = (int) $_GET['clubID'];

/* ========= THÔNG TIN CLB + THÀNH VIÊN ========= */
$sql = "
SELECT 
    c.club_name,
    c.founded_date,
    cm.join_date,
    cm.fee_paid_date,
    cm.fee_expire_date,

    (
        SELECT COUNT(*) 
        FROM club_members 
        WHERE clubID = c.clubID AND status = 1
    ) AS member_count,

    (
        SELECT COUNT(DISTINCT g.groundID)
        FROM club_sports cs
        JOIN grounds g ON g.sportID = cs.sportID
        WHERE cs.clubID = c.clubID
    ) AS ground_count

FROM clubs c
JOIN club_members cm ON cm.clubID = c.clubID
WHERE c.clubID = ? AND cm.userID = ? AND cm.status = 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $clubID, $userID);
$stmt->execute();

$clubData = $stmt->get_result()->fetch_assoc();

if (!$clubData) {
    echo json_encode(['error' => 'No permission']);
    exit;
}

/* ========= LỊCH TẬP TUẦN HIỆN TẠI ========= */
$sqlSchedule = "
SELECT 
    b.booking_date,
    b.start_time,
    b.end_time,
    g.name AS ground_name
FROM bookings b
JOIN grounds g ON b.groundID = g.groundID
JOIN club_sports cs ON cs.sportID = g.sportID
WHERE cs.clubID = ?
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

$weeklySchedule = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

/* ========= TRẢ JSON ========= */
echo json_encode([
    'club_name' => $clubData['club_name'],
    'founded_date' => $clubData['founded_date'],
    'member_count' => $clubData['member_count'],
    'ground_count' => $clubData['ground_count'],
    'join_date' => $clubData['join_date'],
    'fee_paid_date' => $clubData['fee_paid_date'],
    'fee_expire_date' => $clubData['fee_expire_date'],
    'schedule' => $weeklySchedule
]);
