<?php
require_once '../config/pdo.php';

$data = json_decode(file_get_contents('php://input'), true);
$userID = $data['userID'] ?? null;
$clubID = $data['clubID'] ?? null;

if (!$userID || !$clubID) {
    echo json_encode(['success' => false, 'error' => 'Thiếu dữ liệu']);
    exit;
}

try {
    // duyệt
    $stmt = $pdo->prepare("
        UPDATE club_members
        SET status = 1, join_date = NOW()
        WHERE userID = ? AND clubID = ? AND status = 0
    ");
    $stmt->execute([$userID, $clubID]);

    // lấy lại thông tin thành viên
    $stmt = $pdo->prepare("
        SELECT u.full_name, u.email, cm.join_date
        FROM club_members cm
        JOIN users u ON u.userID = cm.userID
        WHERE cm.userID = ? AND cm.clubID = ?
    ");
    $stmt->execute([$userID, $clubID]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'member' => $member
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
