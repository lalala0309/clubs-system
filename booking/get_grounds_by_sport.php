<?php
require_once __DIR__ . '/../config/database.php';

$sportID = intval($_GET['sportID']);
$userID = intval($_GET['userID']);

$sql = "
    SELECT DISTINCT g.groundID, g.name
    FROM club_members cm
    JOIN club_sports cs ON cs.clubID = cm.clubID
    JOIN grounds g ON g.sportID = cs.sportID
    WHERE cm.userID = ?
      AND cm.status = 1
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
