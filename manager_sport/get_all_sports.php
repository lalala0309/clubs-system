<?php
require_once __DIR__ . '/../config/database.php';

$sports = [];

$sql = "SELECT sportID, sport_name FROM sports ORDER BY sportID ASC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $sports[] = $row;
    }
}

return $sports;
?>