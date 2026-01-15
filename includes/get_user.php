<!-- <?php
// get_user.php
session_start();
require_once '../config/database.php';

// if (!isset($_SESSION['user_id'])) {
//     header('Location: ../public/login.php');
//     exit;
// }

$userID = $_SESSION['user_id'];

$sql = "
    SELECT 
        u.userID,
        u.full_name,
        u.email,
        r.role_name
    FROM users u
    JOIN roles r ON u.roleID = r.roleID
    WHERE u.userID = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

/* Biến dùng cho giao diện */
$fullName = $user['full_name'] ?: 'Người dùng';
$roleName = $user['role_name'];
$userCode = 'ID: ' . str_pad($user['userID'], 5, '0', STR_PAD_LEFT);
$avatarUrl = "https://i.pravatar.cc/150?u=" . $userID;
?> -->