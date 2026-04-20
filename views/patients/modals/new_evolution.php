<div id="modalEvolution" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-4xl w-full border border-slate-200">
            
            <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mr-3">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800"><?= __('new_evolution_note') ?></h3>
                        <p class="text-xs text-slate-500"><?= __('patient') ?>: <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></p>
                    </div>
                </div>
                <button onclick="document.getElementById('modalEvolution').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="<?= URL_BASE ?>pacientes/evolucion/guardar" method="POST">
                <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>"> 
                <input type="hidden" name="person_id" value="<?= $patient['ID'] ?>"> 
                <input type="hidden" name="case_id" value="<?= $activeCaseId ?>">
                
                <div class="px-6 py-6 space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-5">
                            <div>
                                <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                                    <?= __('evolution_notes') ?> <span class="text-rose-500 ml-1">*</span>
                                </label>
                                <textarea name="evolution_notes" rows="10" required placeholder="<?= __('evolution_notes_placeholder') ?>" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                                <p class="text-[11px] text-slate-400 mt-1"><?= __('evolution_notes_help') ?></p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                                    <?= __('physical_exam_notes') ?>
                                </label>
                                <textarea name="physical_exam_notes" rows="3" placeholder="<?= __('physical_exam_placeholder') ?>" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                            </div>

                            <div>
                                <label class="flex items-center text-sm font-bold text-slate-700 mb-2">
                                    <?= __('patient_instructions') ?>
                                </label>
                                <textarea name="patient_instructions" rows="3" placeholder="<?= __('patient_instructions_placeholder') ?>" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('assistance_type') ?></label>
                            <select name="assistance_type" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                                <option value="In-Person"><?= __('In-Person') ?></option>
                                <option value="Video"><?= __('Video') ?></option>
                                <option value="Phone"><?= __('Phone') ?></option>
                                <option value="Chat"><?= __('Chat') ?></option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('consultation_fee') ?></label>
                            <div class="relative shadow-sm rounded-lg">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-slate-500 font-bold sm:text-sm">
                                        <?= $_SESSION['clinic']['currency_iso'] ?? 'Q' ?>
                                    </span>
                                </div>
                                <input type="number" step="0.01" name="consultation_price" placeholder="0.00" class="w-full pl-14 border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm font-bold text-slate-700">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1"><?= __('consultation_fee_help') ?></p>
                        </div>
                        
                    </div>

                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-between items-center">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_draft" id="is_draft" value="1" class="w-4 h-4 text-amber-500 bg-slate-100 border-slate-300 rounded focus:ring-amber-500 focus:ring-2">
                        <label for="is_draft" class="ml-2 text-sm font-medium text-slate-600"><?= __('save_as_draft') ?></label>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('modalEvolution').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-semibold transition-colors">
                            <?= __('cancel_btn') ?>
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-blue-200 transition-all flex items-center">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i> <?= __('save_btn') ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>