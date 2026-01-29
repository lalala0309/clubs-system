<?php
session_start();
require_once '../config/database.php';

/* CHỈ ADMIN ĐƯỢC ĐỔI QUYỀN */
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../public/login.php");
    exit;
}

/* VALIDATE INPUT */
if (!isset($_POST['userID'], $_POST['roleID'])) {
    header("Location: roles.php");
    exit;
}

$userID = (int) $_POST['userID'];
$roleID = (int) $_POST['roleID'];

/* KHÔNG CHO ADMIN TỰ ĐỔI QUYỀN CHÍNH MÌNH */
if ($userID === (int)$_SESSION['userID']) {
    header("Location: roles.php?error=self");
    exit;
}

/* KIỂM TRA ROLE CÓ TỒN TẠI */
$checkRole = $conn->prepare("SELECT roleID FROM roles WHERE roleID = ?");
$checkRole->bind_param("i", $roleID);
$checkRole->execute();
$checkRole->store_result();

if ($checkRole->num_rows === 0) {
    header("Location: roles.php?error=invalid_role");
    exit;
}

/* UPDATE ROLE */
$stmt = $conn->prepare("
    UPDATE users 
    SET roleID = ?
    WHERE userID = ?
");
$stmt->bind_param("ii", $roleID, $userID);
$stmt->execute();

/* QUAY LẠI TRANG TRƯỚC */
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
