<?php
require_once '../config/database.php';

$sportID = (int) $_GET['sportID'];

$stmt = $conn->prepare("
    SELECT COALESCE(weekly_limit, 4) AS weekly_limit
    FROM sports
    WHERE sportID = ?
");

$stmt->bind_param("i", $sportID);
$stmt->execute();

echo json_encode($stmt->get_result()->fetch_assoc());