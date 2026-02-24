<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['userID'])) {
    exit;
}

$userID = $_SESSION['userID'];
$sportIDs = $_POST['sports'] ?? [];

if (!empty($sportIDs)) {

    $placeholders = implode(',', array_fill(0, count($sportIDs), '?'));
    $types = str_repeat('i', count($sportIDs));

    $stmt = $conn->prepare("SELECT sport_name FROM sports WHERE sportID IN ($placeholders)");
    $stmt->bind_param($types, ...$sportIDs);
    $stmt->execute();

    $result = $stmt->get_result();
    $names = [];

    while ($row = $result->fetch_assoc()) {
        $names[] = $row['sport_name'];
    }

    $hobbiesString = implode(', ', $names);

} else {
    $hobbiesString = null;
}

$stmt = $conn->prepare("UPDATE users SET hobbies = ? WHERE userID = ?");
$stmt->bind_param("si", $hobbiesString, $userID);
$stmt->execute();

echo "success";
exit;