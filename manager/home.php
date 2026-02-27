<?php
session_start();
require_once '../config/database.php';
require_once '../includes/get_user.php';

// Lấy dữ liệu thành viên theo từng CLB (Sử dụng cho biểu đồ cột và bảng)
$clubStatsResult = $conn->query("
    SELECT c.club_name, COUNT(m.userID) AS total_members 
    FROM clubs c
    LEFT JOIN club_members m ON c.clubID = m.clubID
    GROUP BY c.clubID, c.club_name;
");

$barLabels = [];
$barMembers = [];
$clubDataArray = [];

while ($row = $clubStatsResult->fetch_assoc()) {
    $clubDataArray[] = $row;
    $barLabels[] = $row['club_name'];
    $barMembers[] = (int) $row['total_members'];
}
$totalClubs = count($clubDataArray);

// Tổng lượt đăng ký tuần
$weeklyResult = $conn->query("
    SELECT COUNT(*) AS total_week
    FROM bookings
    WHERE YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1)
");
$totalWeek = $weeklyResult->fetch_assoc()['total_week'] ?? 0;

// Tổng thành viên toàn hệ thống (Role = 3 là Member)
$totalUsersResult = $conn->query("
    SELECT COUNT(*) AS total_users FROM users WHERE roleID = 3
");
$totalUsers = $totalUsersResult->fetch_assoc()['total_users'] ?? 0;

// Tổng số sân hiện có
$totalGroundsResult = $conn->query("SELECT COUNT(*) AS total_grounds FROM grounds");
$totalGrounds = $totalGroundsResult->fetch_assoc()['total_grounds'] ?? 0;

// Dữ liệu biểu đồ đường (7 ngày gần nhất tính từ hôm nay)
$chartDataQuery = $conn->query("
    SELECT DATE(booking_date) as date, COUNT(*) as count 
    FROM bookings 
    WHERE booking_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(booking_date)
    ORDER BY date ASC
");
$lineLabels = [];
$lineCounts = [];
while ($row = $chartDataQuery->fetch_assoc()) {
    $lineLabels[] = date('d/m', strtotime($row['date']));
    $lineCounts[] = (int) $row['count'];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>TRANG CHỦ - CTUMP Clubs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <link rel="stylesheet" href="../assets/css/sidebar_member.css">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFF;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
</head>

<body class="p-2 md:p-4">

    <div class="flex h-[calc(100vh-1rem)] md:h-[calc(100vh-2rem)] overflow-hidden gap-4">
        <?php include '../includes/sidebar_manager.php'; ?>

        <main class="flex-1 flex flex-col overflow-hidden min-w-0">
            <div class="flex items-center gap-3 mb-2">
                <button onclick="toggleSidebar()"
                    class="lg:hidden p-2 bg-white rounded-xl shadow border hover:bg-slate-50">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div class="flex-1">
                    <?php include '../includes/header.php'; ?>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto bg-white/40 rounded-[30px] p-4 md:p-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div
                        class="bg-white p-5 rounded-[24px] shadow-sm flex items-center justify-between border border-slate-100">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Câu lạc bộ</p>
                            <p class="text-2xl font-black text-indigo-600"><?= $totalClubs ?></p>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500">
                            <i class="bi bi-collection text-xl"></i></div>
                    </div>
                    <div
                        class="bg-white p-5 rounded-[24px] shadow-sm flex items-center justify-between border border-slate-100">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Thành viên</p>
                            <p class="text-2xl font-black text-orange-600"><?= $totalUsers ?></p>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500">
                            <i class="bi bi-people text-xl"></i></div>
                    </div>
                    <div
                        class="bg-white p-5 rounded-[24px] shadow-sm flex items-center justify-between border border-slate-100">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tổng số sân</p>
                            <p class="text-2xl font-black text-blue-600"><?= $totalGrounds ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500"><i
                                class="bi bi-building-check text-xl"></i></div>
                    </div>
                    <div
                        class="bg-white p-5 rounded-[24px] shadow-sm flex items-center justify-between border border-slate-100">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Booking tuần</p>
                            <p class="text-2xl font-black text-emerald-600"><?= $totalWeek ?></p>
                        </div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                            <i class="bi bi-calendar-check text-xl"></i></div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="bg-white rounded-[28px] p-6 shadow-sm border border-slate-100">
                        <h3 class="text-sm font-bold text-slate-500 uppercase mb-4 tracking-tight">Phân bổ thành viên
                            CLB</h3>
                        <div class="w-full h-[400px]"> <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-[28px] p-6 shadow-sm border border-slate-100 flex flex-col">
                        <h3 class="text-sm font-bold text-slate-500 uppercase mb-4 tracking-tight">Xu hướng đặt sân (7
                            ngày gần nhất)</h3>
                        <div class="flex-1 flex items-center">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white rounded-[28px] p-6 shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-black text-slate-800">Chi tiết thành viên</h3>
                            <span
                                class="text-[10px] text-slate-400 italic bg-slate-50 px-2 py-1 rounded">Real-time</span>
                        </div>
                        <div class="overflow-y-auto max-h-[350px] pr-2">
                            <table class="w-full text-sm text-left">
                                <thead class="text-slate-400 border-b border-slate-50 sticky top-0 bg-white">
                                    <tr>
                                        <th class="pb-3 font-semibold">Tên câu lạc bộ</th>
                                        <th class="pb-3 font-semibold text-right">Thành viên</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php foreach ($clubDataArray as $row): ?>
                                        <tr class="hover:bg-slate-50/50 transition-colors group">
                                            <td
                                                class="py-4 font-medium text-slate-700 group-hover:text-indigo-600 transition-colors">
                                                <?= htmlspecialchars($row['club_name']) ?>
                                            </td>
                                            <td class="py-4 text-right">
                                                <span
                                                    class="px-3 py-1 bg-slate-50 text-slate-600 group-hover:bg-indigo-50 group-hover:text-indigo-600 rounded-full font-bold transition-all">
                                                    <?= $row['total_members'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        // 1. Khởi tạo Biểu đồ Cột (Bar Chart)
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: <?= json_encode($barLabels) ?>,
                datasets: [{
                    label: 'Số thành viên',
                    data: <?= json_encode($barMembers) ?>,
                    backgroundColor: 'rgba(79, 70, 229, 0.7)',
                    borderColor: '#4f46e5',
                    borderWidth: 1,
                    borderRadius: 12,
                    hoverBackgroundColor: '#4f46e5',
                    barThickness: 65, // Điều chỉnh độ rộng cột vừa phải
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f8fafc', drawBorder: false },
                        ticks: {
                            stepSize: 1,
                            font: { size: 12, weight: '600' },
                            color: '#94a3b8'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11, weight: '500' },
                            color: '#64748b'
                        }
                    }
                }
            }
        });

        // 2. Khởi tạo Biểu đồ Đường 
        const ctxLine = document.getElementById('lineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: <?= json_encode($lineLabels) ?>,
                datasets: [{
                    label: 'Lượt đặt',
                    data: <?= json_encode($lineCounts) ?>,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.05)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f8fafc' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Hàm điều khiển Sidebar cho Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            if (sidebar) sidebar.classList.toggle('show');
        }
    </script>
</body>

</html>