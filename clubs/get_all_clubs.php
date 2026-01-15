<?php
    require_once '../config/database.php';

    // truy vấn lấy toàn bộ câu lạc bộ
    $sql = "
        SELECT c.clubID AS id, c.club_name AS name, s.sport_name
        FROM clubs c
        JOIN club_sports cs ON c.clubID = cs.clubID
        JOIN sports s ON cs.sportID = s.sportID
        ORDER BY c.club_name
    ";

    $result = $conn->query($sql);

    // Lưu danh sách câu lạc bộ
    $clubs = [];

    while ($row = $result->fetch_assoc()) {
        $sport = $row['sport_name'];
    
        $clubs[] = [
            'id'   => $row['id'],
            'name' => $row['name'],
            'desc' => $sportMap[$sport]['desc'] ?? 'Câu lạc bộ thể thao',
            'bg'   => $sportMap[$sport]['bg'] ?? 'bg-slate-100',
           // 'role' => 'Xem chi tiết'
        ];
    }

    return $clubs;
?>