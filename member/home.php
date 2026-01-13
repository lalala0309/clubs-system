<?php
session_start();

// Kiểm tra json đã tồn tại chưa 
// if (!isset($_SESSION['google_json'])) {
//     echo 'Không có dữ liệu Google JSON trong session';
//     exit;
// }

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'MEMBER') {
    header("Location: ../public/login.php");
    exit;
}
?>

<?php include '../includes/sidebar_member.php'; ?>
<h2>Hello Member</h2>
<p>Email: <?= $_SESSION['email'] ?></p>



<a href="../auth/logout.php">Đăng xuất</a>
