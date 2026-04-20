<?php include '../views/layouts/header.php'; ?>
<?php include '../views/layouts/sidebar.php'; ?>

<div class="flex-1 flex flex-col h-full overflow-hidden">
    <?php include '../views/layouts/topbar.php'; ?>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-4 sm:p-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800"><?= __('patients_files_title') ?></h1>
                <p class="text-slate-500 text-sm"><?= __('patients_files_subtitle') ?></p>
            </div>
            <button onclick="openModal('modalNuevoPaciente')" class="flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-sm transition-all font-medium">
                <i data-lucide="user-plus" class="w-5 h-5 mr-2"></i>
                <?= __('patients_files_btn_add') ?>
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            
            <div class="p-4 border-b border-slate-100 bg-white flex items-center justify-between gap-4">
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 w-5 h-5"></i>
                    <input type="text" id="searchPatientInput" placeholder="<?= __('search_patient_placeholder') ?>" class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all text-sm">
                </div>
                </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="patientsTable">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider"><?= __('table_patient') ?></th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider"><?= __('table_contact') ?></th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider"><?= __('table_status') ?></th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right"><?= __('table_actions') ?></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if(empty($patients)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    <i data-lucide="search-x" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                                    <p><?= __('patients_empty_state') ?></p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($patients as $p): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 text-xs">
                                            <?= strtoupper(substr($p['first_name'],0,1) . substr($p['last_name'],0,1)) ?>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800"><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></p>
                                            <p class="text-xs text-slate-400 font-mono">ID: <?= strtoupper(substr($p['uuid'], 0, 8)) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <?php if(!empty($p['primary_cellphone'])): ?>
                                        <p class="text-slate-600 font-medium"><?= htmlspecialchars($p['primary_cellphone']) ?></p>
                                    <?php elseif(!empty($p['tutor_phone'])): ?>
                                        <p class="text-slate-600 font-medium flex items-center">
                                            <?= htmlspecialchars($p['tutor_phone']) ?>
                                            <span class="ml-2 px-1.5 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase rounded border border-indigo-100">
                                                <?= $p['tutor_relation_translated'] ?>
                                            </span>
                                        </p>
                                    <?php else: ?>
                                        <p class="text-slate-400 font-medium">---</p>
                                    <?php endif; ?>
                                    
                                    <p class="text-slate-400 text-xs mt-0.5"><?= htmlspecialchars($p['primary_email'] ?? '---') ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full bg-emerald-100 text-emerald-700">
                                        <?= htmlspecialchars($p['status_translated']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-2">
                                        <a href="<?= URL_BASE ?>pacientes/ver/<?= $p['patient_id'] ?>" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                            <i data-lucide="edit-3" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div id="modalNuevoPaciente" class="fixed inset-0 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    
    <div id="modalBackdrop" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col transform scale-95 transition-transform duration-300">
        
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <div>
                <h3 class="text-lg font-bold text-slate-800"><?= __('modal_new_patient_title') ?? 'Registrar Nuevo Paciente' ?></h3>
                <p class="text-xs text-slate-500"><?= __('modal_new_patient_subtitle') ?? 'Ingresa los datos generales para abrir el expediente.' ?></p>
            </div>
            <button type="button" onclick="closeModal('modalNuevoPaciente')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
            <form id="formNuevoPaciente" action="<?= URL_BASE ?>pacientes/guardar" method="POST">
                
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4"><?= __('section_personal_data') ?></h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('modal_patient_country') ?></label>
                            <select name="country_ID" id="patient_country_ID" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-white text-sm" onchange="loadIdentityTypes(this.value, 'identity_type_ID')">
                                <?php foreach($countries as $c): ?>
                                    <option value="<?= $c['ID'] ?>" <?= $c['ID'] == 1 ? 'selected' : '' ?>><?= __($c['iso_code']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_identity_type') ?></label>
                            <select name="identity_type_ID" id="identity_type_ID" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-white text-sm">
                                <?php 
                                    // DEBUG TEMPORAL: Borra esta línea después de probar
                                    // var_dump($identityTypes); 
                                ?>
                                <?php if(!empty($identityTypes)): ?>
                                    <?php foreach($identityTypes as $type): ?>
                                        <option value="<?= $type['ID'] ?>" 
                                                data-regex="<?= htmlspecialchars($type['validation_regex'] ?? '') ?>">
                                            <?= __($type['label_key']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value=""><?= __('no_identity_types_found') ?></option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_identity_number') ?></label>
                            <div class="relative">
                                <i data-lucide="id-card" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 w-4 h-4"></i>
                                <input type="text" name="identity_number" id="identity_number" placeholder="<?= __('lbl_identity_number_help') ?>" class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_first_name') ?></label>
                        <input type="text" name="first_name" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_last_name') ?></label>
                        <input type="text" name="last_name" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_birth_date') ?></label>
                        <input type="date" name="birth_date" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_gender') ?></label>
                        <select name="gender" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                            <option value=""><?= __('select_default') ?></option>
                            <option value="Female"><?= __('gender_female') ?></option>
                            <option value="Male"><?= __('gender_male') ?></option>
                            <option value="Other"><?= __('gender_other') ?></option>
                        </select>
                    </div>
                </div>

                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4"><?= __('section_contact_location') ?? 'Contacto y Ubicación' ?></h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_phone') ?></label>
                        <input type="tel" name="primary_cellphone" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_email') ?></label>
                        <input type="email" name="primary_email" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>
                    
                    <input type="hidden" name="country_ID" value="1"> 
                    <input type="hidden" name="state_ID" value="1">
                    <input type="hidden" name="city_ID" value="1">
                </div>

                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800"><?= __('tutor_question') ?></h4>
                            <p class="text-xs text-slate-500"><?= __('tutor_help_text') ?></p>
                        </div>
                        
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="requires_tutor" id="tutorToggle" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <div id="tutorFields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 hidden overflow-hidden transition-all duration-300">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_tutor_country') ?></label>
                            <select name="tutor_identity_type_ID" id="tutor_identity_type_ID" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-white text-sm" onchange="loadIdentityTypes(this.value, 'tutor_identity_type_ID')">
                                <?php foreach($countries as $c): ?>
                                    <option value="<?= $c['ID'] ?>" <?= $c['ID'] == $clinicCountryId ? 'selected' : '' ?>>
                                        <?= __($c['iso_code']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_tutor_identity_type') ?></label>
                                <select name="tutor_identity_type_ID" class="w-full px-4 py-2 border border-slate-200 rounded-lg text-sm bg-white">
                                    <?php foreach($identityTypes as $type): ?>
                                        <option value="<?= $type['ID'] ?>"><?= __($type['label_key']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_tutor_identity') ?></label>
                                <input type="text" name="tutor_identity_number" placeholder="<?= __('lbl_tutor_identity_help') ?>" class="w-full px-4 py-2 border border-slate-200 rounded-lg outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_tutor_fname') ?></label>
                            <input type="text" name="tutor_first_name" id="tutorFirstName" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_tutor_lname') ?></label>
                            <input type="text" name="tutor_last_name" id="tutorLastName" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_tutor_relation') ?></label>
                            <select name="tutor_relationship" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                <option value="Mother"><?= __('rel_Mother') ?></option>
                                <option value="Father"><?= __('rel_Father') ?></option>
                                <option value="Partner"><?= __('rel_Partner') ?></option>
                                <option value="Grandparent"><?= __('rel_Grandparent') ?></option>
                                <option value="Legal Guardian"><?= __('rel_Legal Guardian') ?? 'Tutor Legal' ?></option>
                                <option value="Child"><?= __('rel_Child') ?></option>
                                <option value="Other"><?= __('rel_Other') ?></option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('lbl_tutor_phone') ?></label>
                            <input type="tel" name="tutor_phone" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1"><?= __('modal_new_patient_doctor') ?></label>
                    <select name="assigned_doctor_ID" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all bg-white text-sm">
                        <?php 
                        // Asumiendo que guardaste si es doctor en la sesión al hacer login
                        $isCurrentUserDoctor = $_SESSION['user']['is_doctor'] ?? false; 
                        
                        if ($isCurrentUserDoctor): 
                        ?>
                            <option value="<?= $_SESSION['user']['id'] ?>"><?= __('select_modal_me') ?></option>
                        <?php else: ?>
                            <option value=""><?= __('select_lbl_modal_doctor') ?></option>
                        <?php endif; ?>

                        <?php foreach ($doctors as $doc): ?>
                            <option value="<?= $doc['ID'] ?>">
                                Dr(a). <?= htmlspecialchars($doc['first_name'] . ' ' . $doc['last_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end space-x-3">
            <button type="button" onclick="closeModal('modalNuevoPaciente')" class="px-5 py-2 text-slate-600 hover:bg-slate-200 rounded-lg font-medium transition-colors">
                <?= __('cancel_btn') ?>
            </button>
            <button type="submit" form="formNuevoPaciente" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium shadow-sm transition-colors flex items-center">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i> <?= __('save_btn') ?>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. VARIABLES GLOBALES DE LA VISTA ---
        const urlBase = '<?= URL_BASE ?>';
        const tbody = document.querySelector('#patientsTable tbody');
        const searchInput = document.getElementById('searchPatientInput');
        let searchTimeout;

        const modalNuevo = document.getElementById('modalNuevoPaciente');
        const formNuevo = document.getElementById('formNuevoPaciente');
        const tutorToggle = document.getElementById('tutorToggle');
        const tutorFields = document.getElementById('tutorFields');
        const backdrop = document.getElementById('modalBackdrop'); // Asegúrate de tener este ID en tu HTML (Punto 1 de mi respuesta anterior)

        // --- 2. PROTECCIÓN DEL MODAL (Evitar perder datos) ---
        if (backdrop) {
            backdrop.addEventListener('click', function() {
                let isDirty = false;
                const inputs = formNuevo.querySelectorAll('input:not([type="hidden"]), select');
                for (let input of inputs) {
                    if (input.tagName === 'SELECT' && input.value === '') continue;
                    if (input.type !== 'checkbox' && input.type !== 'radio' && input.value.trim() !== '') {
                        isDirty = true;
                        break;
                    }
                }

                if (isDirty) {
                    Swal.fire({
                        title: '<?= __('modal_close_title') ?? '¿Cerrar ventana?' ?>',
                        text: "<?= __('modal_close_text') ?? 'Tienes datos sin guardar. Si cierras, se perderán.' ?>",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '<?= __('modal_close_confirm') ?? 'Sí, cerrar' ?>',
                        cancelButtonText: '<?= __('modal_close_cancel') ?? 'Continuar editando' ?>'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            closeModal('modalNuevoPaciente');
                            formNuevo.reset();
                            // Limpiamos los bordes rojos si los hubiera
                            formNuevo.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500', 'bg-red-50', 'text-red-700'));
                        }
                    });
                } else {
                    closeModal('modalNuevoPaciente');
                }
            });
        }

        // --- 3. VALIDACIÓN FRONTEND (Regex del DPI/Documentos) ---
        function validateInputRegex(selectElement, inputElement) {
            if (!selectElement || !inputElement) return true; // Si no existen, pasa

            const selectedOption = selectElement.options[selectElement.selectedIndex];
            if (!selectedOption) return true;

            const regexStr = selectedOption.getAttribute('data-regex');
            
            if (regexStr && inputElement.value.trim() !== '') {
                const regex = new RegExp(regexStr);
                if (!regex.test(inputElement.value.trim())) {
                    inputElement.classList.add('border-red-500', 'bg-red-50', 'text-red-700');
                    return false;
                }
            }
            inputElement.classList.remove('border-red-500', 'bg-red-50', 'text-red-700');
            return true;
        }

        if (formNuevo) {
            formNuevo.addEventListener('submit', function(e) {
                let isValid = true;

                // Validar Paciente
                const typePatient = document.getElementById('identity_type_ID');
                const inputPatient = document.getElementById('identity_number');
                if (!validateInputRegex(typePatient, inputPatient)) isValid = false;

                // Validar Tutor (si aplica)
                if (tutorToggle && tutorToggle.checked) {
                    const typeTutor = document.querySelector('select[name="tutor_identity_type_ID"]');
                    const inputTutor = document.querySelector('input[name="tutor_identity_number"]');
                    if (!validateInputRegex(typeTutor, inputTutor)) isValid = false;
                }

                if (!isValid) {
                    e.preventDefault(); // DETIENE EL ENVÍO AL SERVIDOR
                    Swal.fire({
                        icon: 'error',
                        title: '<?= __('error_title') ?? 'Formato Inválido' ?>',
                        text: '<?= __('msg_error_identity_format_front') ?? 'Revisa los campos marcados en rojo. El número de identidad no coincide con el formato del documento seleccionado.' ?>'
                    });
                    return;
                }

                // Limpieza final antes de enviar
                this.querySelectorAll('input[type="text"], input[type="email"]').forEach(i => i.value = i.value.trim());
            });
        }

        // --- 4. LÓGICA DEL TOGGLE DE TUTOR ---
        if (tutorToggle) {
            tutorToggle.addEventListener('change', function() {
                if (this.checked) {
                    tutorFields.classList.remove('hidden');
                    tutorFields.querySelectorAll('input').forEach(i => { if(i.name !== 'tutor_phone') i.required = true; });
                } else {
                    tutorFields.classList.add('hidden');
                    tutorFields.querySelectorAll('input').forEach(i => { 
                        i.required = false; 
                        i.value = ''; 
                        i.classList.remove('border-red-500', 'bg-red-50', 'text-red-700'); // Limpiar errores al ocultar
                    });
                }
            });
        }

        // --- 5. VALIDACIÓN DE TELÉFONOS ---
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9+\-\s()]/g, '');
            });
        });

        // --- 6. LÓGICA DE BÚSQUEDA AJAX (DEBOUNCE) ---
        if (searchInput && tbody) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.trim();
                clearTimeout(searchTimeout);
                tbody.style.opacity = '0.5';

                searchTimeout = setTimeout(() => {
                    fetch(`${urlBase}pacientes/buscar?q=${encodeURIComponent(searchTerm)}`)
                        .then(response => response.json())
                        .then(data => {
                            tbody.innerHTML = '';
                            tbody.style.opacity = '1';
                            if (data.length === 0) {
                                tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-12 text-center text-slate-400"><i data-lucide="search-x" class="w-12 h-12 mx-auto mb-3 opacity-20"></i><p><?= __('patients_empty_state') ?></p></td></tr>`;
                            } else {
                                data.forEach(p => {
                                    const initials = (p.first_name.charAt(0) + p.last_name.charAt(0)).toUpperCase();
                                    const uuidShort = p.uuid ? p.uuid.substring(0, 8).toUpperCase() : '---';
                                    const tr = document.createElement('tr');
                                    tr.className = 'hover:bg-slate-50/50 transition-colors';
                                    tr.innerHTML = `
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 text-xs">${initials}</div>
                                                <div>
                                                    <p class="font-semibold text-slate-800">${p.first_name} ${p.last_name}</p>
                                                    <p class="text-xs text-slate-400 font-mono">ID: ${uuidShort}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            ${p.primary_cellphone 
                                                ? `<p class="text-slate-600 font-medium">${p.primary_cellphone}</p>` 
                                                : (p.tutor_phone 
                                                    ? `<p class="text-slate-600 font-medium flex items-center">${p.tutor_phone} <span class="ml-2 px-1.5 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase rounded border border-indigo-100">${p.tutor_relation_translated}</span></p>`
                                                    : `<p class="text-slate-400 font-medium">---</p>`)}
                                            <p class="text-slate-400 text-xs">${p.primary_email || '---'}</p>
                                        </td>
                                        <td class="px-6 py-4"><span class="px-3 py-1 text-[10px] font-bold uppercase rounded-full bg-emerald-100 text-emerald-700">${p.status_translated}</span></td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end space-x-2">
                                                <a href="${urlBase}pacientes/ver/${p.patient_id}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"><i data-lucide="eye" class="w-5 h-5"></i></a>
                                                <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"><i data-lucide="edit-3" class="w-5 h-5"></i></button>
                                            </div>
                                        </td>`;
                                    tbody.appendChild(tr);
                                });
                            }
                            if (window.lucide) window.lucide.createIcons();
                        });
                }, 300);
            });
        }
    });

    // --- 7. CARGA DINÁMICA DE DOCUMENTOS POR PAÍS ---
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
                    opt.textContent = type.label_key; 
                    
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