<?php include '../views/layouts/header.php'; ?>
<?php include '../views/layouts/sidebar.php'; ?>

<div class="flex-1 flex flex-col h-full overflow-auto">
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
                        <?php if ($patient['identity_number'] !== null): ?>
                            <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded text-xs mr-2"><?= __($patient['patient_id_type']) ?></span><?= $patient['identity_number'] ?>
                        <?php else: ?>
                            <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded text-xs mr-2"><?= __('lbl_tutor') . ' - ' . __($patient['tutor_id_type']) ?></span><?= $patient['tutor_identity_number'] ?>
                        <?php endif; ?>
                    </p>
                    <p class="text-sm text-slate-500 font-medium mt-1">
                        <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded text-xs mr-2"><?= __('patient_age') ?> <?= calculate_age($patient['birth_date']) ?>
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
        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 mb-6 flex items-start gap-4 relative">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm border border-blue-50 text-blue-500 mt-0.5">
                <i data-lucide="stethoscope" class="w-5 h-5"></i>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="text-base font-bold text-blue-900"><?= htmlspecialchars($activeCaseData['title']) ?></h3>
                        <h4 class="text-xs font-semibold text-blue-700/70 uppercase tracking-wider"><?= __('consultation_reason') ?> / <?= __('initial_diagnosis') ?></h4>
                    </div>
                    <button onclick="document.getElementById('modalEditCase').classList.remove('hidden')" class="text-blue-500 hover:text-blue-700 bg-white p-2 rounded-lg border border-blue-100 shadow-sm transition-colors" title="Editar Nombre/Motivo del Caso">
                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                    </button>
                </div>

                <div class="relative">
                    <p id="case-reason-text" class="text-sm text-blue-800 leading-relaxed line-clamp-3 transition-all duration-300">
                        <?= nl2br(htmlspecialchars($activeCaseData['initial_reason'])) ?>
                    </p>
                    
                    <?php 
                    // Mostramos el botón si hay más de 180 caracteres o si hay más de 3 saltos de línea (4 líneas de texto)
                    if(strlen($activeCaseData['initial_reason']) > 180 || substr_count($activeCaseData['initial_reason'], "\n") >= 3): 
                    ?>
                        <button onclick="toggleCaseReason('case-reason-text', 'btnForMore', 'btnForLess')" id="btnForMore" class="text-xs font-bold text-blue-600 hover:text-blue-800 mt-1 cursor-pointer">
                            <?= __('read_more') ?>
                        </button>
                        <button onclick="toggleCaseReason('case-reason-text', 'btnForLess', 'btnForMore')" id="btnForLess" class="text-xs font-bold text-blue-600 hover:text-blue-800 mt-1 cursor-pointer hidden">
                            <?= __('read_less') ?>
                        </button>
                    <?php endif; ?>
                </div>

                <div class="flex flex-wrap gap-4 mt-4 text-xs font-medium text-blue-600/80">
                    <span class="flex items-center"><i data-lucide="calendar" class="w-3.5 h-3.5 mr-1.5"></i> <?= __('opened_at') ?>: <?= date('d M, Y', strtotime($activeCaseData['opened_at'])) ?></span>
                    <span class="flex items-center"><i data-lucide="user" class="w-3.5 h-3.5 mr-1.5"></i><?= __('doctor') ?> <?= htmlspecialchars($activeCaseData['doc_fname'] ?? '') . ' ' . htmlspecialchars($activeCaseData['doc_lname'] ?? '') ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <div class="lg:col-span-1 space-y-2">

                <div class="pt-4 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4"><?= __('clinical_data') ?></div>

                <button onclick="switchTab('tab-timeline')" id="btn-tab-timeline" class="tab-btn active-tab w-full flex items-center px-4 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-md shadow-blue-100 transition-all <?= !$activeCaseId ? 'opacity-60' : '' ?>">
                    <i data-lucide="activity" class="w-5 h-5 mr-3 text-slate-400"></i> <?= __('medical_history') ?>
                </button>

                <button onclick="switchTab('tab-trends')" id="btn-tab-trends" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm">
                    <i data-lucide="line-chart" class="w-5 h-5 mr-3 text-slate-400"></i> <?= __('vital_sign_charts') ?>
                </button>

                <?php if (($_SESSION['user']['specialty'] ?? '') === 'specialty_pediatrics'): ?>
                    <button onclick="switchTab('tab-pediatrics')" id="btn-tab-pediatrics" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm">
                        <i data-lucide="baby" class="w-5 h-5 mr-3 text-slate-400"></i> <?= __('pediatric_charts') ?>
                    </button>
                <?php endif; ?>

                <div class="pt-4 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest px-4"><?= __('general_data') ?></div>

                <button onclick="switchTab('tab-profile')" id="btn-tab-profile" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm">
                    <i data-lucide="user-circle" class="w-5 h-5 mr-3"></i> <?= __('patient_profile') ?>
                </button>

                <button onclick="switchTab('tab-background')" id="btn-tab-background" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm">
                    <i data-lucide="clipboard-type" class="w-5 h-5 mr-3 text-slate-400"></i> <?= __('patient_background') ?>
                </button>
                
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white border border-slate-200 rounded-2xl p-6 min-h-[400px] shadow-sm">
                    
                    <div id="tab-timeline" class="tab-content block">
                        <?php include 'tabs/timeline.php'; ?>
                    </div>

                    <div id="tab-trends" class="tab-content hidden">
                        <?php include 'tabs/trends.php'; ?>
                    </div>
                    
                    <?php if (($_SESSION['user']['specialty'] ?? '') === 'specialty_pediatrics'): ?>
                    <div id="tab-pediatrics" class="tab-content hidden">
                        <?php include 'tabs/oms_chart.php'; ?>
                    </div>
                    <?php endif; ?>
                    
                    <div id="tab-profile" class="tab-content hidden">
                        <?php include 'tabs/profile_view.php'; ?>
                    </div>

                    <div id="tab-background" class="tab-content hidden">
                        <?php include 'tabs/background_list.php'; ?>
                    </div>

                </div>
            </div>
        </div>

    </main>
    
    <?php 
        include 'modals/new_case.php'; 
        include 'modals/new_triage.php'; 
        include 'modals/new_evolution.php'; 
        include 'modals/new_prescription.php';
        include 'modals/edit_case.php'; 
    ?>
