<div id="modalPrescription" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl w-full border border-slate-200">
            
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center mr-3">
                        <i data-lucide="pill" class="w-5 h-5 text-indigo-600 mr-2"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800"><?= __('prescriptions_plans') ?></h3>
                        <p class="text-xs text-slate-500"><?= __('patient') ?>: <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></p>
                    </div>
                </div>
                <button onclick="document.getElementById('modalPrescription').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="<?= URL_BASE ?>pacientes/prescription/guardar" method="POST">
                <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>">
                <input type="hidden" name="case_id" value="<?= $activeCaseId ?>">
                
                
                <div class="p-6 space-y-6">
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

                        <div id="medications-container" class="space-y-3">
                            
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalPrescription').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-semibold transition-colors">
                        <?= __('cancel_btn') ?>
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-emerald-200 transition-all flex items-center">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> <?= __('btn_save_prescription') ?> 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>