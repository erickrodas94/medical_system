<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 shadow-sm z-10 shrink-0">
    
    <div class="flex items-center space-x-4">
        <button id="mobileMenuBtn" class="p-2 -ml-2 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg md:hidden transition-colors">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        
        <div class="flex flex-col border-l-2 border-blue-500 pl-3">
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Clinica</span>
            <span class="text-sm font-bold text-slate-800">#<?= $_SESSION['clinic']['clinic_code'] ?? '---' ?></span>
        </div>
    </div>

    <div class="flex items-center space-x-4">
        
        <?php 
            $limitGB = $_SESSION['clinic']['storage_limit_gb'] ?? 5;
            $usedBytes = $_SESSION['clinic']['storage_used_bytes'] ?? 0;
            $usedGB = round($usedBytes / (1024 * 1024 * 1024), 2);
            $percentage = ($limitGB > 0) ? min(100, ($usedGB / $limitGB) * 100) : 0;
            $barColor = $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-amber-500' : 'bg-blue-500');
        ?>
        <div class="hidden lg:flex items-center space-x-3 mr-4 border-r border-slate-100 pr-6">
            <div class="text-right">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Espacio Cloud</p>
                <p class="text-[11px] font-semibold text-slate-600"><?= $usedGB ?> / <?= $limitGB ?> GB</p>
            </div>
            <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full <?= $barColor ?>" style="width: <?= $percentage ?>%"></div>
            </div>
        </div>

        <div class="relative">
            <button id="profileDropdownBtn" class="flex items-center space-x-3 hover:bg-slate-50 p-1 sm:pr-2 rounded-xl transition-all text-left">
                <?php 
                    $initial = strtoupper(substr($_SESSION['user']['full_name'] ?? 'U', 0, 1));
                    $avatar = $_SESSION['user']['profile_pic'] ?? null;
                ?>
                <div class="relative">
                    <?php if ($avatar): ?>
                        <img src="<?= $avatar ?>" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                    <?php else: ?>
                        <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
                            <?= $initial ?>
                        </div>
                    <?php endif; ?>
                    <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></div>
                </div>

                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-700 leading-tight truncate max-w-[120px]">
                        <?= explode(' ', $_SESSION['user']['full_name'])[0] ?> <?= explode(' ', $_SESSION['user']['full_name'])[1] ?? '' ?>
                    </p>
                    <p class="text-[10px] text-slate-500 font-medium uppercase tracking-tighter">Mi Cuenta</p>
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
            </button>

            <div id="profileDropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 py-2 z-50">
                <a href="<?= URL_BASE ?>mi-perfil" class="flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-600">
                    <i data-lucide="user-circle" class="w-4 h-4 mr-3"></i> Mi Perfil
                </a>
                <a href="<?= URL_BASE ?>mis-preferencias" class="flex items-center px-4 py-2 text-sm text-slate-600 hover:bg-blue-50 hover:text-blue-600">
                    <i data-lucide="sliders" class="w-4 h-4 mr-3"></i> Preferencias
                </a>
                <div class="border-t border-slate-100 my-1"></div>
                <button type="button" onclick="confirmation_page_change('<?= URL_BASE ?>', 'logout', '<?= __('logout_title') ?>', '<?= __('logout_confirm') ?>')" class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4 mr-3"></i> <?= __('logout_title') ?>
                </button>
            </div>
        </div>
    </div>
</header>