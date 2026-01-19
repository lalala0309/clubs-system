<header class="w-full rounded-[20px] max-md:rounded-[15px] flex items-center justify-between px-8 max-md:px-4 py-3 max-md:py-2 bg-[#4F46E5] shadow-lg z-50">
    
    <div class="flex items-center gap-4 max-md:gap-2">
        <div class="w-9 h-9 max-md:w-8 max-md:h-8 rounded-full bg-white/20 overflow-hidden border border-white/30 flex items-center justify-center">
            <i class="bi bi-person-circle text-xl max-md:text-lg text-white"></i>
        </div>
        
        <div class="text-left">
            <p class="text-xs max-md:text-[11px] font-black text-white leading-tight">
                <?= htmlspecialchars($fullName) ?>
            </p>
            <p class="text-[9px] max-md:text-[8px] text-indigo-100 font-bold uppercase tracking-wider opacity-80 leading-none">
                <?= htmlspecialchars($userEmail) ?>
            </p>
        </div>
    </div>
</header>