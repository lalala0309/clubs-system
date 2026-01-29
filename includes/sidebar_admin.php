<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside id="main-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white lg:static lg:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out glass-sidebar border border-indigo-100 flex flex-col p-6 shadow-xl h-full">

    <div class="flex items-center justify-between mb-10 px-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-indigo-600 rounded-full flex items-center justify-center shadow-lg shadow-indigo-200">
                <span class="text-white font-bold text-xl"><img src="../assets/images/logo_ctump.png" alt="CTUMP"></span>
            </div>
            <span class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight">CTUMP Clubs</span>
        </div>
        
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-500 hover:text-indigo-600">
            <i class="bi bi-x-lg text-2xl"></i>
        </button>
    </div>

    <nav class="space-y-1 mb-12">
        <a href="../admin/index.php"
           class="text-sm md:text-base <?php echo ($currentPage == 'index.php')
            ? 'active-menu flex items-center gap-4 px-5 py-2.5 font-bold shadow-lg shadow-indigo-100'
            : 'flex items-center gap-4 px-5 py-2.5 rounded-[22px] text-slate-500 hover:bg-indigo-50 transition font-semibold'; ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Quản lý phân quyền
        </a>
    </nav>

    <div class="flex-1 flex flex-col justify-end mt-auto pt-8 border-t border-slate-200">
        <a href="../auth/logout.php"
           class="flex items-center gap-4 px-5 py-3 text-sm md:text-base rounded-[22px] font-bold text-red-600 hover:bg-red-50 transition-all group mb-4">
            <div class="w-10 h-10 bg-red-50 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"></path>
                </svg>
            </div>
            <span>Đăng xuất</span>
        </a>
    </div>

</aside>