</div>

<script>
    // ==========================================
    // 1. DATOS INYECTADOS DESDE PHP
    // ==========================================
    const rawVitalsData = <?= $vitalsJson ?? '[]' ?>;
    const pediatricHistory = <?= $pediatricJson ?? '[]' ?>;
    const whoCurves = <?= $whoCurvesJson ?? '{}' ?>;

    // ==========================================
    // 2. ESTADO GLOBAL
    // ==========================================
    let vitalsChart = null;
    let pediatricChart = null;
    let currentPediatricMetric = 'weight';

    // ==========================================
    // 3. INICIALIZACIÓN
    // ==========================================
    document.addEventListener('DOMContentLoaded', () => {
        initVitalsChart();
        // Nota: La gráfica pediátrica se inicializa sola cuando se abre su pestaña
    });

    // ==========================================
    // 4. LÓGICA DE PESTAÑAS (TABS)
    // ==========================================
    const originalSwitchTab = switchTab; // Guardar referencia si existía
    function switchTab(tabId) {
        // Ocultar todos los contenidos
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
            el.classList.remove('block');
        });
        
        // Mostrar el seleccionado
        document.getElementById(tabId).classList.remove('hidden');
        document.getElementById(tabId).classList.add('block');
        
        // Estilos de los botones
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-100');
            btn.classList.add('bg-white', 'text-slate-500', 'hover:bg-slate-50', 'border', 'border-slate-100');
        });
        
        const activeBtn = document.getElementById('btn-' + tabId);
        if(activeBtn) {
            activeBtn.classList.remove('bg-white', 'text-slate-500', 'hover:bg-slate-50', 'border', 'border-slate-100');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-100');
        }

        // Si entramos a pediatría, renderizamos la gráfica con retraso
        if (tabId === 'tab-pediatrics') {
            setTimeout(initPediatricChart, 150);
        }
    }

    // ==========================================
    // 5. LÓGICA DE FILTROS DEL TIMELINE
    // ==========================================
    function filterTimeline(type, btnElement) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('bg-slate-800', 'text-white');
            btn.classList.add('bg-white', 'text-slate-600', 'border-slate-300');
        });
        
        btnElement.classList.remove('bg-white', 'text-slate-600', 'border-slate-300');
        btnElement.classList.add('bg-slate-800', 'text-white');

        const items = document.querySelectorAll('.timeline-item');
        const doctorFilter = document.getElementById('doctor-filter').value;

        items.forEach(item => {
            const itemType = item.getAttribute('data-type');
            const itemDoctor = item.getAttribute('data-doctor');
            
            const typeMatch = (type === 'all' || itemType === type);
            const doctorMatch = (doctorFilter === 'all' || itemDoctor === doctorFilter);

            item.style.display = (typeMatch && doctorMatch) ? 'block' : 'none';
        });
    }

    function filterByDoctor(doctorId) {
        const activeFilterBtn = document.querySelector('.filter-btn.bg-slate-800') || document.querySelector('.filter-btn');
        let currentType = 'all';
        
        if(activeFilterBtn.innerText.includes('EVOLUCIONES')) currentType = 'evolution';
        if(activeFilterBtn.innerText.includes('SIGNOS')) currentType = 'vitals';
        if(activeFilterBtn.innerText.includes('RECETAS')) currentType = 'prescription';

        filterTimeline(currentType, activeFilterBtn);
    }

    // Cerrar menú de "Registrar"
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('register-dropdown');
        if(!dropdown) return;
        const button = dropdown.previousElementSibling;
        
        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // ==========================================
    // 6. GRÁFICA DE SIGNOS VITALES
    // ==========================================
    function initVitalsChart() {
        const ctx = document.getElementById('vitalsChart');
        if(!ctx) return;

        const labels = rawVitalsData.map(v => new Date(v.taken_at).toLocaleDateString('es-ES', { day: '2-digit', month: 'short' }));
        const dataBP_Sys = rawVitalsData.map(v => v.systolic_bp);
        const dataBP_Dia = rawVitalsData.map(v => v.diastolic_bp);

        vitalsChart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Sistólica', data: dataBP_Sys, borderColor: '#ef4444', backgroundColor: '#fca5a555', fill: true, tension: 0.4 },
                    { label: 'Diastólica', data: dataBP_Dia, borderColor: '#3b82f6', backgroundColor: '#93c5fd55', fill: true, tension: 0.4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    }

    function updateChart(metric) {
        if(!vitalsChart) return;
        
        // Estilos de botones
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-indigo-600');
            btn.classList.add('text-slate-500');
        });
        event.target.classList.add('bg-white', 'shadow-sm', 'text-indigo-600');
        event.target.classList.remove('text-slate-500');

        // Cambiar datos
        if (metric === 'bp') {
            vitalsChart.data.datasets = [
                { label: 'Sistólica', data: rawVitalsData.map(v => v.systolic_bp), borderColor: '#ef4444', backgroundColor: '#fca5a555', fill: true, tension: 0.4 },
                { label: 'Diastólica', data: rawVitalsData.map(v => v.diastolic_bp), borderColor: '#3b82f6', backgroundColor: '#93c5fd55', fill: true, tension: 0.4 }
            ];
        } else if (metric === 'weight') {
            vitalsChart.data.datasets = [
                { label: 'Peso', data: rawVitalsData.map(v => v.weight_value), borderColor: '#10b981', backgroundColor: '#6ee7b755', fill: true, tension: 0.4 }
            ];
        } else if (metric === 'hr') {
            vitalsChart.data.datasets = [
                { label: 'F. Cardíaca', data: rawVitalsData.map(v => v.heart_rate_bpm), borderColor: '#f59e0b', backgroundColor: '#fcd34d55', fill: true, tension: 0.4 }
            ];
        }
        vitalsChart.update();
    }

    // ==========================================
    // 7. GRÁFICA PEDIÁTRICA (OMS)
    // ==========================================
    function initPediatricChart() {
        const canvas = document.getElementById('growthChartCanvas');
        if (!canvas) return; 

        const ctx = canvas.getContext('2d');
        const metricKey = currentPediatricMetric === 'head' ? 'head_circumference' : currentPediatricMetric;
        const curves = whoCurves[currentPediatricMetric] || [];
        
        const labels = curves.map(c => c.age_months);
        const patientPoints = pediatricHistory.map(p => ({
            x: p.x,
            y: p[currentPediatricMetric]
        })).filter(p => p.y > 0);

        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'Paciente', data: patientPoints, type: 'scatter',
                    backgroundColor: '#4f46e5', borderColor: '#ffffff', borderWidth: 2, pointRadius: 6, z: 10
                },
                { label: 'P97', data: curves.map(c => c.P97), borderColor: '#fca5a5', borderWidth: 1, pointRadius: 0, fill: false },
                { label: 'P50 (Ideal)', data: curves.map(c => c.P50), borderColor: '#10b981', borderDash: [5, 5], borderWidth: 2, pointRadius: 0, fill: false },
                { label: 'P3', data: curves.map(c => c.P3), borderColor: '#fca5a5', borderWidth: 1, pointRadius: 0, fill: false }
            ]
        };

        if (pediatricChart) pediatricChart.destroy();
        
        pediatricChart = new Chart(ctx, {
            type: 'line', data: data,
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: 'Meses' } },
                    y: { title: { display: true, text: currentPediatricMetric === 'weight' ? 'kg' : 'cm' } }
                }
            }
        });
    }

    function changePediatricMetric(metric) {
        currentPediatricMetric = metric;
        document.querySelectorAll('.p-metric-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-indigo-600');
            btn.classList.add('text-slate-500');
        });
        document.getElementById('btn-p-' + metric).classList.add('bg-white', 'shadow-sm', 'text-indigo-600');
        initPediatricChart();
    }
</script>

<?php include '../views/layouts/footer.php'; ?>