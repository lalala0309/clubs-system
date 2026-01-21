<?php
session_start();
require_once '../includes/get_user.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTUMP Clubs - Admin Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/sidebar_member.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
         body { font-family: 'Inter', sans-serif; }
        .sidebar-item-active { background-color: #3f51b5; color: white; border-radius: 12px; }
        .club-card.hidden-club { display: none; }

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

        /* Hiệu ứng trượt cho Form bên phải */
        #add-club-panel {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(100%);
        }
        #add-club-panel.open { transform: translateX(0); }
        
        #overlay {
            display: none;
            background-color: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
        }
        #overlay.active { display: block; }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFF] min-h-screen p-2 md:p-4">

    <div id="overlay" class="fixed inset-0 z-40" onclick="toggleAddClubForm()"></div>

    <div class="flex h-[calc(100vh-1rem)] md:h-[calc(100vh-2rem)] overflow-hidden gap-4">
    
    <?php include '../includes/sidebar_manager.php'; ?>

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
                <h2 id="page-title" class="text-2xl font-bold text-slate-800">Danh sách câu lạc bộ</h2>
                
                <div class="flex items-center gap-3">
                    <button id="back-btn" onclick="showAllClubs()" class="hidden flex items-center gap-2 text-indigo-600 font-bold hover:bg-indigo-50 px-4 py-2 rounded-xl transition">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button id="add-club-btn" onclick="toggleAddClubForm()" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 transition flex items-center gap-2 shadow-lg shadow-indigo-100">
                        <i class="fas fa-plus"></i> Thêm câu lạc bộ
                    </button>
                </div>


            </div>

            <div class="h-[2px] w-full bg-slate-200 mt-3 md:mt-4 relative">
                        <div class="absolute left-0 top-0 h-full w-12 md:w-20 bg-indigo-500"></div>
                    </div>
        </div>



            <div id="club-container" class="space-y-4">
                
                <div class="club-card bg-white border rounded-2xl overflow-hidden shadow-sm" id="club-football">
                    <div class="club-header p-5 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition" onclick="openClub('club-football')">
                        <div class="flex items-center gap-4">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-blue-50 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl transition-transform group-hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="w-6 h-6 md:w-9 md:h-9 text-slate-700 block">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1z m4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6 m-5.784 6A2.24 2.24 0 0 1 5 13 c0-1.355.68-2.75 1.936-3.72 A6.3 6.3 0 0 0 5 9 c-4 0-5 3-5 4s1 1 1 1z M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                    </svg>
                                </div>
                            <div>
                                <h3 class="text-sm md:text-lg font-bold text-slate-800 line-clamp-1">Câu lạc bộ Bóng đá</h3>
                                <p class="text-xs text-slate-500">120 thành viên • 3 yêu cầu mới</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <button onclick="event.stopPropagation(); toggleApprovals('approvals-football');" class="flex items-center gap-2 bg-orange-50 text-orange-600 px-4 py-2 rounded-full border border-orange-100 hover:bg-orange-600 hover:text-white transition group">
                                <i class="fas fa-user-clock"></i>
                                <span class="text-xs font-bold uppercase">Phê duyệt</span>
                                <span class="bg-orange-600 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full border-2 border-white group-hover:bg-white group-hover:text-orange-600">3</span>
                            </button>
                            <i class="fas fa-chevron-right text-slate-400 chevron-icon"></i>
                        </div>
                    </div>
                    <div class="club-details hidden p-6 bg-slate-50/50 border-t">
                        <div id="approvals-football" class="hidden mb-6 space-y-3">
                            <h4 class="text-orange-700 font-bold text-xs uppercase tracking-widest">Yêu cầu chờ</h4>
                            <div class="bg-white p-4 rounded-xl border flex justify-between items-center hover:bg-slate-50 transition">
    <div>
        <p class="font-bold text-slate-800">
            Nguyễn Văn An <span class="text-slate-400 font-normal"></span>
        </p>
        <p class="text-sm text-slate-500">
            nguyenvanan@gmail.com • Gửi ngày: 12/01/2026
        </p>
    </div>

    <button class="text-green-600 font-bold hover:text-green-700 transition">
        Duyệt
    </button>
</div>

                        </div>
                        <h4 class="text-slate-700 font-bold text-xs uppercase mb-3">Danh sách thành viên</h4>
                        <div class="bg-white rounded-xl border overflow-hidden">
                        <table class="w-full text-sm">
    <thead>
        <tr class="bg-slate-50 border-b text-slate-600">
            <th class="p-3 text-left">Họ tên</th>
            <th class="p-3 text-left">Gmail</th>
            <th class="p-3 text-center">Mã SV</th>
            <th class="p-3 text-center">Ngày tham gia</th>
            <th class="p-3 text-center">Ngày đóng phí</th>
            <th class="p-3 text-center">Ngày hết hạn</th>
        </tr>
    </thead>
    <tbody>
        <tr class="border-b hover:bg-slate-50 transition">
            <td class="p-3 font-medium">Lê Thái Thảo Vy</td>
            <td class="p-3 text-slate-600">thaovy.le@gmail.com</td>
            <td class="p-3 text-center text-slate-500">B2204980</td>
            <td class="p-3 text-center">10/09/2024</td>
            <td class="p-3 text-center">10/09/2024</td>
            <td class="p-3 text-center text-green-600 font-semibold">10/09/2025</td>
        </tr>

        <tr class="border-b hover:bg-slate-50 transition">
            <td class="p-3 font-medium">Trần Minh Quân</td>
            <td class="p-3 text-slate-600">quanm.tran@gmail.com</td>
            <td class="p-3 text-center text-slate-500">B2204123</td>
            <td class="p-3 text-center">15/10/2024</td>
            <td class="p-3 text-center">15/10/2024</td>
            <td class="p-3 text-center text-green-600 font-semibold">15/10/2025</td>
        </tr>
    </tbody>
