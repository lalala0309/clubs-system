<?php
require_once '../config/database.php';
require_once '../vendor/autoload.php'; // nếu dùng PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$data = json_decode(file_get_contents("php://input"), true);
$bookingID = $data['bookingID'] ?? null;

if (!$bookingID) {
    echo json_encode(['status' => 'error', 'message' => 'Thiếu bookingID']);
    exit;
}

/* ===============================
   1. LẤY THÔNG TIN BOOKING
================================= */

$stmt = $conn->prepare("
    SELECT b.*, u.full_name, u.email, g.name AS ground_name
    FROM bookings b
    JOIN users u ON b.userID = u.userID
    JOIN grounds g ON b.groundID = g.groundID
    WHERE b.id = ?
");

$stmt->bind_param("i", $bookingID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy lịch']);
    exit;
}

$booking = $result->fetch_assoc();

/* ===============================
   2. UPDATE STATUS
================================= */

$update = $conn->prepare("
    UPDATE bookings 
    SET status = 'cancelled'
    WHERE id = ? AND status = 'approved'
");

$update->bind_param("i", $bookingID);
$update->execute();

/* ===============================
   3. GỬI EMAIL
================================= */

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
    $mail->addAddress($booking['email'], $booking['full_name']);

    $mail->isHTML(true);
    $mail->Subject = 'Thông báo huỷ lịch đặt sân';

    $mail->Body = "
        <h3>Xin chào {$booking['full_name']},</h3>
        <p>Lịch đặt sân của bạn đã bị huỷ bởi quản lý.</p>
        <ul>
            <li><strong>Sân:</strong> {$booking['ground_name']}</li>
            <li><strong>Ngày:</strong> {$booking['booking_date']}</li>
            <li><strong>Thời gian:</strong> {$booking['start_time']} - {$booking['end_time']}</li>
        </ul>
        <p>Nếu có thắc mắc vui lòng liên hệ quản lý.</p>
        <br>
        <small>Hệ thống quản lý sân CTUMP</small>
    ";

    $mail->send();

} catch (Exception $e) {
    // Không fail nếu mail lỗi
}

/* =============================== */

echo json_encode(['status' => 'success']);
