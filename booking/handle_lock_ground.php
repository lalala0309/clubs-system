<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    error_log("POST DATA: " . json_encode($_POST));

    /* =====================
       GET INPUT (MATCH JS)
    ===================== */
    $grounds = $_POST['grounds'] ?? [];
    $from = $_POST['from'] ?? null;
    $to = $_POST['to'] ?? null;

    /* =====================
       VALIDATE
    ===================== */
    if (empty($grounds) || !$from || !$to) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing params',
            'POST' => $_POST
        ]);
        exit;
    }

    /* =====================
       PARSE DATETIME
    ===================== */
    $startDate = new DateTime($from);
    $endDate = new DateTime($to);

    $startDay = $startDate->format('Y-m-d');
    $endDay = $endDate->format('Y-m-d');

    $period = new DatePeriod(
        new DateTime($startDay),
        new DateInterval('P1D'),
        (new DateTime($endDay))->modify('+1 day')
    );

    $stmt = $conn->prepare("
    INSERT INTO ground_locks (groundID, lock_date, start_time, end_time)
    VALUES (?, ?, ?, ?)
");

    foreach ($grounds as $groundID) {

        foreach ($period as $dayObj) {

            $currentDay = $dayObj->format('Y-m-d');

            // ⭐ LOGIC QUAN TRỌNG
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
        'message' => $e->getMessage(),
        'POST' => $_POST
    ]);
}
