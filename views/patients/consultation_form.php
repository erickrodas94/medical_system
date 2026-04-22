<?php include '../views/layouts/header.php'; ?>

<div class="flex-1 flex flex-col h-screen bg-slate-50 overflow-y-auto">
    
    <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="<?= URL_BASE ?>pacientes/ver/<?= $patient['patient_id'] ?>?case_id=<?= $activeCaseId ?>" class="p-2 bg-slate-100 text-slate-500 rounded-lg hover:bg-slate-200 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800"><?= __('new_consultation') ?></h1>
                <p class="text-sm text-slate-500 font-medium"><?= __('patient') ?>: <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <button type="button" onclick="submitConsultation(true)" class="px-5 py-2.5 bg-amber-50 border border-amber-200 text-amber-700 hover:bg-amber-100 rounded-xl text-sm font-bold transition-all flex items-center shadow-sm">
                <i data-lucide="edit-3" class="w-5 h-5 mr-2"></i> <?= __('save_as_draft') ?>
            </button>
            <button type="button" onclick="submitConsultation(false)" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200 transition-all flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i> <?= __('finalize_consultation') ?>
            </button>
        </div>
    </div>

    <main class="max-w-4xl mx-auto w-full p-6 pb-32">
        <form id="formConsultation" action="<?= URL_BASE ?>pacientes/consulta/guardar" method="POST" class="space-y-6">
            
            <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>"> 
            <input type="hidden" name="person_id" value="<?= $patient['ID'] ?>"> 
            <input type="hidden" name="case_id" value="<?= $activeCaseId ?>">
            <input type="hidden" name="is_draft" id="is_draft_input" value="0">

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-emerald-50 px-5 py-3 border-b border-emerald-100 flex items-center">
                    <i data-lucide="heart-pulse" class="w-5 h-5 text-emerald-600 mr-2"></i>
                    <h3 class="font-bold text-emerald-900"><?= __('vital_signs') ?></h3>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide"><?= __('blood_pressure') ?> (mmHg)</label>
                        <div class="flex items-center gap-1">
                            <input type="number" name="systolic" placeholder="120" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-center focus:ring-2 focus:ring-emerald-500 outline-none">
                            <span class="text-slate-400 font-bold">/</span>
                            <input type="number" name="diastolic" placeholder="80" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-center focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5"><?= __('heart_rate') ?> (LPM)</label>
                        <input type="number" name="heart_rate" placeholder="70" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5"><?= __('spo2') ?> (%)</label>
                        <input type="number" name="spo2" placeholder="98" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5"><?= __('temperature') ?></label>
                        <div class="flex shadow-sm rounded-lg">
                            <input type="number" step="0.1" name="temperature_value" placeholder="37.0" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                            <select name="temperature_unit" class="border-slate-300 px-3 py-2 rounded-r-lg text-sm bg-slate-50">
                                <option value="C" selected>°C</option>
                                <option value="F">°F</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5"><?= __('weight') ?></label>
                        <div class="flex shadow-sm rounded-lg">
                            <input type="number" step="0.1" name="weight_value" placeholder="0.0" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                            <select name="weight_unit" class="border-slate-300 px-3 py-2 rounded-r-lg text-sm bg-slate-50">
                                <option value="lb" selected>lb</option>
                                <option value="kg">kg</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5"><?= __('height') ?></label>
                        <div class="flex shadow-sm rounded-lg">
                            <input type="number" step="0.1" name="height_value" placeholder="0.0" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                            <select name="height_unit" class="border-slate-300 px-3 py-2 rounded-r-lg text-sm bg-slate-50">
                                <option value="cm" selected>cm</option>
                                <option value="mt">mt</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-blue-50 px-5 py-3 border-b border-blue-100 flex items-center">
                    <i data-lucide="clipboard-list" class="w-5 h-5 text-blue-600 mr-2"></i>
                    <h3 class="font-bold text-blue-900"><?= __('lbl_evolution') ?></h3>
                </div>
                <div class="p-5 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('evolution_notes') ?> <span class="text-rose-500">*</span></label>
                        <textarea name="evolution_notes" rows="4" required placeholder="<?= __('evolution_notes_placeholder') ?>" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
                        <p class="text-[11px] text-slate-400 mt-1"><?= __('evolution_notes_help') ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('physical_exam_notes') ?></label>
                        <textarea name="physical_exam_notes" rows="5" placeholder="<?= __('physical_exam_placeholder') ?>" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('patient_instructions') ?></label>
                        <textarea name="patient_instructions" rows="5" placeholder="<?= __('patient_instructions_placeholder') ?>" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-indigo-50 px-5 py-3 border-b border-indigo-100 flex items-center justify-between">
                    <div class="flex items-center">
                        <i data-lucide="pill" class="w-5 h-5 text-indigo-600 mr-2"></i>
                        <h3 class="font-bold text-indigo-900"><?= __('prescriptions_plans') ?></h3>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-bold text-slate-700"><?= __('prescribed_medications') ?></label>
                            <div class="flex gap-2">
                                <button type="button" onclick="removeLastMedication()" class="px-2 py-1 bg-rose-50 text-rose-600 rounded hover:bg-rose-100 border border-rose-200 transition-colors">
                                    <i data-lucide="minus" class="w-4 h-4"></i>
                                </button>
                                <button type="button" onclick="addMedication()" class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded hover:bg-indigo-100 border border-indigo-200 transition-colors">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <div id="medications-container" class="space-y-3"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-slate-100 px-5 py-3 border-b border-slate-200 flex items-center">
                    <i data-lucide="dollar-sign" class="w-5 h-5 text-slate-600 mr-2"></i>
                    <h3 class="font-bold text-slate-800"><?= __('billing_and_admin') ?></h3>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('assistance_type') ?></label>
                        <select name="assistance_type" class="w-full px-3 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                            <option value="In-Person"><?= __('In-Person') ?></option>
                            <option value="Video"><?= __('Video') ?></option>
                            <option value="Phone"><?= __('Phone') ?></option>
                            <option value="Chat"><?= __('Chat') ?></option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('consultation_fee') ?></label>
                        <div class="relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 font-bold sm:text-sm"><?= $_SESSION['clinic']['currency_iso'] ?? 'GTQ' ?></span>
                            </div>
                            <input type="number" step="0.01" name="consultation_price" placeholder="0.00" class="w-full pl-14 px-3 py-2 border border-slate-300 rounded-lg text-sm font-bold text-slate-700">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1"><?= __('consultation_fee_help') ?></p>
                    </div>
                </div>
            </div>

        </form>
    </main>
