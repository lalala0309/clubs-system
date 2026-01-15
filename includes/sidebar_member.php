<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="w-72 glass-sidebar border border-indigo-100 flex flex-col p-6 shadow-xl shrink-0">

    <!-- LOGO -->
    <div class="flex items-center gap-3 mb-10 px-2">
        <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center shadow-lg shadow-indigo-200">
            <span class="text-white font-bold text-xl"><img src="../assets/images/logo_ctump.png" alt=""></span>
        </div>
        <span class="text-xl font-extrabold text-slate-800 tracking-tight">CTUMP Clubs</span>
    </div>

    <!-- MENU -->
    <nav class="flex-1 space-y-3">

        <!-- DASHBOARD -->
        <a href="../member/home.php"
           class="<?php echo ($currentPage == 'home.php')
                ? 'active-menu flex items-center gap-4 px-5 py-3.5 font-bold shadow-lg shadow-indigo-100'
                : 'flex items-center gap-4 px-5 py-3.5 rounded-[22px] text-slate-500 hover:bg-indigo-50 transition font-semibold'; ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>

        <!-- ĐĂNG KÝ CLB -->
        <a href="../clubs/register_club.php"
           class="<?php echo ($currentPage == 'register_club.php')
                ? 'active-menu flex items-center gap-4 px-5 py-3.5 font-bold shadow-lg shadow-indigo-100'
                : 'flex items-center gap-4 px-5 py-3.5 rounded-[22px] text-slate-500 hover:bg-indigo-50 transition font-semibold'; ?>">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Đăng ký câu lạc bộ
        </a>

        <!-- ĐẶT SÂN -->
        <a href="../booking/booking_ground.php"
           class="<?php echo ($currentPage == 'booking_ground.php')
                ? 'active-menu flex items-center gap-4 px-5 py-3.5 font-bold shadow-lg shadow-indigo-100'
                : 'flex items-center gap-4 px-5 py-3.5 rounded-[22px] text-slate-500 hover:bg-indigo-50 transition font-semibold'; ?>">
             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Đặt sân
        </a>

    </nav>

    <!-- LOGOUT + SUPPORT -->
    <div class="space-y-4">


        <!-- SUPPORT -->
        <div class="bg-indigo-50 p-6 rounded-[35px]">
            <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest mb-2">Hỗ trợ</p>
            <p class="text-sm text-indigo-900 font-bold">
                Liên hệ ngay phòng Công tác Sinh viên.
            </p>
        </div>

                <!-- LOGOUT (MÀU ĐỎ) -->
                <a href="../auth/logout.php"
           class="flex items-center gap-4 px-5 py-3.5 rounded-[22px] font-bold
                  text-red-600 hover:bg-red-50 transition">

            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M17 16l4-4m0 0l-4-4m4 4H7"></path>
            </svg>
            Đăng xuất
        </a>


    </div>


</aside>
