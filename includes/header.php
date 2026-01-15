<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>


<header class="flex items-center justify-between px-10 py-5 bg-white/60 backdrop-blur-md rounded-[35px] mb-4 border border-white">
    <div class="relative w-96">
        <span class="absolute inset-y-0 left-5 flex items-center text-slate-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </span>
        <input type="text" placeholder="Tìm kiếm CLB của bạn..."
               class="w-full pl-14 pr-6 py-3 bg-white/80 rounded-full border-none shadow-sm focus:ring-2 focus:ring-indigo-400 outline-none transition font-medium">
    </div>

    <div class="flex items-center gap-4 bg-white px-5 py-2 rounded-full shadow-sm border border-slate-50">
        <div class="text-right">
            <p class="text-sm font-black text-slate-800">
                <?= htmlspecialchars($fullName) ?>
            </p>
            <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-tighter">
                <?= htmlspecialchars($userCode) ?> • <?= htmlspecialchars($roleName) ?>
            </p>
        </div>
        <div class="w-11 h-11 rounded-full bg-slate-200 overflow-hidden border-2 border-indigo-50">
            <img src="https://i.pravatar.cc/150?u=<?= $userID ?>" class="w-full h-full object-cover">
        </div>
    </div>
</header>
