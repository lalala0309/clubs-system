<?php
require_once '../config/pdo.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$groundID = $data['groundID'] ?? null;
$from = $data['from'] ?? null;
$to = $data['to'] ?? null;

if (!$groundID || !$from || !$to) {
    echo json_encode(["success" => false, "message" => "Missing params"]);
    exit;
}

try {

    $startDate = new DateTime($from);
    $endDate = new DateTime($to);

    $startDay = $startDate->format('Y-m-d');
    $endDay = $endDate->format('Y-m-d');

    $period = new DatePeriod(
        new DateTime($startDay),
        new DateInterval('P1D'),
        (new DateTime($endDay))->modify('+1 day')
    );

    foreach ($period as $dayObj) {

        $currentDay = $dayObj->format('Y-m-d');

        if ($currentDay === $startDay && $currentDay === $endDay) {
            $startTime = $startDate->format('H:i:s');
            $endTime = $endDate->format('H:i:s');

        } elseif ($currentDay === $startDay) {
            $startTime = $startDate->format('H:i:s');
            $endTime = '23:59:59';

        } elseif ($currentDay === $endDay) {
            $startTime = '00:00:00';
            $endTime = $endDate->format('H:i:s');

        } else {
            $startTime = '00:00:00';
            $endTime = '23:59:59';
        }

        $sql = "
        DELETE FROM ground_locks
        WHERE groundID = :groundID
        AND lock_date = :lockDate
        AND NOT (
            end_time <= :startTime
            OR start_time >= :endTime
        )
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':groundID' => $groundID,
            ':lockDate' => $currentDay,
            ':startTime' => $startTime,
            ':endTime' => $endTime
        ]);
    }

    echo json_encode(["success" => true]);

} catch (Throwable $e) {

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}