<?php
function getPendingRequests($pdo, $clubID)
{
    $stmt = $pdo->prepare("
        SELECT 
            u.userID,
            u.full_name,
            u.email,
            cm.request_date
        FROM club_members cm
        JOIN users u ON cm.userID = u.userID
        WHERE cm.clubID = ?
          AND cm.status = 0
        ORDER BY cm.request_date ASC
    ");
    $stmt->execute([$clubID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
