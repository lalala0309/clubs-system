<?php
require_once __DIR__ . '/../config/database.php';

$sportID = intval($_GET['sportID']);
$userID = intval($_GET['userID']);

$sql = "
SELECT 
    g.groundID,
    g.name,
    fee.fee_paid_date,
    fee.fee_expire_date
FROM grounds g

LEFT JOIN (
    SELECT 
        cm.fee_paid_date,
        cm.fee_expire_date,
        cs.sportID
    FROM club_members cm
    JOIN club_sports cs ON cs.clubID = cm.clubID
    WHERE cm.userID = ?
      AND cm.status = 1
) AS fee ON fee.sportID = g.sportID

WHERE g.sportID = ?
AND g.status = 1

GROUP BY g.groundID
ORDER BY g.groundID
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
