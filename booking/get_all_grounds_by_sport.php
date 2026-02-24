<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$sportID = intval($_GET['sportID']);

$sql = "
SELECT g.*, 
       COALESCE(gs.weekly_limit, 4) AS weekly_limit
FROM grounds g
LEFT JOIN ground_settings gs 
    ON g.groundID = gs.groundID
WHERE g.sportID = ?
ORDER BY g.groundID
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
