<?php

if (!isset($conn)) {
    require_once __DIR__ . '/../config/database.php';
}



$sql = "
    SELECT DISTINCT
        g.groundID,
        g.name AS ground_name,
        c.club_name
    FROM club_members cm
    JOIN clubs c ON cm.clubID = c.clubID
    JOIN club_sports cs ON cs.clubID = c.clubID
    JOIN sports s ON cs.sportID = s.sportID
    JOIN grounds g ON g.sportID = s.sportID
    WHERE cm.userID = ?
      AND cm.status = 1
      AND g.status = 1
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$grounds = [];
while ($row = $result->fetch_assoc()) {
    $grounds[] = $row;
}
