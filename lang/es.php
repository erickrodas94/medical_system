<?php
return [
    // ==========================================
    // GLOBALES Y MENÚ PRINCIPAL
    // ==========================================
    'menu_dashboard' => 'Panel de Control',
    'menu_medical_area' => 'Área Médica',
    'menu_appointments' => 'Agenda',
    'menu_patients' => 'Pacientes',
    'menu_cloud' => 'Nube',
    'menu_admin_area' => 'Área Administrativa',
    'menu_quotations' => 'Cotizaciones',
    'menu_inventory' => 'Inventario',
    'menu_finance' => 'Finanzas',
    'menu_reports' => 'Reportes',
    'menu_config_area' => 'Configuraciones',
    'menu_clinic_settings' => 'Configuración Clínica',
    'menu_services' => 'Servicios',
    'menu_team' => 'Equipo y Roles',

    // Botones Genéricos
    'confirm_logout_btn' => 'Sí, cerrar sesión',
    'cancel_btn' => 'Cancelar',
    'save_btn' => 'Guardar',
    'edit_data' => 'Editar Datos',

    // Estados
    'status_active' => 'Activo',
    'status_Active' => 'Activo',
    'status_Inactive' => 'Inactivo',

    // ==========================================
    // MENSAJES Y ALERTAS
    // ==========================================
    'msg_error_access_denied' => 'Acceso denegado o el expediente no existe.',
    'msg_login_error_label' => 'El correo o la contraseña son incorrectos.',
    'msg_user_or_password_incorrect_error' => 'El correo o la contraseña son incorrectos.',
    'msg_clinic_not_found_error' => 'El código de clínica no existe.',
    'msg_error_empty_fields' => 'Los nombres, apellidos y fecha de nacimiento no pueden estar vacíos.',
    'msg_error_email' => 'El formato del correo electrónico ingresado no es válido.',
    'msg_error_database' => 'Error al registrar: Ocurrió un problema en la base de datos.',
    'msg_all_fields_required' => 'Todos los campos son obligatorios.',
    'msg_error_saving_evolution' => 'Error al guardar la evolución.',
    'msg_error_saving_transaction' => 'Error al guardar la transacción.',
    'msg_error_action_failed' => 'Error: La acción no se pudo completar.',
    'msg_patient_saved' => 'Paciente registrado exitosamente. Por favor, completa su expediente.',
    'msg_patient_exists' => 'Este paciente ya estaba registrado en tu clínica.',
    'msg_evolution_saved' => 'Evolución guardada exitosamente.',

    // ==========================================
    // AUTENTICACIÓN (LOGIN / LOGOUT)
    // ==========================================
    'login_subtitle' => 'Ingresa tus credenciales para acceder',
    'login_clinic_code_label' => 'Código de Clínica',
    'login_clinic_code_placeholder' => 'Ej: 241000',
    'login_email_label' => 'Correo Electrónico',
    'login_email_placeholder' => 'tu@correo.com',
    'login_password_label' => 'Contraseña',
    'login_btn' => 'Iniciar Sesión',
    'login_forgot_password' => 'Olvidaste tu contraseña?',
    'logout_title' => 'Cerrar Sesión',
    'logout_success' => 'Sesión cerrada correctamente.',
    'logout_confirm' => '¿Estás seguro de que deseas salir?',

    // ==========================================
    // MÓDULO DE PACIENTES (LISTADO Y PERFIL)
    // ==========================================
    'patients_files_title' => 'Expedientes Médicos',
    'patients_files_subtitle' => 'Administración y gestión de expedientes clínico de los pacientes.',
    'patients_files_btn_add' => 'Agregar Paciente',
    'search_patient_placeholder' => 'Buscar por nombre, correo o teléfono...',
    'table_patient' => 'Paciente',
    'table_contact' => 'Contacto',
    'table_status' => 'Estado',
    'table_actions' => 'Acciones',
    'patients_empty_state' => 'No se encontraron pacientes registrados.',
    
    // Perfil
    'patient' => 'Paciente',
    'patient_name' => 'Nombre del Paciente',
    'patient_data' => 'Datos del paciente',
    'patient_contact' => 'Contacto del paciente',
    'patient_info' => 'Información del paciente',
    'patient_critical' => 'Información Crítica',
    'patient_profile' => 'Perfil del Paciente',
    'patient_background' => 'Antecedentes del Paciente',
    'not_registered' => 'No registrado',
    'blood_type' => 'Tipo de Sangre',
    'emergency_contact' => 'Contacto de Emergencia',
    'critical_medical_alert' => 'Alerta Médica Permanente',

    // ==========================================
    // CASOS Y CONSULTA CLÍNICA (EXPEDIENTE)
    // ==========================================
    'active_case' => 'Caso Clínico Activo',
    'no_active_case' => 'Debe seleccionar o aperturar un caso clínico activo.',
    'clinical_data' => 'Datos Clínicos del Caso',
    'no_cases_registered' => 'No se encontraron casos registrados',
    'new_clinical_case' => 'Expediente Clínico Nuevo',
    'no_cases_registered_text' => 'Este paciente aún no tiene ningún problema de salud registrado. Para iniciar una consulta, primero debes aperturar un caso.',
    'btn_new_case' => 'Nuevo Caso',
    'consultation_reason' => 'Motivo de Consulta',
    'initial_diagnosis' => 'Diagnóstico Inicial',
    'opened_at' => 'Aperturado',
    'doctor' => 'Doctor(a)',

    // Flujo de Consulta Unificada
    'new_consultation' => 'Nueva Consulta Completa',
    'finalize_consultation' => 'Finalizar Consulta',
    'save_as_draft' => 'Guardar como Borrador',
    'Draft' => 'Borrador',
    'Finalized' => 'Finalizado',
    
    // Bloque 1: Triaje
    'vital_signs' => 'Signos Vitales',
    'vital_signs_history' => 'Historial de Signos Vitales',
    'btn_new_triage' => 'Nuevo Triaje',
    'blood_pressure' => 'Presión Arterial',
    'weight' => 'Peso',
    'height' => 'Estatura',
    'temperature' => 'Temperatura',
    'kg' => 'kg', 'lb' => 'lb', 'cm' => 'cm', 'm' => 'm', 'in' => 'in', 'ft' => 'ft', 'C' => '°C', 'F' => '°F',

    // Bloque 2: Evolución SOAP
    'evolution_soap' => 'Evolución SOAP',
    'evolutions' => 'Evoluciones',
    'lbl_evolution' => 'Evolución Médica (SOAP)',
    'no_evolutions_registered' => 'Aún no hay evoluciones registradas',
    'no_evolutions_registered_help' => 'Presiona "Nueva Evolución" para redactar la primera nota médica de este caso.',
    'new_evolution_note' => 'Nueva Evolución',
    'evolution_notes' => 'Notas de Evolución',
    'evolution_notes_placeholder' => 'Información sobre la evolución del paciente',
    'evolution_notes_help' => 'Incluya aquí el motivo de consulta, los síntomas (Subjetivo) y su diagnóstico (Análisis).',
    'physical_exam_notes' => 'Notas de Examen Físico',
    'physical_exam_placeholder' => 'Hallazgos del examen físico',

    // Bloque 3: Recetas y Plan
    'prescriptions_plans' => 'Recetas Médicas y Plan',
    'btn_new_prescription' => 'Nueva Receta',
    'no_prescriptions_registered' => 'No se encontraron recetas emitidas en este caso.',
    'patient_instructions' => 'Instrucciones para el Paciente',
    'patient_instructions_placeholder' => 'Reposo, dieta, cuidados generales...',
    'prescribed_medications' => 'Medicamentos Recetados',
    'medications' => 'Medicamentos',
    'medications_placeholder' => 'Nombre del medicamento',
    'dosage' => 'Dosis',
    'dosage_placeholder' => 'Ej. 1 tableta',
    'frequency' => 'Frecuencia',
    'frequency_placeholder' => 'Ej. Cada 8 horas',
    'duration' => 'Duración',
    'duration_placeholder' => 'Ej. Por 5 días',
    'total_quantity' => 'Cantidad Total',
    'total_quantity_placeholder' => 'Ej. 1 caja / 21 pastillas',
    'additional_notes' => 'Notas Adicionales',
    'additional_notes_placeholder' => 'Ej. Tomar con estómago lleno',
    'prescription_only_evolution' => 'Medical prescription issuance',
    'msg_prescription_saved' => 'Prescription issued and saved successfully.',
    'msg_triage_saved' => 'Triage registered correctly.',
    'msg_case_saved' => 'Clinical case opened successfully.',

    // Bloque 4: Administración y Cobro
    'billing_and_admin' => 'Cobro y Administración',
    'assistance_type' => 'Tipo de Asistencia',
    'In-Person' => 'Presencial',
    'Video' => 'Video Consulta',
    'Phone' => 'Consulta Telefónica',
    'Chat' => 'Chat',
    'consultation_fee' => 'Honorarios de Consulta',
    'consultation_fee_help' => 'Este monto generará un cargo automático en la cuenta del paciente.',

    // ==========================================
    // MODALES (Registro de Pacientes)
    // ==========================================
    'modal_new_patient_title' => 'Registrar Nuevo Paciente',
    'modal_new_patient_subtitle' => 'Ingresa los datos generales para abrir el expediente.',
    'section_personal_data' => 'Datos Personales',
    'section_contact_location' => 'Contacto y Ubicación',
    'modal_close_title' => '¿Desea cancelar el registro?',
    'modal_close_text' => 'Se perderán los datos ingresados.',
    'modal_close_confirm' => 'Sí, cancelar',
    'modal_close_cancel' => 'No, continuar',
    
    // Formularios
    'modal_new_patient_doctor' => 'Doctor Responsable',
    'modal_patient_country' => 'País',
    'lbl_identity_number' => 'Documento de Identidad',
    'lbl_identity_number_help' => 'Número de identidad del paciente. Ej: 1234 56789 0101',
    'lbl_identity_type' => 'Tipo de Documento',
    'lbl_first_name' => 'Nombres <span class="text-rose-500 ml-1">*</span>',
    'lbl_last_name' => 'Apellidos <span class="text-rose-500 ml-1">*</span>',
    'lbl_birth_date' => 'Fecha de Nacimiento <span class="text-rose-500 ml-1">*</span>',
    'lbl_gender' => 'Género',
    'lbl_phone' => 'Teléfono Móvil',
    'lbl_email' => 'Correo Electrónico',
    'lbl_address' => 'Dirección',
    'select_default' => 'Seleccionar...',
    'gender_female' => 'Femenino',
    'gender_male' => 'Masculino',
    'gender_other' => 'Otro',
    'select_modal_me' => 'Asignarme',
    'select_lbl_modal_doctor' => 'Seleccione el médico tratante...',
    
    // Tutor
    'tutor_question' => '¿Paciente requiere Tutor / Encargado?',
    'tutor_help_text' => 'Actívalo si el paciente es menor de edad o requiere asistencia legal.',
    'lbl_tutor_country' => 'País del Tutor',
    'lbl_tutor_identity' => 'Documento de Identidad del Tutor <span class="text-rose-500 ml-1">*</span>',
    'lbl_tutor_identity_help' => 'Número de identidad del responsable. Ej: 1234 56789 0101',
    'lbl_tutor_identity_type' => 'Tipo de Documento del Tutor',
    'lbl_tutor_fname' => 'Nombres del Tutor <span class="text-rose-500 ml-1">*</span>',
    'lbl_tutor_lname' => 'Apellidos del Tutor <span class="text-rose-500 ml-1">*</span>',
    'lbl_tutor_relation' => 'Parentesco',
    'lbl_tutor_phone' => 'Teléfono del Tutor <span class="text-rose-500 ml-1">*</span>',
    
    // Parentescos
    'rel_Mother' => 'Madre',
    'rel_Father' => 'Padre',
    'rel_Partner' => 'Pareja',
    'rel_Grandparent' => 'Abuelo/a',
    'rel_Legal Guardian' => 'Tutor Legal',
    'rel_Child' => 'Hijo/a (Para adulto mayor)',
    'rel_Other' => 'Otro',

    // ==========================================
    // PAÍSES, IDENTIDADES Y FISCALIDAD
    // ==========================================
    'GT' => 'Guatemala',
    'label_division_gt' => 'Departamento',
    'label_municipality_gt' => 'Municipio',
    'label_identity_gt' => 'CUI / DPI',
    'lbl_dpi' => 'DPI',
    'lbl_passport' => 'Pasaporte',
    'placeholder_dpi' => 'Ej: 1234 56789 0101',
    'placeholder_passport' => 'Número de pasaporte vigente',
    
    'MX' => 'México',
    'label_division_mx' => 'Estado',
    'label_municipality_mx' => 'Municipio',
    'label_identity_mx' => 'CURP / RFC',
    'lbl_curp' => 'CURP',
    'lbl_rfc' => 'RFC',
    'placeholder_curp' => 'Ej: ABCD123456HDFRRS01',
    'placeholder_rfc' => 'Ej: ABCD123456XXX',
    
    'US' => 'Estados Unidos',
    'label_division_us' => 'Estado',
    'label_municipality_us' => 'Condado',
    'label_identity_us' => 'SSN / Pasaporte',
    'lbl_ssn' => 'Social Security Number (SSN)',
    'placeholder_ssn' => 'Ej: 999-99-9999',
    'label_tax_sales' => 'Sales Tax',
    
    'CA' => 'Canadá',
    'label_division_ca' => 'Provincia',
    'label_municipality_ca' => 'Municipio',
    'label_identity_ca' => 'SIN / Pasaporte',
    'lbl_sin' => 'Social Insurance Number (SIN)',
    'placeholder_sin' => 'Ej: 999-999-999',
    'label_tax_gst' => 'GST (Tax)',
    
    'SV' => 'El Salvador',
    'label_division_sv' => 'Departamento',
    'label_municipality_sv' => 'Municipio',
    'label_identity_sv' => 'DUI / Pasaporte',
    'lbl_dui' => 'DUI',
    'placeholder_dui' => 'Ej: 00000000-0',
    
    'HN' => 'Honduras',
    'label_division_hn' => 'Departamento',
    'label_municipality_hn' => 'Municipio',
    'label_identity_hn' => 'DNI / Pasaporte',
    'lbl_dni' => 'DNI',
    'placeholder_dni' => 'Ej: 0801-1990-12345',
    'label_tax_isv' => 'ISV',
    
    'label_tax_iva' => 'IVA',
    'no_identity_types_found' => 'No se encontraron tipos de documento configurados para este país.',

    // ==========================================
    // SISTEMA (404)
    // ==========================================
    '404_title' => 'Página no encontrada...',
    '404_description' => 'Parece que la página que buscas se ha traspapelado en nuestro archivo digital. No te preocupes, el resto del sistema sigue operando con normalidad.',
    '404_btn_dashboard' => 'Regresar al Dashboard',
    '404_btn_back' => 'Intentar volver atrás',
];