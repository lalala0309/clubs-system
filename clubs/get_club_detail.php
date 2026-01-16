<?php
ini_set('display_errors', 0);
error_reporting(0);
ob_start();
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/pdo.php';



$clubID = $_GET['clubID'] ?? 0;
if (!$clubID) {
    echo json_encode(['error' => 'Missing clubID']);
    exit;
}

/* Thông tin CLB */
$stmt = $pdo->prepare("
    SELECT club_name, founded_date
    FROM clubs
    WHERE clubID = ?
");
$stmt->execute([$clubID]);
$club = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$club) {
    echo json_encode(['error' => 'Club not found']);
    exit;
}

/* Thành viên */
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM club_members 
    WHERE clubID = ? AND status = 1
");
$stmt->execute([$clubID]);
$members = $stmt->fetchColumn();

/* Môn thể thao */
$stmt = $pdo->prepare("
    SELECT s.sport_name
    FROM club_sports cs
    JOIN sports s ON cs.sportID = s.sportID
    WHERE cs.clubID = ?
");
$stmt->execute([$clubID]);
$sports = $stmt->fetchAll(PDO::FETCH_COLUMN);

/* Sân của CLB */
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM grounds
    WHERE clubID = ? AND status = 1
");
$stmt->execute([$clubID]);
$grounds = $stmt->fetchColumn();

echo json_encode([
    'name'     => $club['club_name'],
    'founded'  => $club['founded_date'],
    'members' => (int)$members,
    'sports'  => implode(', ', $sports),
    'grounds' => (int)$grounds
]);

exit;
?>