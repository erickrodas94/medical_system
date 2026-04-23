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

                <button onclick="switchTab('tab-timeline')" id="btn-tab-timeline" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm <?= !$activeCaseId ? 'opacity-60' : '' ?>">
                    <i data-lucide="activity" class="w-5 h-5 mr-3 text-slate-400"></i> Historial Clínico
                </button>
                <button onclick="switchTab('tab-trends')" id="btn-tab-trends" class="tab-btn w-full flex items-center px-4 py-3 bg-white border border-slate-100 text-slate-500 hover:bg-slate-50 hover:text-slate-700 rounded-xl font-semibold transition-all shadow-sm">
                    <i data-lucide="line-chart" class="w-5 h-5 mr-3 text-slate-400"></i> Gráficas Vitales
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
                    
                    <div id="tab-timeline" class="tab-content hidden">
                        <?php if(!$activeCaseId): ?>
                            <div class="text-center py-16">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <i data-lucide="folder-search" class="w-8 h-8 text-slate-300"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700 mb-1">Sin Caso Activo</h3>
                                <p class="text-slate-400 italic"><?= __('no_active_case') ?></p>
                            </div>
                        <?php else: ?>
                            
                            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-wrap gap-4 items-center justify-between mb-8">
                                <div class="flex gap-2">
                                    <button onclick="filterTimeline('all', this)" class="filter-btn bg-slate-800 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-md">TODO</button>
                                    <button onclick="filterTimeline('evolution', this)" class="filter-btn bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-100 transition-all">EVOLUCIONES</button>
                                    <button onclick="filterTimeline('vitals', this)" class="filter-btn bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-100 transition-all">SIGNOS</button>
                                    <button onclick="filterTimeline('prescription', this)" class="filter-btn bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-100 transition-all">RECETAS</button>
                                </div>

                                <div class="flex items-center gap-3">
                                    <select id="doctor-filter" onchange="filterByDoctor(this.value)" class="text-xs font-bold border-slate-300 text-slate-600 rounded-lg bg-slate-50 px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500">
                                        <option value="all">TODOS LOS DOCTORES</option>
                                        <?php foreach($doctors as $id => $name): ?>
                                            <option value="<?= $id ?>">DR. <?= mb_strtoupper($name) ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <div class="relative inline-block text-left">
                                        <button onclick="document.getElementById('register-dropdown').classList.toggle('hidden')" class="flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">
                                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i> REGISTRAR
                                            <i data-lucide="chevron-down" class="w-4 h-4 ml-1.5"></i>
                                        </button>
                                        
                                        <div id="register-dropdown" class="hidden absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                            <a href="<?= URL_BASE ?>pacientes/consulta/<?= $patient['patient_id'] ?>?case_id=<?= $activeCaseId ?>" class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                                <i data-lucide="stethoscope" class="w-4 h-4 mr-3 text-indigo-500"></i> Consulta Completa
                                            </a>
                                            <button onclick="document.getElementById('modalNewEvolution').classList.remove('hidden'); document.getElementById('register-dropdown').classList.add('hidden');" class="w-full flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                                <i data-lucide="file-text" class="w-4 h-4 mr-3 text-blue-500"></i> Solo Evolución
                                            </button>
                                            <button onclick="document.getElementById('modalTriage').classList.remove('hidden'); document.getElementById('register-dropdown').classList.add('hidden');" class="w-full flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                                <i data-lucide="activity" class="w-4 h-4 mr-3 text-emerald-500"></i> Solo Signos
                                            </button>
                                            <button onclick="document.getElementById('modalPrescription').classList.remove('hidden'); document.getElementById('register-dropdown').classList.add('hidden');" class="w-full flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                                <i data-lucide="pill" class="w-4 h-4 mr-3 text-blue-500"></i> Solo Receta
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="relative pl-8">
                                <div class="absolute left-[47px] top-0 bottom-0 w-0.5 bg-slate-200"></div>

                                <div class="space-y-8">
                                    <?php foreach ($timeline as $item): 
                                        $type = $item['record_type'];
                                        $colors = [
                                            'evolution' => ['border' => 'border-l-blue-500', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'icon' => 'file-text', 'label' => 'Evolución'],
                                            'vitals' => ['border' => 'border-l-emerald-500', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-600', 'icon' => 'activity', 'label' => 'Signos'],
                                            'prescription' => ['border' => 'border-l-purple-500', 'bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'icon' => 'pill', 'label' => 'Receta']
                                        ][$type];
                                    ?>
                                        <div class="timeline-item relative pl-8" data-type="<?= $type ?>" data-doctor="<?= $item['doctor_id'] ?>">
                                            <div class="absolute -left-[10px] top-4 w-8 h-8 rounded-full flex items-center justify-center border-4 border-white shadow-sm z-10 <?= $colors['bg'] ?> <?= $colors['text'] ?>">
                                                <i data-lucide="<?= $colors['icon'] ?>" class="w-3.5 h-3.5"></i>
                                            </div>

                                            <div class="bg-white rounded-xl border border-slate-200 <?= $colors['border'] ?> border-l-4 shadow-sm overflow-hidden">
                                                <div class="px-5 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></span>
                                                    <span class="text-xs font-bold text-slate-600">Dr. <?= htmlspecialchars($item['doctor_full_name']) ?></span>
                                                </div>
                                                
                                                <div class="p-5">
                                                    <?php if($type === 'vitals'): ?>
                                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                            <?php 
                                                                $parts = explode('|', $item['main_content']);
                                                                foreach($parts as $p): $d = explode(':', $p);
                                                            ?>
                                                                <div class="bg-emerald-50/50 p-2.5 rounded-lg border border-emerald-100 text-center">
                                                                    <span class="block text-[9px] font-bold text-emerald-600 uppercase mb-0.5"><?= $d[0] ?></span>
                                                                    <span class="text-sm font-bold text-slate-700"><?= $d[1] ?></span>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>

                                                    <?php elseif($type === 'evolution'): ?>
                                                        <p class="text-sm text-slate-700 leading-relaxed mb-4"><?= nl2br(htmlspecialchars($item['main_content'])) ?></p>
                                                        <?php if($item['sub_content']): ?>
                                                            <div class="bg-slate-50 p-4 rounded-xl border-l-4 border-l-slate-300">
                                                                <h5 class="text-[10px] font-bold text-slate-500 uppercase mb-2">Examen Físico</h5>
                                                                <p class="text-sm text-slate-600 italic"><?= nl2br(htmlspecialchars($item['sub_content'])) ?></p>
                                                            </div>
                                                        <?php endif; ?>

                                                    <?php elseif($type === 'prescription'): ?>
                                                        <div class="space-y-3 mb-5">
                                                            <?php 
                                                            if (!empty($item['main_content'])) {
                                                                // Separamos cada medicamento
                                                                $medications = explode('||', $item['main_content']);
                                                                
                                                                foreach($medications as $medStr): 
                                                                    // Separamos los detalles del medicamento
                                                                    $medData = explode('::', $medStr);
                                                                    // [0]=Nombre, [1]=Dosis, [2]=Frecuencia, [3]=Duración, [4]=Total
                                                                    
                                                                    if(empty(trim($medData[0]))) continue;
                                                            ?>
                                                                <div class="bg-purple-50/40 border border-purple-100 rounded-xl p-3 flex flex-col md:flex-row md:items-center justify-between gap-3 shadow-sm hover:shadow-md transition-all">
                                                                    <div class="flex items-start gap-3">
                                                                        <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0 mt-0.5">
                                                                            <i data-lucide="pill" class="w-4 h-4"></i>
                                                                        </div>
                                                                        <div>
                                                                            <h6 class="text-sm font-bold text-slate-800"><?= htmlspecialchars($medData[0]) ?></h6>
                                                                            <div class="text-xs text-slate-600 mt-1 flex flex-wrap gap-x-3 gap-y-1">
                                                                                <?php if(!empty($medData[1])): ?>
                                                                                    <span><span class="font-bold text-purple-700 opacity-70">Dosis:</span> <?= htmlspecialchars($medData[1]) ?></span>
                                                                                <?php endif; ?>
                                                                                
                                                                                <?php if(!empty($medData[2])): ?>
                                                                                    <span class="text-slate-300">•</span>
                                                                                    <span><span class="font-bold text-purple-700 opacity-70">Frec:</span> <?= htmlspecialchars($medData[2]) ?></span>
                                                                                <?php endif; ?>
                                                                                
                                                                                <?php if(!empty($medData[3])): ?>
                                                                                    <span class="text-slate-300">•</span>
                                                                                    <span><span class="font-bold text-purple-700 opacity-70">Duración:</span> <?= htmlspecialchars($medData[3]) ?></span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <?php if(!empty($medData[4])): ?>
                                                                    <div class="md:text-right shrink-0 bg-white px-3 py-1.5 rounded-lg border border-purple-50 inline-block md:block self-start">
                                                                        <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total</span>
                                                                        <span class="text-sm font-bold text-slate-800"><?= htmlspecialchars($medData[4]) ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php 
                                                                endforeach; 
                                                            } else {
                                                                echo '<p class="text-sm text-slate-500 italic">No hay medicamentos detallados en esta receta.</p>';
                                                            }
                                                            ?>
                                                        </div>

                                                        <?php if(!empty($item['sub_content'])): ?>
                                                            <div class="bg-slate-50 p-4 rounded-xl border-l-4 border-l-purple-300 mt-4">
                                                                <h5 class="flex items-center text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">
                                                                    <i data-lucide="info" class="w-3 h-3 mr-1.5"></i> Instrucciones de Plan
                                                                </h5>
                                                                <p class="text-sm text-slate-700 leading-relaxed"><?= nl2br(htmlspecialchars($item['sub_content'])) ?></p>
                                                            </div>
                                                        <?php endif; ?>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="tab-trends" class="tab-content hidden">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-slate-800">Evolución del Paciente</h3>
                            <div class="flex bg-slate-100 p-1 rounded-lg">
                                <button onclick="updateChart('bp')" class="chart-btn active-chart px-4 py-1.5 rounded text-xs font-bold bg-white shadow-sm text-indigo-600">Presión Arterial</button>
                                <button onclick="updateChart('weight')" class="chart-btn px-4 py-1.5 rounded text-xs font-bold text-slate-500 hover:text-slate-700">Peso</button>
                                <button onclick="updateChart('hr')" class="chart-btn px-4 py-1.5 rounded text-xs font-bold text-slate-500 hover:text-slate-700">F. Cardíaca</button>
                            </div>
                        </div>
                        
                        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                            <canvas id="vitalsChart" height="100"></canvas>
                        </div>
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
    // --- TU FUNCIÓN ACTUAL PARA CAMBIAR TABS ---
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
        if(activeBtn) {
            activeBtn.classList.remove('bg-white', 'text-slate-500', 'hover:bg-slate-50', 'border', 'border-slate-100');
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-md', 'shadow-blue-100');
        }
    }

    // --- NUEVAS FUNCIONES PARA FILTRAR EL TIMELINE ---
    function filterTimeline(type, btnElement) {
        // Cambiar estilos de los botones de filtro
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

            if (typeMatch && doctorMatch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function filterByDoctor(doctorId) {
        // Al cambiar el doctor, disparamos el filtro respetando la categoría actual
        const activeFilterBtn = document.querySelector('.filter-btn.bg-indigo-600');
        let currentType = 'all';
        
        if(activeFilterBtn.innerText.includes('EVOLUCIONES')) currentType = 'evolution';
        if(activeFilterBtn.innerText.includes('SIGNOS')) currentType = 'vitals';
        if(activeFilterBtn.innerText.includes('RECETAS')) currentType = 'prescription';

        filterTimeline(currentType, activeFilterBtn);
    }
    // Cerrar el menú desplegable al hacer clic en cualquier lugar fuera de él
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('register-dropdown');
        const button = dropdown.previousElementSibling;
        
        // Si el clic NO fue en el botón ni dentro del dropdown, ocúltalo
        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // 1. Recibimos los datos históricos desde PHP
    const rawVitalsData = <?= $vitalsJson ?? '[]' ?>;

    // 2. Preparamos las etiquetas (Fechas)
    const labels = rawVitalsData.map(v => new Date(v.taken_at).toLocaleDateString('es-ES', { day: '2-digit', month: 'short' }));

    // 3. Preparamos los arrays de datos
    const dataBP_Sys = rawVitalsData.map(v => v.systolic_bp);
    const dataBP_Dia = rawVitalsData.map(v => v.diastolic_bp);
    const dataWeight = rawVitalsData.map(v => v.weight_value);
    const dataHR = rawVitalsData.map(v => v.heart_rate_bpm);

    let vitalsChart; // Variable global para la gráfica

    function initChart() {
        const ctx = document.getElementById('vitalsChart').getContext('2d');
        
        // Configuramos la gráfica inicial (Presión Arterial)
        vitalsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Sistólica', data: dataBP_Sys, borderColor: '#ef4444', backgroundColor: '#fca5a555', fill: true, tension: 0.4 },
                    { label: 'Diastólica', data: dataBP_Dia, borderColor: '#3b82f6', backgroundColor: '#93c5fd55', fill: true, tension: 0.4 }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        });
    }

    function updateChart(metric) {
        // Cambiamos el estilo de los botones
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.classList.remove('bg-white', 'shadow-sm', 'text-indigo-600');
            btn.classList.add('text-slate-500');
        });
        event.target.classList.add('bg-white', 'shadow-sm', 'text-indigo-600');
        event.target.classList.remove('text-slate-500');

        // Actualizamos los datos de la gráfica
        if (metric === 'bp') {
            vitalsChart.data.datasets = [
                { label: 'Sistólica', data: dataBP_Sys, borderColor: '#ef4444', backgroundColor: '#fca5a555', fill: true, tension: 0.4 },
                { label: 'Diastólica', data: dataBP_Dia, borderColor: '#3b82f6', backgroundColor: '#93c5fd55', fill: true, tension: 0.4 }
            ];
        } else if (metric === 'weight') {
            vitalsChart.data.datasets = [
                { label: 'Peso', data: dataWeight, borderColor: '#10b981', backgroundColor: '#6ee7b755', fill: true, tension: 0.4 }
            ];
        } else if (metric === 'hr') {
            vitalsChart.data.datasets = [
                { label: 'Frecuencia Cardíaca', data: dataHR, borderColor: '#f59e0b', backgroundColor: '#fcd34d55', fill: true, tension: 0.4 }
            ];
        }
        vitalsChart.update();
    }

    // Inicializar la gráfica cuando la página cargue
    document.addEventListener('DOMContentLoaded', initChart);
