<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

$userID = $_SESSION['userID'] ?? 0;
if (!$userID) {
    return [];
}

$sql = "
SELECT 
    c.clubID AS id,
    c.club_name AS name,
    GROUP_CONCAT(DISTINCT s.sport_name SEPARATOR ', ') AS sport_name,
    cm.status AS join_status
FROM clubs c
LEFT JOIN club_members cm 
    ON c.clubID = cm.clubID 
    AND cm.userID = ?
LEFT JOIN club_sports cs ON c.clubID = cs.clubID
LEFT JOIN sports s ON cs.sportID = s.sportID
WHERE cm.status IS NULL OR cm.status = 0
GROUP BY c.clubID
ORDER BY c.club_name
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

$clubs = [];

$bgList = [
    'bg-indigo-100',
    'bg-blue-100',
    'bg-emerald-100',
    'bg-purple-100',
    'bg-rose-100'
];

$i = 0;
while ($row = $result->fetch_assoc()) {
    $clubs[] = [
        'id'   => $row['id'],
        'name' => $row['name'],
        'desc' => $row['sport_name'] ?: 'Chưa có môn',
        'bg'   => $bgList[$i % count($bgList)]
    ];
    $i++;
}

return $clubs;