</div>

<script>
    let medCount = 0;

    function submitConsultation(isDraft) {
        document.getElementById('is_draft_input').value = isDraft ? '1' : '0';
        document.getElementById('formConsultation').submit();
    }

    function addMedication() {
        const container = document.getElementById('medications-container');
        const row = document.createElement('div');
        row.className = 'p-4 bg-slate-50 rounded-xl border border-slate-200 med-row mb-3 relative';
        
        row.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-6 gap-3 mb-3">
                <div class="md:col-span-2">
                    <label class="text-[10px] font-bold text-slate-500 uppercase"><?= __('medications') ?></label>
                    <input type="text" name="medications[${medCount}][name]" placeholder="<?= __('medications_placeholder') ?>" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase"><?= __('dosage') ?></label>
                    <div class="flex shadow-sm rounded-lg">
                        <input type="number" name="medications[${medCount}][dosage_val]" class="js-calc-trigger js-dosage w-full px-3 py-2 text-sm border border-slate-300 border-r-0 rounded-l-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="1">
                        <select name="medications[${medCount}][dosage_unit]" class="border-slate-300 rounded-r-lg text-[10px] bg-slate-50 text-slate-600 px-1">
                            <option value="tableta"><?= __('tablets') ?></option>
                            <option value="capsula"><?= __('capsules') ?></option>
                            <option value="ml"><?= __('ml') ?></option>
                            <option value="mg"><?= __('mg') ?></option>
                            <option value="drop"><?= __('drop') ?></option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase"><?= __('frequency') ?></label>
                    <div class="flex items-center gap-1">
                        <span class="text-[10px] text-slate-400">c/</span>
                        <input type="number" name="medications[${medCount}][freq_val]" class="js-calc-trigger js-freq w-full px-2 py-2 text-sm border border-slate-300 rounded-lg text-center" placeholder="8">
                        <span class="text-[10px] text-slate-400">hrs</span>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase"><?= __('duration') ?></label>
                    <div class="flex shadow-sm rounded-lg">
                        <input type="number" name="medications[${medCount}][dur_val]" class="js-calc-trigger js-dur w-full px-3 py-2 text-sm border border-slate-300 border-r-0 rounded-l-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="5">
                        <select name="medications[${medCount}][dur_unit]" class="js-calc-trigger js-dur-unit border-slate-300 rounded-r-lg text-[10px] bg-slate-50 text-slate-600 px-1">
                            <option value="dias"><?= __('days') ?></option>
                            <option value="semanas"><?= __('weeks') ?></option>
                            <option value="meses"><?= __('months') ?></option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-indigo-600 uppercase"><?= __('total_quantity') ?></label>
                    <input type="text" name="medications[${medCount}][total_quantity]" readonly class="js-total-qty w-full px-3 py-2 text-sm border border-indigo-200 bg-indigo-50 rounded-lg text-center font-bold text-indigo-700" placeholder="Total">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-1 gap-3">
                <div class="md:col-span-1">
                    <label class="text-[10px] font-bold text-slate-500 uppercase"><?= __('additional_notes') ?></label>
                    <input type="text" name="medications[${medCount}][additional_notes]" placeholder="<?= __('additional_notes_placeholder') ?>" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            
            <div class="absolute -top-2 -left-2 bg-indigo-100 text-indigo-700 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold border border-indigo-200">
                ${medCount + 1}
            </div>
        `;
        
        container.appendChild(row);
        medCount++;
    }

    function removeLastMedication() {
        const container = document.getElementById('medications-container');
        if (container.lastElementChild) {
            container.removeChild(container.lastElementChild);
            medCount--;
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        addMedication();
    });

    document.addEventListener('input', function (e) {
    if (e.target.classList.contains('js-calc-trigger')) {
        const container = e.target.closest('.relative'); // O el div que envuelve cada bloque de medicamento
        
        const dosage = parseFloat(container.querySelector('.js-dosage').value) || 0;
        const freq = parseFloat(container.querySelector('.js-freq').value) || 0;
        const durVal = parseFloat(container.querySelector('.js-dur').value) || 0;
        const durUnit = container.querySelector('.js-dur-unit').value;

        // Convertir todo a días
        let days = durVal;
        if (durUnit === 'semanas') days = durVal * 7;

        if (dosage > 0 && freq > 0 && days > 0) {
            // Fórmula: (24h / frecuencia) = tomas al día. Luego * dosis * días.
            const tomasAlDia = 24 / freq;
            const total = Math.ceil(tomasAlDia * dosage * days);
            
            container.querySelector('.js-total-qty').value = total;
        }
    }
});
</script>

<?php include '../views/layouts/footer.php'; ?>