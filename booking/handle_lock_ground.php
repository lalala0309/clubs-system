<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

try {
    // Lấy dữ liệu từ JS
    $grounds = $_POST['grounds'] ?? [];
    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;

    // Thiếu tham số báo lỗi
    if (empty($grounds) || !$from || !$to) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing params',
            'POST' => $_POST
        ]);
        exit;
    }

    // Chuyển string thành object DateTime
    $startDate = new DateTime($from);
    $endDate = new DateTime($to);
    $startDay = $startDate->format('Y-m-d');
    $endDay = $endDate->format('Y-m-d');

    // Tạo khoảng ngày từ start -> end
    $period = new DatePeriod(
        new DateTime($startDay),
        new DateInterval('P1D'),
        (new DateTime($endDay))->modify('+1 day')
    );

    // insert từng ngày vào bảng
    $stmt = $conn->prepare("
                INSERT INTO ground_locks (groundID, lock_date, start_time, end_time)
                VALUES (?, ?, ?, ?)
            ");

    // Lặp khoá nhiều sân cùng lúc
    foreach ($grounds as $groundID) {

        // Lặp khoá từng ngày trong bảng
        foreach ($period as $dayObj) {

            $currentDay = $dayObj->format('Y-m-d');
            if ($currentDay === $startDay && $currentDay === $endDay) {
                // cùng ngày
                $start = $startDate->format('H:i:s');
                $end = $endDate->format('H:i:s');

            } elseif ($currentDay === $startDay) {
                // ngày đầu
                $start = $startDate->format('H:i:s');
                $end = '23:59:59';

            } elseif ($currentDay === $endDay) {
                // ngày cuối
                $start = '00:00:00';
                $end = $endDate->format('H:i:s');

            } else {
                // ngày giữa
                $start = '00:00:00';
                $end = '23:59:59';
            }

            $stmt->bind_param("isss", $groundID, $currentDay, $start, $end);
            $stmt->execute();
        }
    }

    echo json_encode([
        'status' => 'success'
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'status' => 'error',
        'message' => 'Có lỗi xảy ra'
    ]);
}
