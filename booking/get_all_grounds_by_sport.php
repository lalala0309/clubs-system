<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

// Lấy sportID từ url
$sportID = intval($_GET['sportID']);

/* Lấy toàn bộ bảo grounds nếu giá trị bị null
 sẽ lấy mặc định là 4, sắp xếp theo id tăng dần */
$sql = "
        SELECT 
            g.*,
            COALESCE(s.weekly_limit, 4) AS weekly_limit
        FROM grounds g
        JOIN sports s 
            ON g.sportID = s.sportID
        WHERE g.sportID = ?
        ORDER BY g.groundID
        ";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sportID);
$stmt->execute();

// Lấy dữ liệU ra mảng
$result = $stmt->get_result();
$grounds = [];
while ($row = $result->fetch_assoc()) {
    $grounds[] = $row;
}
echo json_encode($grounds);