<?php include '../views/layouts/header.php'; ?>
<?php include '../views/layouts/sidebar.php'; ?>

<div class="flex-1 flex flex-col h-full overflow-hidden">
    <?php include '../views/layouts/topbar.php'; ?>
    
    <main class="p-4 md:p-6 bg-slate-50 min-h-screen">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm border border-blue-200">
                    <i data-lucide="user" class="w-8 h-8"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800"><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></h1>
                    <p class="text-sm text-slate-500 font-medium mt-1">
                        <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded text-xs mr-2"><?= __($patient['identity_type_label']) ?></span>
                        <?= $patient['identity_number'] ?? '---' ?> • <?= (new DateTime($patient['birth_date']))->diff(new DateTime())->y ?> años
                    </p>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="document.getElementById('modalNewCase').classList.remove('hidden')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center">
                    <i data-lucide="folder-plus" class="w-4 h-4 mr-2"></i> <?= __('btn_new_case') ?>
                </button>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1"><?= __('active_case') ?></label>
                    <select onchange="window.location.href='?case_id=' + this.value" class="w-full px-4 py-2 border-none text-lg font-bold text-blue-600 focus:ring-0 bg-transparent cursor-pointer" <?= empty($cases) ? 'disabled' : '' ?>>
                        <?php if(empty($cases)): ?>
                            <option value=""><?= __('no_cases_registered') ?></option>
                        <?php else: ?>
                            <?php foreach($cases as $c): ?>
                                <option value="<?= $c['ID'] ?>" <?= $c['ID'] == $activeCaseId ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['title']) ?> (<?= $c['status'] ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>

        <?php 
        // Buscamos la información del caso seleccionado en el arreglo de casos
        $activeCaseData = null;
        if ($activeCaseId && !empty($cases)) {
            foreach ($cases as $c) {
                if ($c['ID'] == $activeCaseId) {
                    $activeCaseData = $c;
                    break;
                }
            }
        }
        ?>

        <?php if($activeCaseData): ?>
        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 mb-6 flex items-start gap-4">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm border border-blue-50 text-blue-500 mt-0.5">
                <i data-lucide="stethoscope" class="w-5 h-5"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-bold text-blue-900 mb-1"><?= __('consultation_reason') ?> / <?= __('initial_diagnosis') ?></h4>
                <p class="text-sm text-blue-800 leading-relaxed">
                    <?= nl2br(htmlspecialchars($activeCaseData['initial_reason'])) ?>
                </p>
                <div class="flex gap-4 mt-3 text-xs font-medium text-blue-600/80">
                    <span><i data-lucide="calendar" class="w-3 h-3 inline mr-1"></i> <?= __('opened_at') ?>: <?= date('d M, Y', strtotime($activeCaseData['opened_at'])) ?></span>
                    <span><i data-lucide="user" class="w-3 h-3 inline mr-1"></i><?= __('doctor') ?> <?= htmlspecialchars($activeCaseData['doc_fname'] ?? '') . ' ' . htmlspecialchars($activeCaseData['doc_lname'] ?? '') ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <div class="lg:col-span-1 space-y-2">
                <button onclick="switchTab('tab-profile')" id="btn-tab-profile" class="tab-btn active-tab w-full flex items-center px-4 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-md shadow-blue-100 transition-all">
                    <i data-lucide="user-circle" class="w-5 h-5 mr-3"></i> <?= __('patient_profile') ?>
                </button>
                <button onclick="switchTab('tab-background')" id="btn-tab-background" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm">
                    <i data-lucide="clipboard-type" class="w-5 h-5 mr-3 text-slate-400"></i> <?= __('patient_background') ?>
                </button>
                
                <div class="pt-4 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4"><?= __('clinical_data') ?></div>

                <button onclick="switchTab('tab-evolutions')" id="btn-tab-evolutions" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm <?= !$activeCaseId ? 'opacity-60' : '' ?>">
                    <i data-lucide="activity" class="w-5 h-5 mr-3 text-slate-400"></i> <?= __('evolution_soap') ?>
                </button>
                <button onclick="switchTab('tab-vitals')" id="btn-tab-vitals" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm <?= !$activeCaseId ? 'opacity-60' : '' ?>">
                    <i data-lucide="thermometer" class="w-5 h-5 mr-3 text-slate-400"></i> <?= __('vital_signs') ?>
                </button>
                <button onclick="switchTab('tab-prescriptions')" id="btn-tab-prescriptions" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm <?= !$activeCaseId ? 'opacity-60' : '' ?>">
                    <i data-lucide="file-text" class="w-5 h-5 mr-3 text-slate-400"></i> <?= __('prescriptions_plans') ?>
                </button>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 min-h-[400px] shadow-sm">
                    
                    <div id="tab-profile" class="tab-content block">
                        <?php include 'tabs/profile_view.php'; ?>
                    </div>

                    <div id="tab-background" class="tab-content hidden">
                        <?php include 'tabs/background_list.php'; ?>
                    </div>
                    
                    <div id="tab-evolutions" class="tab-content hidden">
                        <?php if(!$activeCaseId): ?>
                            <div class="text-center py-16">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <i data-lucide="folder-search" class="w-8 h-8 text-slate-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700 mb-1">Sin Caso Activo</h3>
                                <p class="text-slate-400 italic"><?= __('no_active_case') ?></p>
                            </div>
                        <?php else: include 'tabs/evolutions_list.php'; endif; ?>
                    </div>

                    <div id="tab-vitals" class="tab-content hidden space-y-6">
                        <?php if(!$activeCaseId): ?>
                            <div class="text-center py-16">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <i data-lucide="folder-search" class="w-8 h-8 text-slate-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700 mb-1">Sin Caso Activo</h3>
                                <p class="text-slate-400 italic"><?= __('no_active_case') ?></p>
                            </div>
                        <?php else: ?>
                            <div class="flex justify-between items-center border-b border-slate-100 pb-4">
                                <h3 class="text-lg font-bold text-slate-800"><?= __('vital_signs') ?></h3>
                                <button onclick="document.getElementById('modalTriage').classList.remove('hidden')" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center bg-emerald-50 px-3 py-1.5 rounded-lg transition-colors shadow-sm border border-emerald-100 hover:bg-emerald-100">
                                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Registrar Signos
                                </button>
                            </div>
                            <div class="text-center py-10"><p class="text-slate-400 italic"><?= __('vital_signs_history') ?></p></div>
                        <?php endif; ?>
                    </div>

                    <div id="tab-prescriptions" class="tab-content hidden space-y-6">
                        <?php if(!$activeCaseId): ?>
                            <div class="text-center py-16">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <i data-lucide="folder-search" class="w-8 h-8 text-slate-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700 mb-1">Sin Caso Activo</h3>
                                <p class="text-slate-400 italic"><?= __('no_active_case') ?></p>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-10"><p class="text-slate-400 italic">No hay recetas emitidas en este caso.</p></div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

    </main>
    
    <?php 
        include 'modals/new_case.php'; 
        include 'modals/new_triage.php'; 
        include 'modals/new_evolution.php'; 
    ?>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.add('hidden');
        el.classList.remove('block');
    });
    
    document.getElementById(tabId).classList.remove('hidden');
    document.getElementById(tabId).classList.add('block');
    
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-100');
        btn.classList.add('bg-white', 'text-slate-500', 'hover:bg-slate-50', 'border', 'border-slate-100');
    });
    
    const activeBtn = document.getElementById('btn-' + tabId);
    activeBtn.classList.remove('bg-white', 'text-slate-500', 'hover:bg-slate-50', 'border', 'border-slate-100');
    activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-100');
}
</script>

<?php include '../views/layouts/footer.php'; ?>
                    </div>