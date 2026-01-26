<?php
    require_once '../includes/get_user.php';
    require_once '../config/database.php';
    require_once '../config/pdo.php';

    function getClubMembers($pdo, $clubID) {
        $sql = "
            SELECT 
                u.full_name,
                u.email,
                cm.join_date,
                cm.fee_paid_date,
                cm.fee_expire_date
            FROM club_members cm
            JOIN users u ON cm.userID = u.userID
            WHERE cm.clubID = :clubID
            AND cm.status = 1
            ORDER BY cm.join_date ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['clubID' => $clubID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function getClubs($pdo) {
        $stmt = $pdo->query("
            SELECT clubID, club_name, founded_date
            FROM clubs
            ORDER BY clubID ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>