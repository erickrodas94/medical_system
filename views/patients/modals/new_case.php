<div id="modalNewCase" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl sm:max-w-lg w-full border border-slate-200">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Nuevo Caso Clínico</h3>
                <button onclick="closeModal('modalNewCase')" class="text-slate-400 hover:text-rose-500"><i data-lucide="x"></i></button>
            </div>
            <form action="<?= URL_BASE ?>pacientes/caso/guardar" method="POST" class="p-6 space-y-4">
                <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>">
                <input type="hidden" name="person_id" value="<?= $patient['ID'] ?>">
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Título del Caso</label>
                    <input type="text" name="title" required placeholder="Ej: Control de Diabetes" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Motivo Inicial / Diagnóstico</label>
                    <textarea name="initial_reason" required rows="3" class="w-full border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('modalNewCase')" class="px-4 py-2 text-slate-600 font-semibold">Cancelar</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl font-bold shadow-md hover:bg-blue-700">Aperturar Caso</button>
                </div>
            </form>
        </div>
    </div>
</div>