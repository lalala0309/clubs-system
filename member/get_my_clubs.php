<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/get_user.php';


if (!isset($_SESSION['userID'])) {
    $myClubs = [];
    return;
}

$userID = (int) $_SESSION['userID'];

$sql = "
    SELECT 
        c.clubID,
        c.club_name,
        cm.join_date
    FROM club_members cm
    INNER JOIN clubs c ON cm.clubID = c.clubID
    WHERE cm.userID = ?
      AND cm.status = 1
    ORDER BY cm.join_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();

$myClubs = [];
while ($row = $result->fetch_assoc()) {
    $myClubs[] = $row;
}

$stmt->close();
?>