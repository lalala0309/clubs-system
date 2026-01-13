<?php
session_start();
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'MANAGER') {
    header("Location: ../public/login.php");
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <title>Club Management</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<!-- include thanh slidebar -->
<div class="app">
<?php include '../includes/sidebar_manager.php'; ?>
</div>


<h2>Hello Manager</h2>
<p>Email: <?= $_SESSION['email'] ?></p>

<a href="../auth/logout.php">Đăng xuất</a>
