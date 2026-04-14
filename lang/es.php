<?php
return [
    // Menu
    'menu_dashboard' => 'Panel de Control',
    'menu_medical_area' => 'Área Médica',
    'menu_appointments' => 'Agenda',
    'menu_patients' => 'Pacientes',
    'menu_admin_area' => 'Área Administrativa',
    'menu_quotations' => 'Cotizaciones',
    'menu_inventory' => 'Inventario',
    'menu_finance' => 'Finanzas',
    'menu_reports' => 'Reportes',
    'menu_config_area' => 'Configuraciones',
    'menu_clinic_settings' => 'Configuración Clínica',
    'menu_services' => 'Servicios',
    'menu_team' => 'Equipo y Roles',

    // Botones

    'confirm_btn' => 'Sí, cerrar sesión',
    'cancel_btn' => 'Cancelar',
    'save_btn' => 'Guardar',

    // Errores

    'msg_login_error_label' => 'El correo o la contraseña son incorrectos.',
    'msg_user_or_password_incorrect_error' => 'El correo o la contraseña son incorrectos.',
    'msg_clinic_not_found_error' => 'El código de clínica no existe.',
    'msg_error_empty_fields' => 'Los nombres, apellidos y fecha de nacimiento no pueden estar vacíos.',
    'msg_error_email' => 'El formato del correo electrónico ingresado no es válido.',
    'msg_error_database' => 'Error al registrar: Ocurrió un problema en la base de datos.',
    'msg_all_fields_required' => 'Todos los campos son obligatorios.',

    // Alertas
    'msg_patient_saved' => 'Paciente registrado exitosamente. Por favor, completa su expediente.',
    'msg_patient_exists' => 'Este paciente ya estaba registrado en tu clínica.',

    // Login
    
    // Encabezados
    'login_subtitle' => 'Ingresa tus credenciales para acceder',
    // Formularios
    'login_clinic_code_label' => 'Código de Clínica',
    'login_clinic_code_placeholder' => 'Ej: 241000',
    'login_email_label' => 'Correo Electrónico',
    'login_email_placeholder' => 'tu@correo.com',
    'login_password_label' => 'Contraseña',
    'login_btn' => 'Iniciar Sesión',
    // Enlaces
    'login_forgot_password' => 'Olvidaste tu contraseña?',

    // Logout

    // Encabezados
    'logout_title' => 'Cerrar Sesión',
    // Confirmación
    'logout_success' => 'Sesión cerrada correctamente.',
    'logout_confirm' => '¿Estás seguro de que deseas salir?',

    // Pacientes

    // Encabezados
    'patients_files_title' => 'Expedientes Médicos',
    'patients_files_subtitle' => 'Administración y gestión de expedientes clínico de los pacientes.',
    // Agregar paciente
    'patients_files_btn_add' => 'Agregar Paciente',
   // Tabla y Búsqueda
    'search_patient_placeholder' => 'Buscar por nombre, correo o teléfono...',
    'table_patient' => 'Paciente',
    'table_contact' => 'Contacto',
    'table_status' => 'Estado',
    'table_actions' => 'Acciones',
    'patients_empty_state' => 'No se encontraron pacientes registrados.',
    'status_active' => 'Activo',
    
    // Modal

    // Modal: Textos Generales
    'modal_new_patient_title' => 'Registrar Nuevo Paciente',
    'modal_new_patient_subtitle' => 'Ingresa los datos generales para abrir el expediente.',
    'section_personal_data' => 'Datos Personales',
    'section_contact_location' => 'Contacto y Ubicación',
    'modal_close_title' => '¿Desea cancelar el registro?',
    'modal_close_text' => 'Se perderán los datos ingresados.',
    // Modal: Botones
    'modal_close_confirm' => 'Sí, cancelar',
    'modal_close_cancel' => 'No, continuar',
    // Modal: Formularios
    'modal_new_patient_doctor' => 'Doctor Responsable',
    'modal_patient_country' => 'País',
    'lbl_identity_number' => 'Documento de Identidad',
    'lbl_identity_number_help' => 'Número de identidad del paciente. Ej: 1234 56789 0101',
    'lbl_identity_type' => 'Tipo de Documento',
    'lbl_first_name' => 'Nombres *',
    'lbl_last_name' => 'Apellidos *',
    'lbl_birth_date' => 'Fecha de Nacimiento *',
    'lbl_gender' => 'Género',
    'lbl_phone' => 'Teléfono Móvil',
    'lbl_email' => 'Correo Electrónico',
    'select_default' => 'Seleccionar...',
    'gender_female' => 'Femenino',
    'gender_male' => 'Masculino',
    'gender_other' => 'Otro',
    'select_modal_me' => 'Asignarme',
    'select_lbl_modal_doctor' => 'Seleccione el médico tratante...',
    // Modal: Tutor
    'tutor_question' => '¿Paciente requiere Tutor / Encargado?',
    'tutor_help_text' => 'Actívalo si el paciente es menor de edad o requiere asistencia legal.',
    'lbl_tutor_country' => 'País del Tutor',
    'lbl_tutor_identity' => 'Documento de Identidad del Tutor *',
    'lbl_tutor_identity_help' => 'Número de identidad del responsable del paciente. Ej: 1234 56789 0101',
    'lbl_tutor_identity_type' => 'Tipo de Documento del Tutor',
    'lbl_tutor_fname' => 'Nombres del Tutor *',
    'lbl_tutor_lname' => 'Apellidos del Tutor *',
    'lbl_tutor_relation' => 'Parentesco',
    'lbl_tutor_phone' => 'Teléfono del Tutor *',
    // Parentescos
    'rel_Mother' => 'Madre',
    'rel_Father' => 'Padre',
    'rel_Partner' => 'Pareja',
    'rel_Grandparent' => 'Abuelo/a',
    'rel_Legal Guardian' => 'Tutor Legal',
    'rel_Child' => 'Hijo/a (Para adulto mayor)',
    'rel_Other' => 'Otro',

    // Estados
    'status_Active' => 'Activo',
    'status_Inactive' => 'Inactivo',

    // Países e Identidad
    // Guatemala
    'GT' => 'Guatemala',
    'label_division_gt' => 'Departamento',
    'label_municipality_gt' => 'Municipio',
    'label_identity_gt' => 'CUI / DPI',
    'lbl_dpi' => 'DPI 1234 56789 1011',
    'lbl_passport' => 'Pasaporte',
    'placeholder_dpi' => 'Ej: 1234 56789 0101',
    'placeholder_passport' => 'Número de pasaporte vigente',
    // México
    'MX' => 'México',
    'label_division_mx' => 'Estado',
    'label_municipality_mx' => 'Municipio',
    'label_identity_mx' => 'CURP / RFC',
    'lbl_curp' => 'CURP (Clave Única de Registro de Población)',
    'lbl_rfc' => 'RFC (Registro Federal de Contribuyentes)',
    'placeholder_curp' => 'Ej: ABCD123456HDFRRS01',
    'placeholder_rfc' => 'Ej: ABCD123456XXX',
    // USA
    'US' => 'Estados Unidos',
    'label_division_us' => 'Estado',
    'label_municipality_us' => 'Condado',
    'label_identity_us' => 'SSN / Pasaporte',
    'lbl_ssn' => 'Número de Seguro Social (SSN)',
    'placeholder_ssn' => 'Ej: 999-99-9999',
    'label_tax_sales' => 'Sales Tax',
    // Canadá
    'CA' => 'Canadá',
    'label_division_ca' => 'Provincia',
    'label_municipality_ca' => 'Municipio',
    'label_identity_ca' => 'SIN / Pasaporte',
    'lbl_sin' => 'Social Insurance Number (SIN)',
    'placeholder_sin' => 'Ej: 999-999-999',
    'label_tax_gst' => 'GST (Tax)',
    // El Salvador
    'SV' => 'El Salvador',
    'label_division_sv' => 'Departamento',
    'label_municipality_sv' => 'Municipio',
    'label_identity_sv' => 'DUI / Pasaporte',
    'lbl_dui' => 'DUI (Documento Único de Identidad)',
    'placeholder_dui' => 'Ej: 00000000-0',
    // Honduras
    'HN' => 'Honduras',
    'label_division_hn' => 'Departamento',
    'label_municipality_hn' => 'Municipio',
    'label_identity_hn' => 'DNI / Pasaporte',
    'lbl_dni' => 'DNI (Documento Nacional de Identificación)',
    'placeholder_dni' => 'Ej: 0801-1990-12345',
    'label_tax_isv' => 'ISV',
    
    // Fiscal
    'label_tax_iva' => 'IVA',

    // No Identity Types Found
    'no_identity_types_found' => 'No se encontraron tipos de documento configurados para este país.',
];
