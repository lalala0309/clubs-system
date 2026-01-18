<?php
if (!isset($conn)) {
    require_once __DIR__ . '/../config/database.php';
}

/*
  OUTPUT:
  $sports = [
     ['sportID'=>1, 'sport_name'=>'Bóng đá'],
     ['sportID'=>2, 'sport_name'=>'Cầu lông']
  ]
*/

$sql = "
    SELECT DISTINCT s.sportID, s.sport_name
    FROM club_members cm
    JOIN club_sports cs ON cm.clubID = cs.clubID
    JOIN sports s ON cs.sportID = s.sportID
    WHERE cm.userID = ?
      AND cm.status = 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$sports = [];
while ($row = $result->fetch_assoc()) {
    $sports[] = $row;
}
?>