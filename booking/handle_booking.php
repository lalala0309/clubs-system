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


// Lấy dữ liệu từ POST
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

// Format date
$booking_date_obj = DateTime::createFromFormat('d/m', $booking_date);

if (!$booking_date_obj) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Sai định dạng ngày (phải là dd/mm)'
    ]);
    exit;
}

// Gán năm hiện tại
$booking_date_obj->setDate(
    date('Y'),
    $booking_date_obj->format('m'),
    $booking_date_obj->format('d')
);

$booking_date = $booking_date_obj->format('Y-m-d');


// Lấy weekly limit theo ground
$sqlLimit = "
SELECT s.weekly_limit
FROM grounds g
JOIN sports s ON g.sportID = s.sportID
WHERE g.groundID = ?
";

$stmt = $conn->prepare($sqlLimit);
$stmt->bind_param("i", $groundID);
$stmt->execute();
$stmt->bind_result($limit);
$stmt->fetch();
$stmt->close();


// tính tuần của ngày đặt
$weekDate = new DateTime($booking_date);

$monday = clone $weekDate;
$monday->modify('monday this week');

$startWeek = $monday->format('Y-m-d');

$sunday = clone $monday;
$sunday->modify('+6 days');

$endWeek = $sunday->format('Y-m-d');



// Check trùng giờ
$sqlCheck = "
SELECT id, userID FROM bookings
WHERE groundID = ?
AND booking_date = ?
AND start_time < ?
AND end_time > ?
AND status = 'approved'
";

$stmt = $conn->prepare($sqlCheck);
$stmt->bind_param("isss", $groundID, $booking_date, $end_time, $start_time);
$stmt->execute();
$result = $stmt->get_result();

// Nếu trùng giờ => kiểm ra override
if ($result->num_rows > 0) {
    $existing = $result->fetch_assoc();
    $oldUserID = $existing['userID'];
    $oldBookingID = $existing['id'];

    $sqlOldCount = "
    SELECT COUNT(*) AS total
    FROM bookings b
    JOIN grounds g ON b.groundID = g.groundID
    WHERE b.userID = ?
    AND g.sportID = (
        SELECT sportID FROM grounds WHERE groundID = ?
    )
    AND b.booking_date BETWEEN ? AND ?
    AND b.priority = 1
    ";

    $stmtOld = $conn->prepare($sqlOldCount);
    $stmtOld->bind_param("iiss", $oldUserID, $groundID, $startWeek, $endWeek);
    $stmtOld->execute();
    $oldTotal = $stmtOld->get_result()->fetch_assoc()['total'];
    if ($oldTotal >= $limit) {

        $conn->begin_transaction();
        try {
            // Huỷ booking cũ
            $sqlCancel = "UPDATE bookings SET status='cancelled' WHERE id=?";
            $stmtCancel = $conn->prepare($sqlCancel);
            $stmtCancel->bind_param("i", $oldBookingID);
            $stmtCancel->execute();

            // Insert booking mới
            $sqlInsert = "
            INSERT INTO bookings (userID, groundID, booking_date, start_time, end_time)
            VALUES (?, ?, ?, ?, ?)
            ";

            $stmtInsert = $conn->prepare($sqlInsert);
            $stmtInsert->bind_param("iisss", $userID, $groundID, $booking_date, $start_time, $end_time);
            $stmtInsert->execute();

            // Commit
            $conn->commit();
            echo json_encode(['status' => 'success']);
            exit;

        } catch (Exception $e) {
            $conn->rollback();

            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi huỷ'
            ]);
            exit;
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Khung giờ này đã được đặt'
        ]);
        exit;
    }
}


// Đếm số lượt tính vào limit của người cũ
$sqlCount = "
SELECT COUNT(*) AS total
FROM bookings b
JOIN grounds g ON b.groundID = g.groundID
WHERE b.userID = ?
AND g.sportID = (
    SELECT sportID FROM grounds WHERE groundID = ?
)
AND b.booking_date BETWEEN ? AND ?
AND b.priority = 1
";

$stmt = $conn->prepare($sqlCount);
$stmt->bind_param("iiss", $userID, $groundID, $startWeek, $endWeek);
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'];


// Tính độ ưu tiên
$priority = ($total < $limit) ? 1 : 0;


// Inser booking
$sqlInsert = "
INSERT INTO bookings 
(userID, groundID, booking_date, start_time, end_time, status, priority)
VALUES (?, ?, ?, ?, ?, 'approved', ?)
";

$stmt = $conn->prepare($sqlInsert);
$stmt->bind_param("iisssi", $userID, $groundID, $booking_date, $start_time, $end_time, $priority);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không thể lưu']);
}