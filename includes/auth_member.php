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
  Chỉ MEMBER mới được vào
================================= */
$role = $_SESSION['role'] ?? null;

if ($role !== 'MEMBER') {
    // Nếu là admin hoặc manager thì đá về trang admin
    if ($role === 'ADMIN' || $role === 'MANAGER') {
        header("Location: ../manager/home.php");
    } else {
        header("Location: ../public/login.php");
    }
    exit;
}