<?php
require_once __DIR__ . '/../config/database.php';

$sportID = intval($_GET['sportID']);
$userID  = intval($_GET['userID']);

$sql = "
    SELECT g.groundID, g.name
    FROM club_members cm
    JOIN grounds g ON cm.clubID = g.clubID
    JOIN club_sports cs ON cs.clubID = cm.clubID
    WHERE cm.userID = ?
      AND cs.sportID = ?
      AND g.status = 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $userID, $sportID);
$stmt->execute();
$result = $stmt->get_result();

$grounds = [];
while ($row = $result->fetch_assoc()) {
    $grounds[] = $row;
}

echo json_encode($grounds);
?>