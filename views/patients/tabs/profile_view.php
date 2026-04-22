<div class="space-y-6 overflow-y-auto h-full">
    
    <div class="border-b border-slate-100 pb-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-800"><?= __('patient_data') ?></h3>
        <a href="<?= URL_BASE ?>pacientes/editar/<?= $patient['patient_id'] ?>" class="text-sm font-bold text-blue-600 hover:text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg transition-colors flex items-center">
            <i data-lucide="edit-3" class="w-4 h-4 mr-1.5"></i> <?= __('edit_data') ?>
        </a>
    </div>

    <?php if(!empty($patient['critical_medical_alert'])): ?>
    <div class="bg-rose-600 text-white p-4 rounded-xl shadow-sm flex items-start border border-rose-700">
        <i data-lucide="alert-triangle" class="w-6 h-6 mr-3 flex-shrink-0"></i>
        <div>
            <h4 class="font-bold text-rose-50 tracking-wide uppercase text-xs"><?= __('critical_medical_alert') ?></h4>
            <p class="text-sm mt-1 font-medium"><?= htmlspecialchars($patient['critical_medical_alert']) ?></p>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                <i data-lucide="user" class="w-4 h-4 mr-2"></i> <?= __('section_personal_data') ?>
            </h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500 font-medium"><?= __('lbl_identity_type') ?></span>
                    <span class="font-bold text-slate-800"><?= __(htmlspecialchars($patient['identity_type_label'] ?? 'N/A')) ?>: <?= htmlspecialchars($patient['identity_number'] ?? 'N/A') ?></span>
                </div>
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500 font-medium"><?= __('lbl_birth_date') ?></span>
                    <span class="font-bold text-slate-800">
                        <?= date('d M, Y', strtotime($patient['birth_date'])) ?> 
                        <span class="text-slate-400 font-normal text-xs ml-1">(<?= __('patient_age') ?>: <?= date_diff(date_create($patient['birth_date']), date_create('today'))->y ?>)</span>
                    </span>
                </div>
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500 font-medium"><?= __('lbl_gender') ?></span>
                    <span class="font-bold text-slate-800"><?= !empty($patient['gender']) ? __('gender_' . strtolower($patient['gender'])) : '<span class="text-slate-300 italic">'.__('not_registered').'</span>' ?></span>
                </div>
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500 font-medium"><?= __('marital_status') ?></span>
                    <span class="font-bold text-slate-800"><?= !empty($patient['marital_status']) ? __('status_' . $patient['marital_status']) : '<span class="text-slate-300 italic">'.__('not_registered').'</span>' ?></span>
                </div>
                <div class="flex justify-between pb-1">
                    <span class="text-slate-500 font-medium"><?= __('lbl_status') ?></span>
                    <span class="font-bold <?= $patient['patient_status'] === 'Active' ? 'text-emerald-600' : 'text-slate-400' ?>">
                        <?= __('status_' . $patient['patient_status']) ?? 'Active' ?>
                    </span>
                </div>
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500"><?= __('lbl_ethnicity') ?? 'Etnia' ?>:</span>
                    <span class="font-bold text-slate-800"><?= !empty($patient['ethnicity']) ? htmlspecialchars($patient['ethnicity']) : '<span class="text-slate-300 italic">'.__('not_registered').'</span>' ?></span>
                </div>
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500"><?= __('lbl_religion') ?? 'Religión' ?>:</span>
                    <span class="font-bold text-slate-800"><?= !empty($patient['religion']) ? __('rel_' . strtolower($patient['religion'])) : '<span class="text-slate-300 italic">'.__('not_registered').'</span>' ?></span>
                </div>
            </div>
        </div>

        <div class="bg-rose-50 p-5 rounded-2xl border border-rose-100 shadow-sm">
            <h4 class="text-xs font-bold text-rose-500 uppercase tracking-widest mb-4 flex items-center">
                <i data-lucide="heart" class="w-4 h-4 mr-2"></i> <?= __('patient_critical') ?>
            </h4>
            
            <div class="flex items-center justify-between bg-white p-4 rounded-xl border border-rose-100 mb-4">
                <span class="text-slate-600 font-bold"><?= __('blood_type') ?></span>
                <span class="text-2xl font-black text-rose-600 drop-shadow-sm">
                    <?= $patient['blood_type'] !== 'Unknown' ? $patient['blood_type'] : '<span class="text-sm text-slate-400 font-normal">No registrado</span>' ?>
                </span>
            </div>

            <div class="space-y-2 text-sm bg-white p-4 rounded-xl border border-rose-100">
                <h5 class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-2"><?= __('emergency_contact') ?></h5>
                <?php if(!empty($patient['emergency_contact_name'])): ?>
                    <p><span class="font-semibold text-rose-700"><?= __('emergency_contact_name') ?>:</span> <?= htmlspecialchars($patient['emergency_contact_name']) ?> <span class="text-xs text-rose-400 ml-1">(<?= !empty($patient['emergency_contact_relationship']) ? __('rel_' . $patient['emergency_contact_relationship']) : '' ?>)</span></p>
                    <p><span class="font-semibold text-rose-700"><?= __('emergency_contact_phone') ?>:</span> <?= htmlspecialchars($patient['emergency_contact_phone']) ?></p>
                <?php else: ?>
                    <p class="text-slate-400 italic text-xs"><?= __('not_registered') ?></p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm md:col-span-2 lg:col-span-1">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center">
                <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i> <?= __('section_contact_location') ?>
            </h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500 font-medium"><?= __('lbl_phone') ?></span>
                    <span class="font-bold text-slate-800"><?= !empty($patient['primary_cellphone']) ? htmlspecialchars($patient['primary_cellphone']) : '<span class="text-slate-300 italic">'.__('not_registered').'</span>' ?></span>
                </div>
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500 font-medium"><?= __('secondary_phone') ?></span>
                    <span class="font-bold text-slate-800"><?= !empty($patient['landline_phone']) ? htmlspecialchars($patient['landline_phone']) : '<span class="text-slate-300 italic">'.__('not_registered').'</span>' ?></span>
                </div>
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500 font-medium"><?= __('lbl_email') ?></span>
                    <span class="font-bold text-slate-800 truncate" title="<?= htmlspecialchars($patient['primary_email'] ?? '') ?>"><?= !empty($patient['primary_email']) ? htmlspecialchars($patient['primary_email']) : '<span class="text-slate-300 italic">'.__('not_registered').'</span>' ?></span>
                </div>
                
                <div class="flex justify-between border-b border-slate-50 pb-2">
                    <span class="text-slate-500 font-medium mb-1"><?= __('lbl_address') ?></span>
                    <span class="font-bold text-slate-800 leading-relaxed">
                        <?php 
                        $fullAddress = trim(($patient['address_line1'] ?? '') . ' ' . ($patient['address_line2'] ?? ''));
                        echo !empty($fullAddress) ? htmlspecialchars($fullAddress) : '<span class="text-slate-300 italic">'.__('not_registered').'</span>';
                        ?>
                    </span>
                </div>

                <div class="flex justify-between pb-1 pt-1">
                    <span class="text-slate-500 font-medium"><?= __('lbl_location') ?></span>
                    <span class="font-bold text-slate-800 text-right w-2/3">
                        <?php
                        $locationParts = [];
                        if (!empty($patient['state_name'])) $locationParts[] = $patient['state_name'];
                        if (!empty($patient['country_name'])) $locationParts[] = __($patient['country_name']);
                        
                        $locString = implode(', ', $locationParts);
                        if(!empty($patient['postal_code'])) {
                            $locString .= ' (CP: ' . htmlspecialchars($patient['postal_code']) . ')';
                        }
                        
                        echo !empty($locString) ? htmlspecialchars($locString) : '<span class="text-slate-300 italic">'.__('not_registered').'</span>';
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <?php if(!empty($patient['tutor_fname'])): ?>
        <div class="bg-amber-50 p-5 rounded-2xl border border-amber-200 shadow-sm md:col-span-2 lg:col-span-1">
            <h4 class="text-xs font-bold text-amber-600 uppercase tracking-widest mb-4 flex items-center">
                <i data-lucide="shield" class="w-4 h-4 mr-2"></i> <?= __('lbl_tutor') ?>
            </h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-amber-100 pb-2">
                    <span class="text-amber-700 font-medium"><?= __('tutor_name') ?></span>
                    <span class="font-bold text-amber-900"><?= htmlspecialchars($patient['tutor_fname'] . ' ' . $patient['tutor_lname']) ?></span>
                </div>
                <div class="flex justify-between border-b border-amber-100 pb-2">
                    <span class="text-amber-700 font-medium"><?= __('lbl_tutor_relation') ?></span>
                    <span class="font-bold text-amber-900"><?= htmlspecialchars($patient['tutor_relation_translated'] ?? $patient['tutor_relation']) ?></span>
                </div>
                <div class="flex justify-between border-b border-amber-100 pb-2">
                    <span class="text-amber-700 font-medium"><?= __(htmlspecialchars($patient['tutor_identity_type_label'] ?? 'ID')) ?></span>
                    <span class="font-bold text-amber-900">
                        <?= htmlspecialchars($patient['tutor_identity'] ?? 'N/A') ?>
                    </span>
                </div>
                <div class="flex justify-between pb-1">
                    <span class="text-amber-700 font-medium"><?= __('tutor_phone') ?></span>
                    <span class="font-bold text-amber-900"><?= htmlspecialchars($patient['tutor_phone']) ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>