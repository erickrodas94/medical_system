<div id="modalTriage" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-2xl w-full border border-slate-200">
            
            <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mr-3">
                        <i data-lucide="heart-pulse" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Registrar Signos Vitales</h3>
                        <p class="text-xs text-slate-500">Paciente: <?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></p>
                    </div>
                </div>
                <button onclick="document.getElementById('modalTriage').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="<?= URL_BASE ?>pacientes/triage/guardar" method="POST">
                <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>">
                <input type="hidden" name="case_id" value="<?= $activeCaseId ?>">
                
                <div class="px-6 py-6 space-y-6">
                    
                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 border-b border-slate-100 pb-2">Cardiopulmonar</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Presión Arterial (Sys/Dia)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" name="bp_sys" placeholder="120" class="w-full border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-center">
                                    <span class="text-slate-400 font-bold">/</span>
                                    <input type="number" name="bp_dia" placeholder="80" class="w-full border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 text-center">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Frec. Cardíaca</label>
                                <div class="relative">
                                    <input type="number" name="heart_rate" placeholder="75" class="w-full border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 pr-10">
                                    <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-bold">bpm</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">SpO2 (Oxígeno)</label>
                                <div class="relative">
                                    <input type="number" name="oxygen_saturation" placeholder="98" class="w-full border-slate-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 pr-8">
                                    <span class="absolute right-3 top-2.5 text-xs text-slate-400 font-bold">%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 border-b border-slate-100 pb-2">Medidas Físicas</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Temperatura</label>
                                <div class="flex">
                                    <input type="number" step="0.1" name="temperature_value" placeholder="36.5" class="w-2/3 border-slate-300 rounded-l-lg focus:ring-emerald-500 focus:border-emerald-500 border-r-0">
                                    <select name="temperature_unit" class="w-1/3 border-slate-300 rounded-r-lg bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500 px-2 text-sm">
                                        <option value="C">°C</option>
                                        <option value="F">°F</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Peso</label>
                                <div class="flex">
                                    <input type="number" step="0.1" name="weight_value" placeholder="70.5" class="w-2/3 border-slate-300 rounded-l-lg focus:ring-emerald-500 focus:border-emerald-500 border-r-0">
                                    <select name="weight_unit" class="w-1/3 border-slate-300 rounded-r-lg bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500 px-2 text-sm">
                                        <option value="kg">kg</option>
                                        <option value="lb">lb</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Altura</label>
                                <div class="flex">
                                    <input type="number" step="0.1" name="height_value" placeholder="175" class="w-2/3 border-slate-300 rounded-l-lg focus:ring-emerald-500 focus:border-emerald-500 border-r-0">
                                    <select name="height_unit" class="w-1/3 border-slate-300 rounded-r-lg bg-slate-50 focus:ring-emerald-500 focus:border-emerald-500 px-2 text-sm">
                                        <option value="cm">cm</option>
                                        <option value="mt">m</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-2"><i data-lucide="info" class="w-3 h-3 inline"></i> El sistema calculará el IMC (BMI) automáticamente al guardar.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Notas Clínicas de Enfermería (Opcional)</label>
                        <textarea name="clinical_notes" rows="2" placeholder="Paciente refiere dolor al caminar, se observa pálido..." class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm"></textarea>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modalTriage').classList.add('hidden')" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl text-sm font-semibold transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-emerald-200 transition-all flex items-center">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i> Guardar Signos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>