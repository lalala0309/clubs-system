<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$sportID = intval($_GET['sportID']);

$sql = "
    SELECT 
        groundID,
        name
    FROM grounds
    WHERE sportID = ?
      AND status = 1
    ORDER BY groundID
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sportID);
$stmt->execute();

$result = $stmt->get_result();

$grounds = [];

while ($row = $result->fetch_assoc()) {
    $grounds[] = $row;
}

echo json_encode($grounds);
