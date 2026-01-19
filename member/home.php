<?php
session_start();
require_once '../includes/get_user.php';
require_once './get_my_clubs.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLB Đã Tham Gia - CTUMP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/sidebar_member.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* GIỮ NGUYÊN STYLE CỦA BẠN */
        body { font-family: 'Inter', sans-serif; }
        .court-input:checked + .court-card {
            border-color: #4F46E5;
            background-color: #f5f3ff;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.15);
        }
        .court-input:checked + .court-card .status-icon {
            background-color: #4F46E5 !important;
            color: white !important;
        }
        .active-menu {
            background: linear-gradient(135deg, #4F46E5 0%, #2A53A2 100%);
            color: white !important;
            border-radius: 24px; 
        }
        #right-panel {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0; opacity: 0; overflow: hidden;
        }
        #right-panel.active { width: 400px; opacity: 1; }

        /* Bổ sung để hiển thị sidebar trên mobile */
        #main-sidebar.show {
            transform: translateX(0);
        }
        
        /* Overlay khi mở menu trên mobile */
        .sidebar-overlay {
            display: none;
        }
        .sidebar-overlay.active {
            display: block;
        }

        /* Responsive cỡ chữ cho Right Panel trên Mobile */
        @media (max-width: 1024px) {
            #right-panel.active {
                position: fixed;
                right: 0;
                top: 0;
                height: 100vh;
                z-index: 60;
                width: 100%; /* Chiếm toàn màn hình trên mobile */
            }
        }
    </style>
</head>
<body class="bg-[#F8FAFF] min-h-screen p-2 md:p-4"> 

    <div id="sidebar-overlay" onclick="toggleSidebar()" class="sidebar-overlay fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <div class="flex h-[calc(100vh-1rem)] md:h-[calc(100vh-2rem)] overflow-hidden gap-4">
    
        <?php include '../includes/sidebar_member.php'; ?>

        <main class="flex-1 flex flex-col overflow-hidden min-w-0">
            <div class="flex items-center gap-3 mb-2">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 bg-white rounded-xl shadow-sm border border-slate-100 text-indigo-600">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div class="flex-1">
                    <?php include '../includes/header.php'; ?>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto bg-white/40 backdrop-blur-sm rounded-[30px] md:rounded-[45px] p-4 md:p-8 border border-white">
                <div class="mb-5 md:mb-6"> 
                    <div class="flex items-end justify-between">
                        <div>
                            <h2 class="text-lg md:text-2xl font-black text-slate-800 tracking-tight uppercase">Câu lạc bộ của tôi</h2>
                            <p class="text-[11px] md:text-[13px] text-slate-400 mt-0.5 font-medium">Bạn có <?php echo count($myClubs); ?> CLB chính thức</p>
                        </div>
                        <div class="pb-1 hidden md:block">
                            <i class="bi bi-grid-fill text-indigo-500 text-xl"></i>
                        </div>
                    </div>
                    <div class="h-[2px] w-full bg-slate-200 mt-3 md:mt-4 relative">
                        <div class="absolute left-0 top-0 h-full w-12 md:w-20 bg-indigo-500"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 md:gap-6">
                    <?php foreach ($myClubs as $clb): ?>
                    <div class="relative">
                        <input type="radio" name="court_select" id="c-<?php echo $clb['clubID']; ?>" class="hidden court-input"
                               onchange="showBookingPanel('<?php echo htmlspecialchars($clb['club_name']); ?>', 'Thành viên chính thức')">

                        <label for="c-<?php echo $clb['clubID']; ?>" class="court-card p-4 md:p-5 flex items-center justify-between shadow-sm group bg-white rounded-[20px] md:rounded-[25px] border border-transparent cursor-pointer transition-all">
                            <div class="flex items-center gap-3 md:gap-5">
                                <div class="w-10 h-10 md:w-14 md:h-14 bg-blue-50 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl transition-transform group-hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="w-6 h-6 md:w-9 md:h-9 text-slate-700 block">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1z m4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6 m-5.784 6A2.24 2.24 0 0 1 5 13 c0-1.355.68-2.75 1.936-3.72 A6.3 6.3 0 0 0 5 9 c-4 0-5 3-5 4s1 1 1 1z M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm md:text-lg font-bold text-slate-800 line-clamp-1"><?php echo htmlspecialchars($clb['club_name']); ?></h3>
                                    <p class="text-[10px] md:text-sm text-indigo-500 font-semibold uppercase md:capitalize">Thành viên</p>
                                    <p class="text-[9px] md:text-[11px] text-slate-400 mt-0.5">Tham gia: <?php echo date('d/m/Y', strtotime($clb['join_date'])); ?></p>
                                </div>
                            </div>
                            <div class="status-icon w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <aside id="right-panel" class="bg-white flex flex-col shrink-0 shadow-2xl lg:rounded-l-[40px] border-l border-indigo-50">
            <div class="p-5 md:p-8 h-full flex flex-col w-full lg:w-[400px]">
                <div class="flex justify-between items-center mb-6 md:mb-8">
                    <div>
                        <h2 id="display-name" class="text-lg md:text-xl font-black text-indigo-600 uppercase tracking-tight">Tên CLB</h2>
                        <p id="display-role" class="text-[10px] md:text-xs font-bold text-slate-400"></p>
                    </div>
                    <button onclick="closePanel()" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">✕</button>
                </div>
                
                <div class="flex-1 space-y-4 md:space-y-6 overflow-y-auto">
                    <div class="bg-indigo-50/50 p-4 md:p-6 rounded-[20px] md:rounded-[30px] border border-indigo-100">
                        <h4 class="text-xs md:text-sm font-bold text-slate-800 mb-3 md:mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-indigo-500 rounded-full"></span> Thông tin
                        </h4>
                        <ul class="space-y-2 md:space-y-3 text-[11px] md:text-sm text-slate-600">
                            <li class="flex justify-between"><span>Thành viên</span> <span id="members" class="font-bold text-slate-800">120</span></li>
                            <li class="flex justify-between"><span>Sân bãi</span> <span id="grounds" class="font-bold text-slate-800">2</span></li>
                        </ul>
                    </div>

                    <div class="p-4 md:p-6">
                        <h4 class="text-xs md:text-sm font-bold text-slate-800 mb-3 md:mb-4 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 md:w-2 md:h-2 bg-indigo-500 rounded-full"></span> Lịch tập tuần này
                        </h4>
                        <ul class="space-y-2 text-[11px] md:text-sm text-slate-600">
                            <li class="flex justify-between"><span>Thứ 2</span> <span class="font-bold">18:00 - 20:00</span></li>
                        </ul>
                    </div>
                </div>

                <button class="mt-auto w-full py-3 md:py-4 bg-slate-800 text-white rounded-[18px] md:rounded-[22px] text-sm md:text-base font-bold shadow-xl active:scale-95 transition">
                    Rời câu lạc bộ
                </button>
            </div>
        </aside>
    </div>

    <script>
        // Logic Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('active');
        }

        // Logic Panel chi tiết
        const panel = document.getElementById('right-panel');
        const displayName = document.getElementById('display-name');
        const displayRole = document.getElementById('display-role');

        function showBookingPanel(name, role) {
            displayName.innerText = name;
            displayRole.innerText = role;
            panel.classList.add('active');
        }

        function closePanel() {
            panel.classList.remove('active');
            document.querySelectorAll('.court-input').forEach(input => input.checked = false);
        }
    </script>
</body>
</html>