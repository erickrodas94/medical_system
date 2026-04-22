<?php include '../views/layouts/header.php'; ?>

<div class="flex-1 flex flex-col h-screen bg-slate-50 overflow-y-auto">
    
    <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-40 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="<?= URL_BASE ?>pacientes/ver/<?= $patient['patient_id'] ?>" class="p-2 bg-slate-100 text-slate-500 rounded-lg hover:bg-slate-200 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800"><?= __('edit_data') ?></h1>
                <p class="text-sm text-slate-500 font-medium"><?= htmlspecialchars($patient['first_name'] . ' ' . $patient['last_name']) ?></p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="<?= URL_BASE ?>pacientes/ver/<?= $patient['patient_id'] ?>" class="px-5 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl text-sm font-bold transition-all">
                <?= __('cancel_btn') ?>
            </a>
            <button type="button" onclick="document.getElementById('formEditPatient').submit()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-md shadow-blue-200 transition-all flex items-center">
                <i data-lucide="save" class="w-5 h-5 mr-2"></i> <?= __('save_btn') ?>
            </button>
        </div>
    </div>

    <main class="max-w-4xl mx-auto w-full p-6 pb-32">
        <form id="formEditPatient" action="<?= URL_BASE ?>pacientes/actualizar" method="POST" class="space-y-6">
            
            <input type="hidden" name="patient_id" value="<?= $patient['patient_id'] ?>"> 
            <input type="hidden" name="person_id" value="<?= $patient['ID'] ?>"> 

            <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden shadow-sm">
                <div class="bg-rose-50 px-5 py-3 border-b border-rose-100 flex items-center">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600 mr-2"></i>
                    <h3 class="font-bold text-rose-900"><?= __('patient_critical') ?></h3>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-1 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('blood_type') ?></label>
                        <select name="blood_type" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                            <?php 
                            $bloodTypes = ['Unknown', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                            $currentBlood = $patient['blood_type'] ?? 'Unknown';
                            foreach($bloodTypes as $bt): 
                            ?>
                                <option value="<?= $bt ?>" <?= $currentBlood === $bt ? 'selected' : '' ?>>
                                    <?= $bt === 'Unknown' ? __('select_default') : $bt ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('critical_medical_alert') ?></label>
                        <textarea name="critical_medical_alert" rows="2" placeholder="<?= __('critical_medical_alert_placeholder') ?? 'Alerta médica' ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm"><?= htmlspecialchars($patient['critical_medical_alert'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden shadow-sm">
                <div class="bg-rose-50 px-5 py-3 border-b border-rose-100 flex items-center">
                    <i data-lucide="phone-call" class="w-5 h-5 text-rose-600 mr-2"></i>
                    <h3 class="font-bold text-rose-900"><?= __('emergency_contact') ?></h3>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('emergency_contact_name') ?></label>
                        <input type="text" name="emergency_contact_name" value="<?= htmlspecialchars($patient['emergency_contact_name'] ?? '') ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('emergency_contact_phone') ?></label>
                        <input type="text" name="emergency_contact_phone" value="<?= htmlspecialchars($patient['emergency_contact_phone'] ?? '') ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('emergency_contact_relationship') ?></label>
                        <select name="emergency_contact_relationship" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                            <option value=""><?= __('select_default') ?></option>
                            <?php foreach(['Mother', 'Father', 'Partner', 'Grandparent', 'Legal Guardian', 'Child', 'Other'] as $rel): ?>
                                <option value="<?= $rel ?>" <?= ($patient['emergency_contact_relationship'] ?? '') === $rel ? 'selected' : '' ?>>
                                    <?= __('rel_' . $rel) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-slate-50 px-5 py-3 border-b border-slate-200 flex items-center">
                    <i data-lucide="user" class="w-5 h-5 text-slate-600 mr-2"></i>
                    <h3 class="font-bold text-slate-800"><?= __('section_personal_data') ?></h3>
                </div>
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_first_name') ?></label>
                        <input type="text" name="first_name" value="<?= htmlspecialchars($patient['first_name']) ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_last_name') ?></label>
                        <input type="text" name="last_name" value="<?= htmlspecialchars($patient['last_name']) ?>" required class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_gender') ?></label>
                        <select name="gender" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                            <option value=""><?= __('select_default') ?></option>
                            <option value="Female" <?= ($patient['gender'] ?? '') === 'Female' ? 'selected' : '' ?>><?= __('gender_female') ?? 'Femenino' ?></option>
                            <option value="Male" <?= ($patient['gender'] ?? '') === 'Male' ? 'selected' : '' ?>><?= __('gender_male') ?? 'Masculino' ?></option>
                            <option value="Other" <?= ($patient['gender'] ?? '') === 'Other' ? 'selected' : '' ?>><?= __('gender_other') ?? 'Otro' ?></option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('marital_status') ?></label>
                        <select name="marital_status" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                            <?php foreach(['Single', 'Married', 'Divorced', 'Widowed', 'Other'] as $st): ?>
                                <option value="<?= $st ?>" <?= ($patient['marital_status'] ?? 'Single') === $st ? 'selected' : '' ?>>
                                    <?= __('status_' . $st) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="opacity-70 cursor-not-allowed">
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_identity_number') ?></label>
                        <div class="flex gap-2">
                            <input type="text" value="<?= __(htmlspecialchars($patient['identity_type_label'] ?? 'ID')) ?>" disabled class="w-1/3 px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-500">
                            <input type="text" value="<?= htmlspecialchars($patient['identity_number'] ?? 'N/A') ?>" disabled class="w-2/3 px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-500">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1"><?= __('identity_number_not_editable') ?? 'No editable' ?></p>
                    </div>

                    <div class="opacity-70 cursor-not-allowed">
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_birth_date') ?></label>
                        <input type="date" value="<?= $patient['birth_date'] ?>" disabled class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_ethnicity') ?? 'Etnia' ?></label>
                        <input type="text" name="ethnicity" value="<?= htmlspecialchars($patient['ethnicity'] ?? '') ?>" class="w-full px-4 py-2 border rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_religion') ?? 'Religión' ?></label>
                        <select name="religion" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-white text-sm">
                            <option value=""><?= __('select_default') ?></option>
                            <?php 
                            $religions = ['Catholic', 'Protestant', 'Christian', 'Jewish', 'Muslim', 'Buddhist', 'Hindu', 'Atheist', 'Other'];
                            foreach($religions as $rel): ?>
                                <option value="<?= $rel ?>" <?= ($patient['religion'] ?? '') === $rel ? 'selected' : '' ?>>
                                    <?= __('rel_' . strtolower($rel)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
            </div>

           <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                <div class="bg-slate-50 px-5 py-3 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center">
                        <i data-lucide="map-pin" class="w-5 h-5 text-slate-600 mr-2"></i>
                        <h3 class="font-bold text-slate-800"><?= __('section_contact_location') ?></h3>
                    </div>
                </div>
                
                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_phone') ?></label>
                        <input type="text" name="primary_cellphone" value="<?= htmlspecialchars($patient['primary_cellphone'] ?? '') ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('secondary_phone') ?? 'Teléfono Secundario' ?></label>
                        <input type="text" name="landline_phone" value="<?= htmlspecialchars($patient['landline_phone'] ?? '') ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                    </div>
                    <div class="md:col-span-2 border-b border-slate-100 pb-5">
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_email') ?></label>
                        <input type="email" name="primary_email" value="<?= htmlspecialchars($patient['primary_email'] ?? '') ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                    </div>

                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-5 pt-2">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_country') ?? 'País' ?></label>
                            <select name="country_ID" id="country_ID" onchange="loadStatesByCountry(this.value, 'state_ID')" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                                <option value=""><?= __('select_default') ?></option>
                                <?php if(isset($countries)): foreach($countries as $c): ?>
                                    <option value="<?= $c['ID'] ?>" <?= ($clinicCountryId == $c['ID']) ? 'selected' : '' ?>>
                                        <?= __(htmlspecialchars($c['iso_code'])) ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_state') ?? 'Estado / Departamento' ?></label>
                            <select name="state_ID" id="state_ID" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                                <option value=""><?= __('select_default') ?></option>
                                <?php if(isset($states)): foreach($states as $s): ?>
                                    <option value="<?= $s['ID'] ?>" <?= ($patient['state_ID'] ?? '') == $s['ID'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($s['name']) ?>
                                    </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_address') ?> Principal</label>
                        <input type="text" name="address_line1" value="<?= htmlspecialchars($patient['address_line1'] ?? '') ?>" placeholder="<?= __('address_placeholder_1') ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Complemento de Dirección</label>
                        <input type="text" name="address_line2" value="<?= htmlspecialchars($patient['address_line2'] ?? '') ?>" placeholder="<?= __('address_placeholder_2') ?>" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2"><?= __('lbl_postal_code') ?></label>
                        <input type="text" name="postal_code" value="<?= htmlspecialchars($patient['postal_code'] ?? '') ?>" placeholder="Ej: 01010" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white text-sm">
                    </div>
                </div>
            </div>

        </form>
    </main>
</div>

<script>
    const urlBase = '<?= URL_BASE ?>';
    function loadStatesByCountry(countryId, targetSelectId) {
        const selectDoc = document.getElementById(targetSelectId);
        if (!selectDoc) return;

        // Mostramos "Cargando..." mientras esperamos al servidor
        selectDoc.innerHTML = '<option value=""><?= __('select_default') ?></option>';

        // Hacemos la petición a la ruta que creamos en el Router
        fetch(`${urlBase}api/states?country_id=${countryId}`)
            .then(response => {
                if (!response.ok) throw new Error("<?= __('error_loading_states') ?>");
                return response.json();
            })
            .then(data => {
                // Limpiamos y ponemos la opción por defecto
                selectDoc.innerHTML = '<option value=""><?= __('select_default') ?></option>';
                
                // Recorremos los documentos que nos devolvió PHP y los agregamos al select
                data.forEach(state => {
                    const opt = document.createElement('option');
                    opt.value = state.ID;
                    
                    // Aquí usamos el label_key (ej. 'lbl_dpi'). 
                    // Si tienes las traducciones expuestas en JS, podrías traducirlo aquí.
                    // Antes: opt.textContent = state.label_key;
                    opt.textContent = state.name;
                    
                    // ¡Vital para tu validación Frontend!
                    opt.setAttribute('data-regex', state.validation_regex);
                    
                    selectDoc.appendChild(opt);
                });
            })
            .catch(error => {
                console.error('Error obteniendo documentos:', error);
                selectDoc.innerHTML = '<option value="">Error al carga'+error+'</option>';
            });
    }

    // document.addEventListener('DOMContentLoaded', function() {
    //     const countrySelect = document.getElementById('country_ID');
    //     const stateSelect = document.getElementById('state_ID');

    //     // Cargar Estados cuando cambia el País
    //     countrySelect.addEventListener('change', function() {
    //         const countryId = this.value;
    //         stateSelect.innerHTML = '<option value="">   </option>';
            
    //         if (countryId) {
    //             fetch(`${urlBase}api/states?country_id=${countryId}`)
    //                 .then(response => response.json())
    //                 .then(data => {
    //                     data.forEach(state => {
    //                         let option = new Option(state.name, state.ID);
    //                         stateSelect.add(option);
    //                     });
    //                 })
    //                 .catch(error => console.error('Error cargando estados:', error));
    //         }
    //     });
    // });

    function loadIdentityTypes(countryId, targetSelectId) {
        const selectDoc = document.getElementById(targetSelectId);
        if (!selectDoc) return;

        // Mostramos "Cargando..." mientras esperamos al servidor
        selectDoc.innerHTML = '<option value="">Cargando...</option>';

        // Hacemos la petición a la ruta que creamos en el Router
        fetch(`${urlBase}documentos/por-pais?id=${countryId}`)
            .then(response => {
                if (!response.ok) throw new Error('Error en la red');
                return response.json();
            })
            .then(data => {
                // Limpiamos y ponemos la opción por defecto
                selectDoc.innerHTML = '<option value="">Seleccione...</option>';
                
                // Recorremos los documentos que nos devolvió PHP y los agregamos al select
                data.forEach(type => {
                    const opt = document.createElement('option');
                    opt.value = type.ID;
                    
                    // Aquí usamos el label_key (ej. 'lbl_dpi'). 
                    // Si tienes las traducciones expuestas en JS, podrías traducirlo aquí.
                    // Si no, mostrará el nombre técnico o puedes ajustar PHP para que devuelva la palabra ya traducida.
                    // Antes: opt.textContent = type.label_key;
                    opt.textContent = type.label_translated;
                    
                    // ¡Vital para tu validación Frontend!
                    opt.setAttribute('data-regex', type.validation_regex);
                    
                    selectDoc.appendChild(opt);
                });
            })
            .catch(error => {
                console.error('Error obteniendo documentos:', error);
                selectDoc.innerHTML = '<option value="">Error al cargar</option>';
            });
    }
</script>

<?php include '../views/layouts/footer.php'; ?>