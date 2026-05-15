<div id="modalTriage" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl w-full border border-slate-200">
            
            <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mr-3">
                        <i data-lucide="heart-pulse" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800"><?= __('vital_signs') ?></h3>
                        <p class="text-xs text-slate-500"><?= __('patient') ?>: <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></p>
                    </div>
                </div>
                <button onclick="document.getElementById('modalTriage').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="<?= URL_BASE ?>pacientes/triage/guardar" method="POST">
                <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>">
                <input type="hidden" name="case_id" value="<?= $activeCaseId ?>">
                
                <div class="p-5 grid grid-cols-1 grid-cols-2 gap-5 items-center justify-center">
                    <div class="space-y-1.5">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide"><?= __('blood_pressure') ?></label>
                        <div class="flex items-center gap-1">
                            <input type="number" name="systolic" placeholder="120" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-center focus:ring-2 focus:ring-emerald-500 outline-none">
                            <span class="text-slate-400 font-bold">/</span>
                            <input type="number" name="diastolic" placeholder="80" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm text-center focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5"><?= __('heart_rate') ?></label>
                        <input type="number" name="heart_rate" placeholder="70" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5"><?= __('oxygen_saturation') ?></label>
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
                    <?php if (($_SESSION['user']['specialty'] ?? '') === 'specialty_pediatrics'): ?>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wide mb-1.5"><?= __('head_circumference') ?></label>
                        <div class="flex">
                            <input type="number" step="0.1" name="head_circumference" placeholder="0.0" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm">
                            <select name="head_circumference_unit" class="border-slate-300 px-3 py-2 rounded-r-lg text-sm bg-slate-50">
                                <option value="cm" selected><?= __('cm') ?></option>
                                <option value="in"><?= __('in') ?></option>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div>
                    </div>
                </div>
                <div class="p-3 border-t border-slate-100 bg-slate-50 flex items-center justify-center">
                    <p class="text-[11px] text-slate-400"><i data-lucide="info" class="w-3 h-3 inline"></i> El sistema calculará el IMC (BMI) automáticamente al guardar.</p>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalTriage').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-semibold transition-colors">
                        <?= __('cancel_btn') ?>
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-emerald-200 transition-all flex items-center">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> <?= __('btn_save_triage') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>