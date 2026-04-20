<div class="space-y-6">
    <div class="border-b border-slate-100 pb-4 flex justify-between items-center">
        <h3 class="text-lg font-bold text-slate-800"><?= __('patient_data') ?></h3>
        <button class="text-sm text-blue-600 hover:underline"><i data-lucide="edit" class="w-4 h-4 inline"></i> <?= __('edit_data') ?></button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3"><?= __('patient_contact') ?></h4>
            <div class="space-y-2 text-sm">
                <p><span class="font-semibold text-slate-700"><?= __('lbl_email') ?>:</span> <?= !empty($patient['primary_email']) ? $patient['primary_email'] : __('not_registered') ?></p>
                <p><span class="font-semibold text-slate-700"><?= __('lbl_phone') ?>:</span> <?= !empty($patient['primary_cellphone']) ? $patient['primary_cellphone'] : __('not_registered') ?></p>
                <p><span class="font-semibold text-slate-700"><?= __('lbl_address') ?>:</span> <?= !empty($patient['address_line1']) ? $patient['address_line1'] : __('not_registered') ?></p>
            </div>
        </div>

        <div class="bg-rose-50 p-4 rounded-xl border border-rose-100">
            <h4 class="text-xs font-bold text-rose-400 uppercase tracking-widest mb-3"><?= __('patient_critical') ?></h4>
            <div class="space-y-2 text-sm">
                <p><span class="font-semibold text-rose-700"><?= __('blood_type') ?>:</span> <span class="bg-rose-200 text-rose-800 px-2 rounded font-bold"><?= $patient['blood_type'] ?></span></p>
                <p><span class="font-semibold text-rose-700"><?= __('emergency_contact') ?>:</span> <?= !empty($patient['emergency_contact_name']) ? $patient['emergency_contact_name'] . ' (' . $patient['emergency_contact_phone'] . ')' : 'No registrado' ?></p>
            </div>
        </div>
    </div>
    
    <?php if(!empty($patient['critical_medical_alert'])): ?>
    <div class="bg-red-600 text-white p-4 rounded-xl shadow-sm flex items-start">
        <i data-lucide="alert-triangle" class="w-6 h-6 mr-3 flex-shrink-0"></i>
        <div>
            <h4 class="font-bold"><?= __('critical_medical_alert') ?></h4>
            <p class="text-sm mt-1"><?= htmlspecialchars($patient['critical_medical_alert']) ?></p>
        </div>
    </div>
    <?php endif; ?>
</div>