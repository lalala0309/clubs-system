<?php
if (!isset($conn)) {
    require_once __DIR__ . '/../config/database.php';
}


$sql = "
    SELECT sportID, sport_name
    FROM sports
    ORDER BY sport_name
";

$result = $conn->query($sql);

$sports = [];

while ($row = $result->fetch_assoc()) {
    $sports[] = $row;
}
?>