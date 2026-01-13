<?php
session_start();
require '../auth/middleware.php';
include '../includes/header.php';

$user = $_SESSION['user'];
?>

<h2>Thông tin sinh viên</h2>

<p><strong>Họ tên:</strong> <?= htmlspecialchars($user['name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
<p><strong>Vai trò:</strong> <?= $user['role'] ?></p>

<img src="<?= $user['avatar'] ?>" width="120">

<br><br>
<a href="../auth/logout.php">Đăng xuất</a>
?>
<?php include '../includes/footer.php'; ?>
