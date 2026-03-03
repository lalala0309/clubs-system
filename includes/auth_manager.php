<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ==============================
  Bắt buộc đăng nhập
================================= */
if (!isset($_SESSION['userID'])) {
    header("Location: ../public/login.php");
    exit;
}

/* ==============================
 Chỉ admin và manager mới được vào
================================= */
$role = $_SESSION['role'] ?? null;

if ($role !== 'ADMIN' && $role !== 'MANAGER') {
    header("Location: ../member/home.php");
    exit;
}