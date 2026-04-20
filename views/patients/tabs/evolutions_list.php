<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-slate-100 pb-4">
        <h3 class="text-lg font-bold text-slate-800"><?= __('evolution_soap') ?? 'Evoluciones Médicas' ?></h3>
        <?php if($activeCaseId): ?>
            <button onclick="document.getElementById('modalEvolution').classList.remove('hidden')" class="text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center bg-blue-50 px-3 py-1.5 rounded-lg transition-colors shadow-sm border border-blue-100 hover:bg-blue-100">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> <?= __('new_evolution_note') ?? 'Nueva Evolución' ?>
            </button>
            <a href="<?= URL_BASE ?>pacientes/consulta/<?= $patient['patient_id'] ?>?case_id=<?= $activeCaseId ?>" class="text-sm font-bold text-blue-600 hover:text-blue-700 flex items-center bg-blue-50 px-3 py-1.5 rounded-lg transition-colors shadow-sm border border-blue-100 hover:bg-blue-100">
                <i data-lucide="plus" class="w-4 h-4 mr-1"></i> <?= __('new_consultation') ?? 'Nueva Consulta' ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if(empty($evolutions)): ?>
        <div class="text-center py-12 bg-slate-50 rounded-xl border border-dashed border-slate-200">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-slate-100">
                <i data-lucide="clipboard-x" class="w-8 h-8 text-slate-300"></i>
            </div>
            <h4 class="text-slate-600 font-bold text-lg"><?= __('no_evolutions_registered') ?? 'Aún no hay evoluciones registradas' ?></h4>
            <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto"><?= __('no_evolutions_registered_help') ?? 'Presiona "Nueva Evolución" para redactar la primera nota médica de este caso.' ?></p>
        </div>
        
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach($evolutions as $ev): ?>
                
                <div class="border <?= $ev['status'] === 'Finalized' ? 'border-slate-200 bg-white' : 'border-amber-300 bg-amber-50' ?> rounded-xl p-5 hover:shadow-md transition-shadow relative overflow-hidden">
                    
                    <?php if($ev['status'] === 'Draft'): ?>
                        <div class="absolute top-0 right-0 bg-amber-400 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-bl-lg shadow-sm">
                            <?= __('draft') ?? 'Borrador' ?>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-sm font-bold text-slate-800 flex items-center">
                                <i data-lucide="calendar" class="w-4 h-4 mr-2 text-blue-500"></i> 
                                <?= date('d M, Y - h:i A', strtotime($ev['created_at'])) ?>
                                <span class="ml-2 px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] rounded border border-slate-200">
                                    <?= __($ev['assistance_type']) ?? $ev['assistance_type'] ?>
                                </span>
                            </span>
                            <span class="text-xs text-slate-500 block mt-2">
                                <i data-lucide="user" class="w-3 h-3 inline mr-1"></i>
                                <?= __('doctor') ?> <?= htmlspecialchars($ev['doc_fname'] . ' ' . $ev['doc_lname']) ?>
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <button class="p-1.5 bg-white text-slate-400 hover:text-blue-600 border border-slate-200 rounded shadow-sm hover:border-blue-200 transition-colors" title="Ver detalle completo">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            
                            <?php if($ev['status'] === 'Draft'): ?>
                                <button class="p-1.5 bg-white text-amber-500 hover:text-amber-700 border border-slate-200 rounded shadow-sm hover:border-amber-300 transition-colors" title="Continuar editando">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm">
                        <div class="bg-white/60 p-3 rounded-lg border <?= $ev['status'] === 'Finalized' ? 'border-slate-100' : 'border-amber-200/50' ?>">
                            <p class="text-xs font-bold text-slate-400 mb-1 uppercase"><?= __('evolution_notes') ?? 'Notas de Evolución' ?></p>
                            <p class="text-slate-700 line-clamp-2">
                                <?= !empty($ev['evolution_notes']) ? nl2br(htmlspecialchars($ev['evolution_notes'])) : '<span class="text-slate-300 italic">Sin datos</span>' ?>
                            </p>
                        </div>
                        <div class="bg-white/60 p-3 rounded-lg border <?= $ev['status'] === 'Finalized' ? 'border-slate-100' : 'border-amber-200/50' ?>">
                            <p class="text-xs font-bold text-slate-400 mb-1 uppercase"><?= __('patient_instructions') ?? 'Plan / Instrucciones' ?></p>
                            <p class="text-slate-700 line-clamp-2">
                                <?= !empty($ev['patient_instructions']) ? htmlspecialchars($ev['patient_instructions']) : '<span class="text-slate-300 italic">Sin datos</span>' ?>
                            </p>
                        </div>
                    </div>
                    
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>