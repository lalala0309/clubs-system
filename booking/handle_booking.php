<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Bạn chưa đăng nhập'
    ]);
    exit;
}

$userID = $_SESSION['userID'];

$groundID = $_POST['groundID'] ?? null;
$booking_date = $_POST['booking_date'] ?? null;
$start_time = $_POST['start_time'] ?? null;
$end_time = $_POST['end_time'] ?? null;

if (!$groundID || !$booking_date || !$start_time || !$end_time) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Thiếu dữ liệu đặt sân'
    ]);
    exit;
}


/* ==============================
   FORMAT DATE
============================== */
$booking_date_obj = DateTime::createFromFormat('d/m', $booking_date);
$booking_date_obj->setDate(date('Y'), $booking_date_obj->format('m'), $booking_date_obj->format('d'));
$booking_date = $booking_date_obj->format('Y-m-d');


/* ==============================
   0️⃣ CHECK LOCK
============================== */
$sqlLock = "
SELECT id FROM ground_locks
WHERE groundID = ?
AND lock_date = ?
AND start_time < ?
AND end_time > ?
";

$stmt = $conn->prepare($sqlLock);
$stmt->bind_param("isss", $groundID, $booking_date, $end_time, $start_time);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Khung giờ này đang bị khóa']);
    exit;
}


/* ==============================
   CHECK TRÙNG GIỜ
============================== */
$sqlCheck = "
SELECT id FROM bookings
WHERE groundID = ?
AND booking_date = ?
AND start_time < ?
AND end_time > ?
";

$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("isss", $groundID, $booking_date, $end_time, $start_time);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Khung giờ này đã được đặt']);
    exit;
}


/* ==============================
    LẤY LIMIT THEO GROUND 
============================== */
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


/* ==============================
    TÍNH TUẦN
============================== */
$weekDate = new DateTime($booking_date);

$monday = clone $weekDate;
$monday->modify('monday this week');

$startWeek = $monday->format('Y-m-d');

$sunday = clone $monday;
$sunday->modify('+6 days');

$endWeek = $sunday->format('Y-m-d');


/* ==============================
   ĐẾM THEO USER + GROUND  
============================== */
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

if ($total >= $limit) {
    echo json_encode([
        'status' => 'error',
        'message' => "Bạn đã đạt {$limit} lượt/tuần cho sân này"
    ]);
    exit;
}


/* ==============================
    INSERT BOOKING
============================== */
$sqlInsert = "
INSERT INTO bookings (userID, groundID, booking_date, start_time, end_time)
VALUES (?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sqlInsert);
$stmt->bind_param("iisss", $userID, $groundID, $booking_date, $start_time, $end_time);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không thể lưu đặt sân']);
}
?>