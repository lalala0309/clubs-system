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
        body { font-family: 'Inter', sans-serif; }
        
        /* Hiệu ứng khi chọn CLB để xem chi tiết (Radio checked) */
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
            width: 0;
            opacity: 0;
            overflow: hidden;
        }
        
        #right-panel.active {
            width: 400px;
            opacity: 1;
        }
    </style>
</head>
<body class="bg-[#F8FAFF] min-h-screen p-4"> 
    <div class="flex h-[calc(100vh-2rem)] overflow-hidden gap-4">
    
    <?php include '../includes/sidebar_member.php'; ?>

        <main class="flex-1 flex flex-col overflow-hidden min-w-0">
            <!-- <header class="flex items-center justify-between px-10 py-5 bg-white/60 backdrop-blur-md rounded-[35px] mb-4 border border-white">
                <div class="relative w-96">
                    <span class="absolute inset-y-0 left-5 flex items-center text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" placeholder="Tìm kiếm CLB của bạn..." class="w-full pl-14 pr-6 py-3 bg-white/80 rounded-[20px] border-none shadow-sm focus:ring-2 focus:ring-indigo-400 outline-none transition font-medium">
                </div>

                <div class="flex items-center gap-4 bg-white px-5 py-2 rounded-[25px] shadow-sm border border-slate-50">
                    <div class="text-right">
                        <p class="text-sm font-black text-slate-800">Nguyễn Văn A</p>
                        <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-tighter">ID: SV12345</p>
                    </div>
                    <div class="w-11 h-11 rounded-[15px] bg-slate-200 overflow-hidden border-2 border-indigo-50">
                        <img src="https://i.pravatar.cc/150?u=a" class="w-full h-full object-cover">
                    </div>
                </div>
            </header> -->
            <?php include '../includes/header.php'; ?>
            <div class="flex-1 overflow-y-auto bg-white/40 backdrop-blur-sm rounded-[45px] p-8 border border-white mt-3">
                <div class="mb-6"> <div class="flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight uppercase">Câu lạc bộ của tôi</h2>
                    <p class="text-[13px] text-slate-400 mt-0.5 font-medium">Bạn đang tham gia <?php echo count($myClubs); ?> câu lạc bộ chính thức</p>
                </div>
                <div class="pb-1">
                    <i class="bi bi-grid-fill text-indigo-500 text-xl"></i>
                </div>
            </div>
    <div class="h-[2px] w-full bg-slate-200 mt-4 relative">
        <div class="absolute left-0 top-0 h-full w-20 bg-indigo-500"></div>
    </div>
</div>
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <?php foreach ($myClubs as $clb): ?>
<div class="relative">
    <input type="radio"
           name="court_select"
           id="c-<?php echo $clb['clubID']; ?>"
           class="hidden court-input"
           onchange="showBookingPanel(
               '<?php echo htmlspecialchars($clb['club_name']); ?>',
               'Thành viên chính thức'
           )">

    <label for="c-<?php echo $clb['clubID']; ?>"
           class="court-card p-5 flex items-center justify-between shadow-sm group">

        <div class="flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-3xl transition-transform group-hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                     class="w-9 h-9 text-slate-700 block">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1z
                             m4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6
                             m-5.784 6A2.24 2.24 0 0 1 5 13
                             c0-1.355.68-2.75 1.936-3.72
                             A6.3 6.3 0 0 0 5 9
                             c-4 0-5 3-5 4s1 1 1 1z
                             M4.5 8a2.5 2.5 0 1 0 0-5
                             2.5 2.5 0 0 0 0 5"/>
                </svg>
            </div>

            <div>
                <h3 class="text-lg font-bold text-slate-800">
                    <?php echo htmlspecialchars($clb['club_name']); ?>
                </h3>
                <p class="text-sm text-indigo-500 font-semibold">
                    Thành viên chính thức
                </p>
                <p class="text-[11px] text-slate-400 uppercase tracking-wider mt-1">
                    Tham gia: <?php echo date('d/m/Y', strtotime($clb['join_date'])); ?>
                </p>
            </div>
        </div>

        <div class="status-icon w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center
                    text-slate-300 transition-all duration-300
                    group-hover:bg-indigo-600 group-hover:text-white
                    group-hover:shadow-lg group-hover:shadow-indigo-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </div>
    </label>
</div>
<?php endforeach; ?>

                 
                </div>
            </div>
        </main>

        <aside id="right-panel" class="bg-white flex flex-col shrink-0 shadow-2xl rounded-l-[40px] border-l border-indigo-50">
            <div class="p-8 h-full flex flex-col w-[400px]">
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 id="display-name" class="text-xl font-black text-indigo-600 uppercase">Tên CLB</h2>
                        <p id="display-role" class="text-xs font-bold text-slate-400"></p>
                    </div>
                    <button onclick="closePanel()" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">✕</button>
                </div>
                <div class="flex-1 space-y-6">
                    <div class="bg-indigo-50/50 p-6 rounded-[30px] border border-indigo-100">
                        <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full"></span> Thông tin câu lạc bộ
                        </h4>
                        <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex justify-between">
                            <span>Ngày thành lập</span>
                            <span id="founded" class="font-bold"></span>
                        </li>

                        <li class="flex justify-between">
                            <span>Thành viên</span>
                            <span id="members" class="font-bold"></span>
                        </li>

                        <li class="flex justify-between">
                            <span>Môn thể thao</span>
                            <span id="sports" class="font-bold"></span>
                        </li>

                        <li class="flex justify-between">
                            <span>Số lượng sân</span>
                            <span id="grounds" class="font-bold"></span>
                        </li>

                        </ul>
                    </div>
                </div>
                <div class="flex-1 space-y-6">
                    <div class="p-6 min-h-[200px]">
                        <h4 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full"></span> Lịch đã đặt tuần này
                        </h4>
                        <ul class="space-y-3 text-sm text-slate-600">
                            <li class="flex justify-between"><span>Thứ 2 (18:00-19:00)</span> <span class="font-bold">Sân số 1</span></li>
                            <li class="flex justify-between"><span>Thứ 5 (17:00-18:00)</span> <span class="font-bold">Sân số 2</span></li>
                        </ul>
                    </div>

                    <!-- <div class="p-6">
                        <h4 class="text-sm font-bold text-slate-800 mb-4">Thông báo mới nhất</h4>
                        <p class="text-xs text-slate-500 leading-relaxed italic">"Chuẩn bị cho giải đấu CTUMP Open vào tháng sau..."</p>
                    </div> -->
                </div>

            
                <button class="mt-auto w-full py-4 bg-slate-800 text-white rounded-[22px] font-bold shadow-xl hover:bg-slate-900 transition transform active:scale-95">
                    Rời câu lạc bộ
                </button>
            </div>
        </aside>
    </div>

    <script>
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