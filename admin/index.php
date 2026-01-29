<?php
session_start();
require_once '../config/database.php';
require_once '../includes/get_user.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: ../public/login.php");
    exit;
}

$result = $conn->query("
    SELECT 
        SUM(CASE WHEN roleID = 1 THEN 1 ELSE 0 END) AS total_admin,
        SUM(CASE WHEN roleID = 2 THEN 1 ELSE 0 END) AS total_manager,
        SUM(CASE WHEN roleID = 3 THEN 1 ELSE 0 END) AS total_user
    FROM users
");
$stats = $result->fetch_assoc();

$roles = [];
$r = $conn->query("SELECT roleID, role_name FROM roles");
while ($row = $r->fetch_assoc()) {
    $roles[] = $row;
}

$search = isset($_GET['search']) ? "%".$_GET['search']."%" : "%%";

$stmt = $conn->prepare("
    SELECT u.userID, u.full_name, u.email, u.roleID, r.role_name
    FROM users u
    JOIN roles r ON u.roleID = r.roleID
    WHERE u.full_name LIKE ? OR u.email LIKE ?
    ORDER BY u.roleID ASC, u.created_at DESC
");
$stmt->bind_param("ss", $search, $search);
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Phân quyền</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/sidebar_admin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-[#F8FAFF] min-h-screen p-2 md:p-4">

<div id="sidebar-overlay" onclick="toggleSidebar()"
     class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

<div class="flex h-[calc(100vh-1rem)] md:h-[calc(100vh-2rem)] overflow-hidden gap-4">

    <?php include '../admin/sidebar.php'; ?>

    <main class="flex-1 flex flex-col overflow-hidden min-w-0">

        <div class="flex items-center gap-3 mb-2">
            <button onclick="toggleSidebar()"
                class="lg:hidden p-2 bg-white rounded-xl shadow border">
                <i class="bi bi-list text-2xl"></i>
            </button>
            <div class="flex-1">
                <?php include '../includes/header.php'; ?>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto bg-white/40 rounded-[30px] p-4 md:p-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white/80 backdrop-blur p-6 rounded-[28px] shadow-xl flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 uppercase">Admin</p>
                        <p class="text-3xl font-black text-slate-800"><?= $stats['total_admin'] ?></p>
                        <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full bg-emerald-100 text-emerald-700">
                            Phân quyền
                        </span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">
                        <i class="bi bi-shield-check text-indigo-600 text-xl"></i>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur p-6 rounded-[28px] shadow-xl flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500 uppercase">Manager</p>
                        <p class="text-3xl font-black text-slate-800"><?= $stats['total_manager'] ?></p>
                        <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full bg-amber-100 text-amber-700">
                            Quản lý CLB
                        </span>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center">
                        <i class="bi bi-person-check text-orange-600 text-xl"></i>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur p-6 rounded-[28px] shadow-xl flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-500 uppercase">User</p>
                    <p class="text-3xl font-black text-slate-800"><?= $stats['total_user'] ?></p>
                    <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full bg-slate-100 text-slate-600">
                        Thành viên
                    </span>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                    <i class="bi bi-person text-slate-600 text-xl"></i>
                </div>
                </div>

            </div>

            <div class="bg-white rounded-[30px] p-6 shadow border">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                    <h3 class="text-xl font-black">Danh sách tài khoản</h3>

                    <form method="GET">
                        <input
                            name="search"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                            placeholder="Tìm tên hoặc email"
                            class="px-4 py-2 border rounded-xl"
                        >
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                        <tr class="bg-slate-100">
                            <th class="px-6 py-3 text-left">Tên</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Quyền</th>
                            <th class="px-6 py-3 text-right">Đổi quyền</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y">
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="px-6 py-3 font-semibold">
                                    <?= htmlspecialchars($u['full_name']) ?>
                                </td>
                                <td class="px-6 py-3">
                                    <?= htmlspecialchars($u['email']) ?>
                                </td>
                                <td class="px-6 py-3">
                                    <?php
                                    $roleClass = match ($u['role_name']) {
                                        'Admin' => 'bg-emerald-100 text-emerald-700',
                                        'Manager'              => 'bg-amber-100 text-amber-700',
                                        default                => 'bg-slate-100 text-slate-600'
                                    };
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $roleClass ?>">
                                        <?= $u['role_name'] ?>
                                    </span>
                                </td>

                                <td class="px-6 py-3 text-right">
                                    <?php if ($u['userID'] != $_SESSION['userID']): ?>
                                        <form action="update_role.php" method="POST" class="inline-flex gap-2">
                                            <input type="hidden" name="userID" value="<?= $u['userID'] ?>">
                                            <select
                                            name="roleID"
                                            class="px-3 py-1.5 rounded-lg border border-slate-200
                                                bg-white shadow-sm focus:ring-2 focus:ring-indigo-500">
                                                <?php foreach ($roles as $r): ?>
                                                    <option value="<?= $r['roleID'] ?>"
                                                        <?= $u['roleID'] == $r['roleID'] ? 'selected' : '' ?>>
                                                        <?= $r['role_name'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button class="px-3 py-1 bg-emerald-500 text-white rounded">
                                                Lưu
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400">Bạn</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('main-sidebar')?.classList.toggle('show');
    document.getElementById('sidebar-overlay')?.classList.toggle('hidden');
}
</script>

</body>
</html>
