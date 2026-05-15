<div id="modalEditCase" class="hidden fixed inset-0 z-[100] flex items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="document.getElementById('modalEditCase').classList.add('hidden')"></div>
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg relative z-10 p-6">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-slate-800">Editar Caso Clínico</h3>
            <button onclick="document.getElementById('modalEditCase').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        
        <form action="<?= URL_BASE ?>pacientes/caso/editar" method="POST">
            <input type="hidden" name="case_id" value="<?= $activeCaseId ?>">
            <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>">

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Nombre del Caso</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($activeCaseData['title'] ?? '') ?>" class="w-full px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('modalEditCase').classList.add('hidden')" class="px-4 py-2 text-slate-500 hover:bg-slate-100 rounded-xl font-bold transition-colors">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-sm transition-colors">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>