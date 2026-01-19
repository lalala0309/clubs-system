<header class="w-full rounded-[20px] flex items-center justify-end px-8 py-3 bg-[#4F46E5] shadow-lg z-50">
        <!-- <div class="relative w-80">
            <span class="absolute inset-y-0 left-4 flex items-center text-indigo-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </span>
            <input type="text" placeholder="Tìm kiếm CLB..."
                   class="w-full pl-11 pr-4 py-2 bg-white/10 rounded-lg border-none text-white placeholder-indigo-200 focus:ring-2 focus:ring-white/30 outline-none transition font-medium text-xs">
        </div> -->

        <div class="flex items-center gap-4">
        <div class="w-9 h-9 rounded-full bg-white/20 overflow-hidden border border-white/30 flex items-center justify-center">
                <i class="bi bi-person-circle text-xl text-white"></i>
            </div>
            <div class="text-left">
                <p class="text-xs font-black text-white leading-tight">
                    <?= htmlspecialchars($fullName) ?>
                </p>
                <p class="text-[9px] text-indigo-100 font-bold uppercase tracking-wider opacity-80">
                    <?= htmlspecialchars($userEmail) ?>
                </p>
            </div>
        </div>
    </header>