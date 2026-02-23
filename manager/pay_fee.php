<?php
header('Content-Type: application/json');
require_once '../config/pdo.php';

$data = json_decode(file_get_contents("php://input"), true);

$userID = $data['userID'] ?? null;
$clubID = $data['clubID'] ?? null;
$months = $data['months'] ?? 1;

if (!$userID || !$clubID) {
    echo json_encode(["success" => false, "error" => "Thiếu dữ liệu"]);
    exit;
}

$months = (int) $months;

if ($months <= 0 || $months > 12) {
    echo json_encode(["success" => false, "error" => "Số tháng không hợp lệ"]);
    exit;
}

try {

    $today = date('Y-m-d');

    // Nếu còn hạn → cộng thêm vào ngày hết hạn cũ
    $check = $pdo->prepare("
        SELECT fee_expire_date 
        FROM club_members 
        WHERE userID = ? AND clubID = ?
    ");
    $check->execute([$userID, $clubID]);
    $row = $check->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['fee_expire_date'] && strtotime($row['fee_expire_date']) > time()) {
        $baseDate = $row['fee_expire_date'];
    } else {
        $baseDate = $today;
    }

    $daysToAdd = $months * 30;
    $expire = date('Y-m-d', strtotime("+$daysToAdd days", strtotime($baseDate)));

    $stmt = $pdo->prepare("
        UPDATE club_members
        SET fee_paid_date = ?, fee_expire_date = ?
        WHERE userID = ? AND clubID = ?
    ");

    $stmt->execute([$today, $expire, $userID, $clubID]);

    echo json_encode([
        "success" => true,
        "fee_paid_date" => $today,
        "fee_expire_date" => $expire
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}