</table>

                        </div>
                    </div>
                </div>

                <div class="club-card bg-white border rounded-2xl overflow-hidden shadow-sm" id="club-volunteer">
                    <div class="club-header p-5 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition" onclick="openClub('club-volunteer')">
                        <div class="flex items-center gap-4">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-blue-50 rounded-xl md:rounded-2xl flex items-center justify-center text-xl md:text-3xl transition-transform group-hover:scale-110">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="w-6 h-6 md:w-9 md:h-9 text-slate-700 block">
                                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1z m4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6 m-5.784 6A2.24 2.24 0 0 1 5 13 c0-1.355.68-2.75 1.936-3.72 A6.3 6.3 0 0 0 5 9 c-4 0-5 3-5 4s1 1 1 1z M4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                    </svg>
                                </div>
                            <div>
                                <h3 class="text-sm md:text-lg font-bold text-slate-800 line-clamp-1">Câu lạc bộ Cầu lông</h3>
                                <p class="text-xs text-slate-500">250 thành viên • 15 yêu cầu mới</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <button onclick="event.stopPropagation(); toggleApprovals('approvals-volunteer');" class="flex items-center gap-2 bg-orange-50 text-orange-600 px-4 py-2 rounded-full border border-orange-100 hover:bg-orange-600 hover:text-white transition group">
                                <i class="fas fa-user-clock"></i>
                                <span class="text-xs font-bold uppercase">Phê duyệt</span>
                                <span class="bg-orange-600 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full border-2 border-white group-hover:bg-white group-hover:text-orange-600">15</span>
                            </button>
                            <i class="fas fa-chevron-right text-slate-400 chevron-icon"></i>
                        </div>
                    </div>
                    <div class="club-details hidden p-6 bg-slate-50/50 border-t">
                        <div id="approvals-volunteer" class="hidden mb-6">
                            <p class="text-orange-600 italic text-sm">Hiện có 15 yêu cầu mới chưa xử lý...</p>
                        </div>
                        <p class="text-center text-slate-400 py-10">Dữ liệu thành viên Tình nguyện đang tải...</p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <aside id="add-club-panel" class="fixed right-0 top-0 h-full w-96 bg-white shadow-2xl z-50 flex flex-col">
        <div class="p-6 border-b flex justify-between items-center bg-indigo-50">
            <h3 class="font-bold text-indigo-900 uppercase tracking-wider">Thêm câu lạc bộ mới</h3>
            <button onclick="toggleAddClubForm()" class="text-slate-400 hover:text-red-500 transition"><i class="fas fa-times text-xl"></i></button>
        </div>
        <form class="p-6 space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Tên câu lạc bộ</label>
                <input type="text" class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Icon (FontAwesome)</label>
                <input type="text" placeholder="fa-music" class="w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
            <button type="button" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl shadow-lg">Lưu câu lạc bộ</button>
        </form>
    </aside>
</div>
    <script>
        function toggleAddClubForm() {
            document.getElementById('add-club-panel').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('active');
        }

        function openClub(clubId) {
            const allCards = document.querySelectorAll('.club-card');
            allCards.forEach(card => {
                if (card.id === clubId) {
                    card.querySelector('.club-details').classList.remove('hidden');
                    card.querySelector('.chevron-icon')?.classList.add('hidden');
                    document.getElementById('page-title').innerText = "Quản lý thành viên";
                    card.querySelector('.club-header').onclick = null;
                    card.querySelector('.club-header').style.cursor = "default";
                } else {
                    card.classList.add('hidden-club');
                }
            });
            document.getElementById('back-btn').classList.remove('hidden');
            document.getElementById('add-club-btn').classList.add('hidden');
        }

        function showAllClubs() {
            const allCards = document.querySelectorAll('.club-card');
            allCards.forEach(card => {
                card.classList.remove('hidden-club');
                card.querySelector('.club-details').classList.add('hidden');
                card.querySelector('.chevron-icon')?.classList.remove('hidden');
                const id = card.id;
                const header = card.querySelector('.club-header');
                header.onclick = function() { openClub(id); };
                header.style.cursor = "pointer";
            });
            document.getElementById('back-btn').classList.add('hidden');
            document.getElementById('add-club-btn').classList.remove('hidden');
            document.getElementById('page-title').innerText = "Danh sách câu lạc bộ";
        }

        function toggleApprovals(id) {
            const section = document.getElementById(id);
            if(section) section.classList.toggle('hidden');
        }
    </script>
</body>
</html>