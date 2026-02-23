<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

/* KIỂM TRA ĐĂNG NHẬP */
if (!isset($_SESSION['userID'])) {
    header("Location: /clubs-system/public/login.php");
    exit;
}

$userID = $_SESSION['userID'];

/* LẤY THÔNG TIN USER + ROLE */
$sql = "
    SELECT 
        u.full_name,
        u.email,
        u.avatar_url,
        u.student_code,
        u.hobbies,
        u.created_at,
        r.role_name
    FROM users u
    JOIN roles r ON u.roleID = r.roleID
    WHERE u.userID = ?
    LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    session_destroy();
    header("Location: /clubs-system/public/login.php");
    exit;
}

$user = $result->fetch_assoc();

/* BIẾN DÙNG CHUNG */
$fullName = $user['full_name'];
$userEmail = $user['email'];
$roleName = $user['role_name']; // ADMIN | MANAGER | MEMBER
$avatar = $user['avatar_url'] ?: ($_SESSION['avatar'] ?? null);
$studentCode = $user['student_code'] ?? '';
$hobbies = $user['hobbies'] ?? '';
$createdAt = $user['created_at'] ?? '';
?>