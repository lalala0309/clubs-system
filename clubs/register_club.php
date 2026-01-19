<?php
session_start();
require_once '../includes/get_user.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống đăng ký Câu lạc bộ thể thao - CTUMP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/sidebar_member.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .club-card:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
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

        /* Responsive di động */
        #main-sidebar.show { transform: translateX(0); }
        .sidebar-overlay { display: none; }
        .sidebar-overlay.active { display: block; }

        @media (max-width: 1024px) {
            #right-panel.active {
                position: fixed;
                right: 0;
                top: 0;
                height: 100vh;
                z-index: 60;
                width: 100%;
                border-radius: 0;
            }
        }
    </style>
</head>
<body class="bg-[#F8FAFF] min-h-screen p-2 md:p-4"> 
    <div id="sidebar-overlay" onclick="toggleSidebar()" class="sidebar-overlay fixed inset-0 bg-black/50 z-40 lg:hidden"></div>

    <div class="flex h-[calc(100vh-1rem)] md:h-[calc(100vh-2rem)] gap-4 overflow-hidden">
        
        <?php include '../includes/sidebar_member.php'; ?>

        <main class="flex-1 flex flex-col overflow-hidden min-w-0">
            <div class="flex items-center gap-3 mb-2">
                <button onclick="toggleSidebar()" class="lg:hidden p-2.5 bg-white rounded-xl shadow-sm border border-slate-100 text-indigo-600 active:scale-95 transition">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <div class="flex-1">
                    <?php include '../includes/header.php'; ?>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto bg-white/40 backdrop-blur-sm rounded-[30px] md:rounded-[45px] p-5 md:p-10 border border-white">
                <div class="mb-6"> 
                    <div class="flex items-end justify-between">
                        <div>
                            <h2 class="text-base text-lg md:text-2xl font-black text-slate-800 tracking-tight uppercase">Đăng ký câu lạc bộ</h2>
                            <p class="text-[11px] md:text-[13px] text-slate-400 mt-0.5 font-medium">Đăng ký để sử dụng các tiện ích của câu lạc bộ</p>
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
                    <?php 
                        $clubs = require '../clubs/get_all_clubs.php';
                        foreach($clubs as $clb):
                    ?>
                    <div class="relative">
                        <input type="radio" name="court_select" id="c-<?php echo $clb['id']; ?>" class="hidden court-input" onchange="loadClubDetail(<?php echo $clb['id']; ?>)">
                        <label for="c-<?php echo $clb['id']; ?>" class="club-card p-2 md:p-5 flex items-center justify-between shadow-sm hover:shadow-md cursor-pointer group bg-white rounded-[20px] md:rounded-[25px] transition-all">
                            <div class="flex items-center gap-4 md:gap-5">
                                <div class="w-12 h-12 md:w-14 md:h-14 <?php echo $clb['bg']; ?> rounded-xl md:rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="w-7 h-7 md:w-9 md:h-9 text-slate-700 block">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm md:text-lg font-bold text-slate-800 line-clamp-1"><?php echo $clb['name']; ?></h3>
                                    <p class="text-[11px] md:text-sm text-slate-400 font-medium line-clamp-1"><?php echo $clb['desc']; ?></p>
                                </div>
                            </div>
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-indigo-600 group-hover:text-white transition-all shrink-0">
                                <i class="bi bi-arrow-right-short text-2xl"></i>
                            </div>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <aside id="right-panel" class="bg-white flex flex-col shrink-0 shadow-2xl lg:rounded-l-[40px] border-l border-indigo-50">
            <div class="p-6 md:p-8 h-full flex flex-col w-full lg:w-[400px]">
                <div class="flex justify-between items-center mb-6 md:mb-8">
                    <div>
                        <h2 id="display-name" class="text-lg md:text-xl font-black text-indigo-600 uppercase">Tên CLB</h2>
                        <p id="display-role" class="text-[10px] font-bold text-slate-400"></p>
                    </div>
                    <button onclick="closePanel()" class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all">✕</button>
                </div>
                
                <div class="flex-1 space-y-5 md:space-y-6 overflow-y-auto pr-1">
                    <div class="bg-indigo-50/50 p-5 md:p-6 rounded-[25px] md:rounded-[30px] border border-indigo-100">
                        <h4 class="text-xs md:text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full"></span> Thông tin câu lạc bộ
                        </h4>
                        <ul class="space-y-3 text-[12px] md:text-sm text-slate-600">
                            <li class="flex justify-between"><span>Ngày thành lập</span> <span id="founded" class="font-bold text-slate-800"></span></li>
                            <li class="flex justify-between"><span>Thành viên</span> <span id="members" class="font-bold text-slate-800"></span></li>
                            <li class="flex justify-between"><span>Môn thể thao</span> <span id="sports" class="font-bold text-slate-800"></span></li>
                            <li class="flex justify-between"><span>Số lượng sân</span> <span id="grounds" class="font-bold text-slate-800"></span></li>
                        </ul>
                    </div>

                    <div class="px-4">
                        <h4 class="text-xs md:text-sm font-bold text-slate-800 mb-2">Lưu ý</h4>
                        <p class="text-[11px] md:text-xs text-slate-500 leading-relaxed italic">"Việc đăng ký tham gia câu lạc bộ thể hiện sự đồng ý của bạn trong việc đóng góp phí vận hành và tuân thủ quy định sử dụng cơ sở vật chất."</p>
                    </div>
                </div>

                <button id="btn-register" class="mt-6 w-full py-3.5 md:py-4 bg-blue-600 text-white rounded-[18px] md:rounded-[22px] font-bold shadow-xl hover:bg-blue-700 transition transform active:scale-95">
                    Đăng ký tham gia
                </button>
            </div>
        </aside>
    </div>

    <script>
        let selectedClubID = null;
        let selectedClubName = '';
        const panel = document.getElementById('right-panel');

        function toggleSidebar() {
            document.getElementById('main-sidebar').classList.toggle('show');
            document.getElementById('sidebar-overlay').classList.toggle('active');
        }

        function loadClubDetail(clubID) {
            fetch(`./get_club_detail.php?clubID=${clubID}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) return alert(data.error);
                    
                    selectedClubID = clubID;
                    selectedClubName = data.name;

                    document.getElementById('display-name').innerText = data.name;
                    document.getElementById('display-role').innerText = 'Câu lạc bộ thể thao';
                    document.getElementById('founded').innerText = data.founded;
                    document.getElementById('members').innerText = data.members;
                    document.getElementById('sports').innerText = data.sports;
                    document.getElementById('grounds').innerText = data.grounds;

                    panel.classList.add('active');
                })
                .catch(err => alert('Không thể tải dữ liệu CLB'));
        }

        function closePanel() {
            panel.classList.remove('active');
            selectedClubID = null;
            document.querySelectorAll('.court-input').forEach(i => i.checked = false);
        }

        document.getElementById('btn-register').addEventListener('click', () => {
            if (!selectedClubID) return alert('Vui lòng chọn câu lạc bộ');
            if (!confirm('Bạn chắc chắn muốn đăng ký tham gia câu lạc bộ này?')) return;

            fetch('./register_club_action.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ clubID: selectedClubID })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert(`Đăng ký thành công!\nBạn đã là thành viên của ${selectedClubName}`);
                    closePanel();
                    window.location.reload();
                }
            })
            .catch(err => alert('Lỗi khi đăng ký'));
        });
    </script>
</body>
</html>