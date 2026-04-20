<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>404 - Página no encontrada | KeyTech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full text-center">
        <div class="mb-8 relative inline-block">
            <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center mx-auto animate-pulse">
                <i data-lucide="stethoscope" class="w-16 h-16 text-blue-600"></i>
            </div>
            <div class="absolute -top-2 -right-2 bg-rose-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                Error 404
            </div>
        </div>

        <h1 class="text-3xl font-bold text-slate-800 mb-4"><?= __('404_title') ?></h1>
        <p class="text-slate-500 mb-8 leading-relaxed">
            <?= __('404_description') ?>
        </p>

        <div class="flex flex-col gap-3">
            <a href="<?= URL_BASE ?>dashboard" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all flex items-center justify-center">
                <i data-lucide="home" class="w-5 h-5 mr-2"></i> <?= __('404_btn_dashboard') ?>
            </a>
            <button onclick="window.history.back()" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-all">
                <?= __('404_btn_back') ?>
            </button>
        </div>

        <p class="mt-12 text-xs text-slate-400 font-medium uppercase tracking-widest">
            KeyTech Medical System &bull; Guatemala
        </p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>