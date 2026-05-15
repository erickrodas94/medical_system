<?php if(!$activeCaseId): ?>
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
            <i data-lucide="folder-search" class="w-8 h-8 text-slate-300"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-700 mb-1"><?= __('no_active_case') ?></h3>
        <p class="text-slate-400 italic"><?= __('no_active_case') ?></p>
    </div>
<?php else: ?>
    
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-wrap gap-4 items-center justify-between mb-8">
        <div class="flex gap-2">
            <button onclick="filterTimeline('all', this)" class="filter-btn bg-slate-800 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-md"><?= __('all_data') ?></button>
            <button onclick="filterTimeline('evolution', this)" class="filter-btn bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-100 transition-all"><?= __('evolutions') ?></button>
            <button onclick="filterTimeline('vitals', this)" class="filter-btn bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-100 transition-all"><?= __('vitals_signs') ?></button>
            <button onclick="filterTimeline('prescription', this)" class="filter-btn bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-100 transition-all"><?= __('prescriptions') ?></button>
            <button onclick="filterTimeline('documents', this)" class="filter-btn bg-white border border-slate-300 text-slate-600 px-4 py-2 rounded-lg text-xs font-bold hover:bg-slate-100 transition-all"><?= __('documents') ?></button>
        </div>

        <div class="flex items-center gap-3">
            <select id="doctor-filter" onchange="filterByDoctor(this.value)" class="text-xs font-bold border-slate-300 text-slate-600 rounded-lg bg-slate-50 px-3 py-2 outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="all"><?= __('all_doctors') ?></option>
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
                    <button onclick="document.getElementById('modalEvolution').classList.remove('hidden'); document.getElementById('register-dropdown').classList.add('hidden');" class="w-full flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
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
            <?php 
                $evolutionCount = 0;
                $prescriptionCount = 0;
                foreach ($timeline as $item): 
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

                            <?php 
                                elseif($type === 'evolution'): 
                                    $evolutionCount++;
                            ?>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <?php if(($item['status'] ?? '') === 'Draft'): ?>
                                        <span class="bg-amber-100 text-amber-700 px-2.5 py-0.5 rounded flex items-center text-[10px] font-bold uppercase tracking-wider">
                                            <i data-lucide="edit-3" class="w-3 h-3 mr-1.5"></i> <?= __('Draft') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded flex items-center text-[10px] font-bold uppercase tracking-wider">
                                            <i data-lucide="check-circle" class="w-3 h-3 mr-1.5"></i> <?= __('Finalized') ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php 
                                        // 1. Obtenemos el tipo (si está vacío, asumimos presencial)
                                        $asstType = $item['type'] ?? 'In-Person'; 
                                        
                                        // 2. Mapeamos el tipo con su ícono ideal de Lucide
                                        $typeIcons = [
                                            'In-Person' => 'user',
                                            'Video'     => 'video',
                                            'Phone'     => 'phone-call',
                                            'Chat'      => 'message-square'
                                        ];

                                        // 3. Traducimos al español (opcional, si no lo tienes en tu función __() )
                                        $typeLabels = [
                                            'In-Person' => __('In-Person'),
                                            'Video'     => __('Video'),
                                            'Phone'     => __('Phone'),
                                            'Chat'      => __('Chat')
                                        ];

                                        // Asignamos el ícono (si por algún error llega otro texto, pone estetoscopio por defecto)
                                        $iconToUse = $typeIcons[$asstType] ?? 'stethoscope';
                                        $labelToUse = $typeLabels[$asstType] ?? $asstType;
                                    ?>
                                    <span class="bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded flex items-center text-[10px] font-bold uppercase tracking-wider">
                                        <i data-lucide="<?= $iconToUse ?>" class="w-3 h-3 mr-1.5"></i> <?= htmlspecialchars($labelToUse) ?>
                                    </span>
                                </div>

                                <p id=<?= 'main-evolution-text' . $evolutionCount ?> class="text-sm text-slate-700 leading-relaxed mb-4 line-clamp-3"><?= nl2br(htmlspecialchars($item['main_content'])) ?></p>

                                <?php 
                                    // Mostramos el botón si hay más de 180 caracteres o si hay más de 3 saltos de línea (4 líneas de texto)
                                    if(strlen($item['main_content']) > 180 || substr_count($item['main_content'], "\n") >= 3): 
                                ?>
                                    <button onclick="toggleCaseReason('<?= 'main-evolution-text' . $evolutionCount ?>', '<?= 'btnForMoreEvolution' . $evolutionCount ?>', '<?= 'btnForLessEvolution' . $evolutionCount ?>')" id="<?= 'btnForMoreEvolution' . $evolutionCount ?>" class="text-xs font-bold text-slate-700 hover:text-slate-900 mt-1 cursor-pointer">
                                        <?= __('read_more') ?>
                                    </button>
                                    <button onclick="toggleCaseReason('<?= 'main-evolution-text' . $evolutionCount ?>', '<?= 'btnForLessEvolution' . $evolutionCount ?>', '<?= 'btnForMoreEvolution' . $evolutionCount ?>')" id="<?= 'btnForLessEvolution' . $evolutionCount ?>" class="text-xs font-bold text-slate-700 hover:text-slate-900 mt-1 cursor-pointer hidden">
                                        <?= __('read_less') ?>
                                    </button>
                                <?php endif; ?>
                                
                                <?php if($item['sub_content']): ?>
                                    <p class="pt-3"></p>
                                    <div class="bg-slate-50 p-4 rounded-xl border-l-4 border-l-slate-300 mb-4">
                                        <h5 class="text-[10px] font-bold text-slate-500 uppercase mb-2"><?= __('physical_exam_notes') ?></h5>
                                        <p id="<?= 'sub-evolution-text' . $evolutionCount ?>" class="text-sm text-slate-600 italic line-clamp-3"><?= nl2br(htmlspecialchars($item['sub_content'])) ?></p>

                                        <?php 
                                            // Mostramos el botón si hay más de 180 caracteres o si hay más de 3 saltos de línea (4 líneas de texto)
                                            if(strlen($item['sub_content']) > 180 || substr_count($item['sub_content'], "\n") >= 3): 
                                        ?>
                                            <button onclick="toggleCaseReason('<?= 'sub-evolution-text' . $evolutionCount ?>', '<?= 'btnForMoreSubEvolution' . $evolutionCount ?>', '<?= 'btnForLessSubEvolution' . $evolutionCount ?>')" id="<?= 'btnForMoreSubEvolution' . $evolutionCount ?>" class="text-xs font-bold text-slate-600 hover:text-slate-800 mt-1 cursor-pointer">
                                                <?= __('read_more') ?>
                                            </button>
                                            <button onclick="toggleCaseReason('<?= 'sub-evolution-text' . $evolutionCount ?>', '<?= 'btnForLessSubEvolution' . $evolutionCount ?>', '<?= 'btnForMoreSubEvolution' . $evolutionCount ?>')" id="<?= 'btnForLessSubEvolution' . $evolutionCount ?>" class="text-xs font-bold text-slate-600 hover:text-slate-800 mt-1 cursor-pointer hidden">
                                                <?= __('read_less') ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if(($item['status'] ?? '') === 'Draft'): ?>
                                    <div class="flex gap-4 border-t border-slate-100 pt-3 mt-2">
                                        <a href="<?= URL_BASE ?>evoluciones/editar/<?= $item['record_id'] ?>" class="text-[11px] font-bold text-indigo-600 hover:text-indigo-800 flex items-center transition-colors uppercase tracking-wide">
                                            <i data-lucide="edit-2" class="w-3.5 h-3.5 mr-1.5"></i> <?= __('edit_btn') ?>
                                        </a>
                                        <button onclick="deleteEvolution(<?= $item['record_id'] ?>)" class="text-[11px] font-bold text-red-500 hover:text-red-700 flex items-center transition-colors uppercase tracking-wide">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5 mr-1.5"></i> <?= __('delete_btn') ?>
                                        </button>
                                    </div>
                                <?php endif; ?>

                            <?php 
                            elseif($type === 'prescription'):
                                $prescriptionCount++; 
                            ?>
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
                                            <i data-lucide="info" class="w-3 h-3 mr-1.5"></i> <?= __('patient_instructions') ?>
                                        </h5>
                                        <p id="<?= 'sub-prescription-text' . $prescriptionCount ?>" class="text-sm text-slate-700 leading-relaxed line-clamp-3"><?= nl2br(htmlspecialchars($item['sub_content'])) ?></p>
                                        
                                        <?php 
                                            // Mostramos el botón si hay más de 180 caracteres o si hay más de 3 saltos de línea (4 líneas de texto)
                                            if(strlen($item['sub_content']) > 180 || substr_count($item['sub_content'], "\n") >= 3): 
                                        ?>
                                            <button onclick="toggleCaseReason('<?= 'sub-prescription-text' . $prescriptionCount ?>', '<?= 'btnForMoreSubPrescription' . $prescriptionCount ?>', '<?= 'btnForLessSubPrescription' . $prescriptionCount ?>')" id="<?= 'btnForMoreSubPrescription' . $prescriptionCount ?>" class="text-xs font-bold text-slate-600 hover:text-slate-800 mt-1 cursor-pointer">
                                                <?= __('read_more') ?>
                                            </button>
                                            <button onclick="toggleCaseReason('<?= 'sub-prescription-text' . $prescriptionCount ?>', '<?= 'btnForLessSubPrescription' . $prescriptionCount ?>', '<?= 'btnForMoreSubPrescription' . $prescriptionCount ?>')" id="<?= 'btnForLessSubPrescription' . $prescriptionCount ?>" class="text-xs font-bold text-slate-600 hover:text-slate-800 mt-1 cursor-pointer hidden">
                                                <?= __('read_less') ?>
                                            </button>
                                        <?php endif; ?>
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