</script>
<script>
    // 1. Recibimos los JSON de PHP
    const patientPoints = <?= $pediatricJson ?? '[]' ?>; 
    const whoData = <?= $whoCurvesJson ?? '{}' ?>;

    function renderPediatricChart() {
        const ctx = document.getElementById('pediatricChart').getContext('2d');
        
        // Extraemos las líneas de la OMS
        const weightCurves = whoData.weight || [];
        const labels = weightCurves.map(row => row.age_months); // Eje X: 0, 1, 2... 60
        
        const p3Data = weightCurves.map(row => row.P3);
        const p50Data = weightCurves.map(row => row.P50);
        const p97Data = weightCurves.map(row => row.P97);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    // CAPA FONDO: Las líneas de la OMS
                    {
                        label: 'Percentil 97',
                        data: p97Data,
                        borderColor: '#fca5a5', // Rojo suave
                        borderWidth: 1,
                        pointRadius: 0, // Ocultar los puntos de la línea
                        fill: false
                    },
                    {
                        label: 'Percentil 50 (Mediana)',
                        data: p50Data,
                        borderColor: '#10b981', // Verde esmeralda
                        borderWidth: 2,
                        borderDash: [5, 5], // Línea punteada
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'Percentil 3',
                        data: p3Data,
                        borderColor: '#fca5a5',
                        borderWidth: 1,
                        pointRadius: 0,
                        fill: false
                    },
                    // CAPA FRENTE: El paciente (Scatter Plot)
                    {
                        type: 'scatter', // Puntitos sueltos
                        label: 'Mi Paciente',
                        data: patientPoints, // Tiene {x: meses, y: peso}
                        backgroundColor: '#4f46e5', // Azul índigo fuerte
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        pointRadius: 6, // Puntos grandes para que resalten
                        pointHoverRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Edad (Meses)' } },
                    y: { title: { display: true, text: 'Peso (Kg)' } }
                }
            }
        });
    }

    // Ejecutar si estamos en la pestaña correcta
    document.addEventListener('DOMContentLoaded', renderPediatricChart);
</script>

<?php include '../views/layouts/footer.php'; ?>