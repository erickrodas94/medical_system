<?php include '../views/layouts/header.php'; ?>
<?php include '../views/layouts/sidebar.php'; ?>

<main class="flex-1 flex flex-col overflow-y-auto">
    
    <?php include '../views/layouts/topbar.php'; ?>

    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <p class="text-sm font-medium text-slate-500 uppercase">Consultas hoy</p>
                <h4 class="text-2xl font-bold text-slate-900 mt-1">14</h4>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200">
                <p class="text-sm font-medium text-slate-500 uppercase">Nuevos Pacientes</p>
                <h4 class="text-2xl font-bold text-slate-900 mt-1">3</h4>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 h-64 flex items-center justify-center">
            <p class="text-slate-400 italic">Próximamente: Gráfica de evolución de pacientes</p>
        </div>
    </div>
</main>

<?php include '../views/layouts/footer.php'; ?>