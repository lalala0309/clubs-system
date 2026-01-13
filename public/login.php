<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login | Clubs System</title>
</head>
<body>

<h2>Đăng nhập hệ thống CLB</h2>

<a href="../auth/google-login.php">
    <button>Đăng nhập bằng Google</button>
</a>

</body>
</html>
