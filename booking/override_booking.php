<?php
session_start();
require_once '../config/database.php';
require_once '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập']);
    exit;
}

$userID = $_SESSION['userID'];

$groundID = $_POST['groundID'] ?? null;
$booking_date = $_POST['booking_date'] ?? null;
$start_time = $_POST['start_time'] ?? null;
$end_time = $_POST['end_time'] ?? null;

if (!$groundID || !$booking_date || !$start_time || !$end_time) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu dữ liệu']);
    exit;
}

/* ==============================
   FORMAT DATE + TIME
============================== */

$start_time = date('H:i:s', strtotime($start_time));
$end_time = date('H:i:s', strtotime($end_time));

$dateObj = DateTime::createFromFormat('d/m', $booking_date);

if (!$dateObj) {
    echo json_encode(['status' => 'error', 'message' => 'Sai định dạng ngày']);
    exit;
}

$dateObj->setDate(date('Y'), $dateObj->format('m'), $dateObj->format('d'));
$booking_date = $dateObj->format('Y-m-d');


/* ==============================
   TÌM BOOKING ĐANG GIỮ SLOT
============================== */

$sqlFind = "
SELECT b.id, b.userID, b.priority, g.sportID
FROM bookings b
JOIN grounds g ON b.groundID = g.groundID
WHERE b.groundID = ?
AND b.booking_date = ?
AND b.start_time < ?
AND b.end_time > ?
AND b.status = 'approved'
LIMIT 1
";

$stmt = $conn->prepare($sqlFind);
$stmt->bind_param("isss", $groundID, $booking_date, $end_time, $start_time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Slot không tồn tại']);
    exit;
}

$row = $result->fetch_assoc();

$oldBookingID = $row['id'];
$oldUserID = $row['userID'];
$oldPriority = (int) $row['priority'];
$sportID = $row['sportID'];


/* ==============================
   LẤY THÔNG TIN USER BỊ ĐÈ
============================== */

$sqlOldUser = "
SELECT u.full_name, u.email, g.name AS ground_name
FROM bookings b
JOIN users u ON b.userID = u.userID
JOIN grounds g ON b.groundID = g.groundID
WHERE b.id = ?
";

$stmtInfo = $conn->prepare($sqlOldUser);
$stmtInfo->bind_param("i", $oldBookingID);
$stmtInfo->execute();
$oldUserInfo = $stmtInfo->get_result()->fetch_assoc();

$oldUserName = $oldUserInfo['full_name'];
$oldUserEmail = $oldUserInfo['email'];
$groundName = $oldUserInfo['ground_name'];


/* ==============================
   LẤY WEEKLY LIMIT
============================== */

$sqlLimit = "SELECT weekly_limit FROM sports WHERE sportID = ?";
$stmt = $conn->prepare($sqlLimit);
$stmt->bind_param("i", $sportID);
$stmt->execute();
$limit = (int) $stmt->get_result()->fetch_assoc()['weekly_limit'];


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
   ĐẾM LƯỢT USER MỚI
============================== */
$sqlCount = "
SELECT COUNT(*) as total
FROM bookings b
JOIN grounds g ON b.groundID = g.groundID
WHERE b.userID = ?
AND g.sportID = ?
AND b.booking_date BETWEEN ? AND ?
AND b.priority = 1
";
$stmt = $conn->prepare($sqlCount);
$stmt->bind_param("iiss", $userID, $sportID, $startWeek, $endWeek);
$stmt->execute();
$newTotal = (int) $stmt->get_result()->fetch_assoc()['total'];


/* ==============================
   TÍNH PRIORITY USER MỚI
============================== */

$newPriority = ($newTotal < $limit) ? 1 : 0;


/* ==============================
   SO SÁNH PRIORITY
============================== */

// không được đè nếu priority mới <= priority cũ
if ($newPriority <= $oldPriority) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Không đủ quyền priority để override'
    ]);
    exit;
}


/* ==============================
   TRANSACTION
============================== */

$conn->begin_transaction();

try {

    // Xóa booking cũ
    $sqlDelete = "DELETE FROM bookings WHERE id = ?";
    $stmt = $conn->prepare($sqlDelete);
    $stmt->bind_param("i", $oldBookingID);
    $stmt->execute();

    // Insert booking mới
    $sqlInsert = "
    INSERT INTO bookings 
    (userID, groundID, booking_date, start_time, end_time, status, priority)
    VALUES (?, ?, ?, ?, ?, 'approved', ?)
    ";

    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param(
        "iisssi",
        $userID,
        $groundID,
        $booking_date,
        $start_time,
        $end_time,
        $newPriority
    );
    $stmt->execute();

    $conn->commit();

    /* ==============================
       GỬI MAIL CHO USER BỊ ĐÈ
    ============================== */



    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kietb2204944@student.ctu.edu.vn';
        $mail->Password = 'vpwmbjcwjezwvxyb';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('yourgmail@gmail.com', 'CTUMP Booking');
        $mail->addAddress($oldUserEmail, $oldUserName);

        $mail->isHTML(true);
        $mail->Subject = 'Thông báo lịch đặt sân bị huỷ';

        $mail->Body = "
        <h3>Xin chào {$oldUserName},</h3>
        <p>Lịch đặt sân của bạn đã bị huỷ bởi người có lượt ưu tiên cao hơn.</p>
        <ul>
            <li><strong>Sân:</strong> {$groundName}</li>
            <li><strong>Ngày:</strong> {$booking_date}</li>
            <li><strong>Thời gian:</strong> {$start_time} - {$end_time}</li>
        </ul>
        <p>Nếu có thắc mắc vui lòng liên hệ quản lý.</p>
        <br>
        <small>Hệ thống quản lý sân CTUMP</small>
    ";

        $mail->send();

    } catch (Exception $e) {
        // Không làm fail nếu mail lỗi
    }

    echo json_encode(['status' => 'success']);

} catch (Throwable $e) {

    $conn->rollback();

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}