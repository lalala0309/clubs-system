<?php
session_start();
require_once '../includes/get_user.php';
require_once '../config/database.php'; // file connect DB của bạn

if (!isset($_GET['sportID'])) {
    header("Location: manage_grounds.php");
    exit;
}

$sportID = (int) $_GET['sportID'];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 flex items-center justify-center min-h-screen">

    <form action="handle_add_ground.php" method="POST"
        class="bg-white p-8 rounded-2xl w-full max-w-md space-y-5 shadow">

        <h2 class="text-xl font-bold text-indigo-600">Thêm sân mới</h2>

        <input type="hidden" name="sportID" value="<?= $sportID ?>">

        <div>
            <label class="text-sm font-semibold">Tên sân</label>
            <input name="name" required
                class="w-full mt-1 p-3 rounded-xl bg-slate-50 focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <label class="text-sm font-semibold">Vị trí</label>
            <input name="location" class="w-full mt-1 p-3 rounded-xl bg-slate-50 focus:ring-2 focus:ring-indigo-500">
        </div>

        <button class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700">
            Thêm sân
        </button>

    </form>

</body>

</html>