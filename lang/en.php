<?php
return [
    // ==========================================
    // GLOBALS & MAIN MENU
    // ==========================================
    'menu_dashboard' => 'Dashboard',
    'menu_medical_area' => 'Medical Area',
    'menu_appointments' => 'Appointments',
    'menu_patients' => 'Patients',
    'menu_cloud' => 'Cloud',
    'menu_admin_area' => 'Administrative Area',
    'menu_quotations' => 'Quotations',
    'menu_inventory' => 'Inventory',
    'menu_finance' => 'Finance',
    'menu_reports' => 'Reports',
    'menu_config_area' => 'Configurations',
    'menu_clinic_settings' => 'Clinic Settings',
    'menu_services' => 'Services',
    'menu_team' => 'Team and Roles',

    // Generic Buttons
    'confirm_logout_btn' => 'Yes, logout',
    'cancel_btn' => 'Cancel',
    'save_btn' => 'Save',
    'edit_data' => 'Edit Data',

    // Statuses
    'status_active' => 'Active',
    'status_Active' => 'Active',
    'status_Inactive' => 'Inactive',

    // ==========================================
    // MESSAGES & ALERTS
    // ==========================================
    'msg_error_access_denied' => 'Access denied or the file does not exist.',
    'msg_login_error_label' => 'The email or password are incorrect.',
    'msg_user_or_password_incorrect_error' => 'The email or password are incorrect.',
    'msg_clinic_not_found_error' => 'The clinic code does not exist.',
    'msg_error_empty_fields' => 'Names, last names, and date of birth cannot be empty.',
    'msg_error_email' => 'The email format entered is invalid.',
    'msg_error_database' => 'Registration error: A problem occurred in the database.',
    'msg_all_fields_required' => 'All fields are required.',
    'msg_error_saving_evolution' => 'Error saving the evolution.',
    'msg_error_saving_transaction' => 'Error: A problem occurred while saving the transaction.',
    'msg_error_action_failed' => 'Error: The action could not be completed.',
    'msg_patient_saved' => 'Patient registered successfully. Please complete their medical record.',
    'msg_patient_exists' => 'This patient was already registered in your clinic.',
    'msg_evolution_saved' => 'Evolution saved successfully.',

    // ==========================================
    // AUTHENTICATION (LOGIN / LOGOUT)
    // ==========================================
    'login_subtitle' => 'Enter your credentials to access',
    'login_clinic_code_label' => 'Clinic Code',
    'login_clinic_code_placeholder' => 'Ex: 241000',
    'login_email_label' => 'Email Address',
    'login_email_placeholder' => 'your@email.com',
    'login_password_label' => 'Password',
    'login_btn' => 'Login',
    'login_forgot_password' => 'Forgot Password?',
    'logout_title' => 'Logout',
    'logout_success' => 'Session closed successfully.',
    'logout_confirm' => 'Are you sure you want to logout?',

    // ==========================================
    // PATIENTS MODULE (LIST & PROFILE)
    // ==========================================
    'patients_files_title' => 'Medical Files',
    'patients_files_subtitle' => 'Administration and management of clinical records of patients.',
    'patients_files_btn_add' => 'Add Patient',
    'search_patient_placeholder' => 'Search by name, email or phone number...',
    'table_patient' => 'Patient',
    'table_contact' => 'Contact',
    'table_status' => 'Status',
    'table_actions' => 'Actions',
    'patients_empty_state' => 'No patients found.',
    
    // Profile
    'patient' => 'Patient',
    'patient_name' => 'Patient Name',
    'patient_data' => 'Patient Data',
    'patient_contact' => 'Patient Contact',
    'patient_info' => 'Patient Information',
    'patient_critical' => 'Critical Information',
    'patient_profile' => 'Patient Profile',
    'patient_background' => 'Patient Background',
    'not_registered' => 'Not registered',
    'blood_type' => 'Blood Type',
    'emergency_contact' => 'Emergency Contact',
    'critical_medical_alert' => 'Critical Medical Alert',

    // ==========================================
    // CASES & CLINICAL CONSULTATION
    // ==========================================
    'active_case' => 'Active Clinical Case',
    'no_active_case' => 'You must select or open an active clinical case.',
    'clinical_data' => 'Clinical Data',
    'no_cases_registered' => 'No cases registered',
    'new_clinical_case' => 'New Clinical Case',
    'no_cases_registered_text' => 'This patient does not have any health problems registered yet. To start a medical consultation, you must first open a case.',
    'btn_new_case' => 'New Case',
    'consultation_reason' => 'Consultation Reason',
    'initial_diagnosis' => 'Initial Diagnosis',
    'opened_at' => 'Opened at',
    'doctor' => 'Doctor',

    // Unified Consultation Flow
    'new_consultation' => 'New Complete Consultation',
    'finalize_consultation' => 'Finalize Consultation',
    'save_as_draft' => 'Save as Draft',
    'Draft' => 'Draft',
    'Finalized' => 'Finalized',
    
    // Block 1: Triage
    'vital_signs' => 'Vital Signs',
    'vital_signs_history' => 'Vital Signs History',
    'btn_new_triage' => 'New Triage',
    'blood_pressure' => 'Blood Pressure',
    'weight' => 'Weight',
    'height' => 'Height',
    'temperature' => 'Temperature',
    'kg' => 'kg', 'lb' => 'lb', 'cm' => 'cm', 'm' => 'm', 'in' => 'in', 'ft' => 'ft', 'C' => '°C', 'F' => '°F',

    // Block 2: SOAP Evolution
    'evolution_soap' => 'SOAP Evolution',
    'evolutions' => 'Evolutions',
    'lbl_evolution' => 'Evolution Notes (SOAP)',
    'no_evolutions_registered' => 'No evolutions registered',
    'no_evolutions_registered_help' => 'Press "New Evolution" to write the first medical note of this case.',
    'new_evolution_note' => 'New Evolution',
    'evolution_notes' => 'Evolution Notes',
    'evolution_notes_placeholder' => 'Information regarding the patient\'s evolution',
    'evolution_notes_help' => 'Include the chief complaint, patient symptoms (Subjective), and your clinical assessment (Analysis) here.',
    'physical_exam_notes' => 'Physical Exam Notes',
    'physical_exam_placeholder' => 'Physical Exam Findings',

    // Block 3: Prescriptions & Plan
    'prescriptions_plans' => 'Prescriptions and Plans',
    'btn_new_prescription' => 'New Prescription',
    'no_prescriptions_registered' => 'No prescriptions registered.',
    'patient_instructions' => 'Patient Instructions',
    'patient_instructions_placeholder' => 'Rest, diet, general care...',
    'prescribed_medications' => 'Prescribed Medications',
    'medications' => 'Medications',
    'medications_placeholder' => 'Medication name',
    'dosage' => 'Dosage',
    'dosage_placeholder' => 'Ex. 1 tablet',
    'frequency' => 'Frequency',
    'frequency_placeholder' => 'Ex. Every 8 hours',
    'duration' => 'Duration',
    'duration_placeholder' => 'Ex. For 5 days',
    'total_quantity' => 'Total Quantity',
    'total_quantity_placeholder' => 'Ex. 1 box / 21 pills',
    'additional_notes' => 'Additional Notes',
    'additional_notes_placeholder' => 'Ex. Take with a full stomach',
    'prescription_only_evolution' => 'Medical prescription issuance',
    'msg_prescription_saved' => 'Prescription issued and saved successfully.',
    'msg_triage_saved' => 'Triage registered correctly.',
    'msg_case_saved' => 'Clinical case opened successfully.',

    // Block 4: Administration & Billing
    'billing_and_admin' => 'Billing and Administration',
    'assistance_type' => 'Assistance Type',
    'In-Person' => 'In Person',
    'Video' => 'Video Consultation',
    'Phone' => 'Phone Consultation',
    'Chat' => 'Chat',
    'consultation_fee' => 'Consultation Fee',
    'consultation_fee_help' => 'This amount will generate an automatic charge on the patient\'s account.',

    // ==========================================
    // MODALS (Patient Registration)
    // ==========================================
    'modal_new_patient_title' => 'Register New Patient',
    'modal_new_patient_subtitle' => 'Enter general information to open the medical record.',
    'section_personal_data' => 'Personal Information',
    'section_contact_location' => 'Contact and Location',
    'modal_close_title' => 'Do you want to cancel the registration?',
    'modal_close_text' => 'The data entered will be lost.',
    'modal_close_confirm' => 'Yes, cancel',
    'modal_close_cancel' => 'No, continue',
    
    // Forms
    'modal_new_patient_doctor' => 'Attending Doctor',
    'modal_patient_country' => 'Country',
    'lbl_identity_number' => 'Identity Number',
    'lbl_identity_number_help' => 'Patient identity number. Ex: 1234 56789 0101',
    'lbl_identity_type' => 'Identity Type',
    'lbl_first_name' => 'First Name <span class="text-rose-500 ml-1">*</span>',
    'lbl_last_name' => 'Last Name <span class="text-rose-500 ml-1">*</span>',
    'lbl_birth_date' => 'Date of Birth <span class="text-rose-500 ml-1">*</span>',
    'lbl_gender' => 'Gender',
    'lbl_phone' => 'Phone Number',
    'lbl_email' => 'Email Address',
    'lbl_address' => 'Address',
    'select_default' => 'Select...',
    'gender_female' => 'Female',
    'gender_male' => 'Male',
    'gender_other' => 'Other',
    'select_modal_me' => 'Assign me',
    'select_lbl_modal_doctor' => 'Select the attending physician...',
    
    // Tutor
    'tutor_question' => 'Does the patient require a Tutor / Guardian?',
    'tutor_help_text' => 'Enable this if the patient is a minor or requires legal assistance.',
    'lbl_tutor_country' => 'Tutor Country',
    'lbl_tutor_identity' => 'Tutor Identity Number <span class="text-rose-500 ml-1">*</span>',
    'lbl_tutor_identity_help' => 'Tutor identity number. Ex: 1234 56789 0101',
    'lbl_tutor_identity_type' => 'Tutor Identity Type',
    'lbl_tutor_fname' => 'Tutor First Name <span class="text-rose-500 ml-1">*</span>',
    'lbl_tutor_lname' => 'Tutor Last Name <span class="text-rose-500 ml-1">*</span>',
    'lbl_tutor_relation' => 'Relationship',
    'lbl_tutor_phone' => 'Tutor Phone Number <span class="text-rose-500 ml-1">*</span>',
    
    // Relationships
    'rel_Mother' => 'Mother',
    'rel_Father' => 'Father',
    'rel_Partner' => 'Partner',
    'rel_Grandparent' => 'Grandparent',
    'rel_Legal Guardian' => 'Legal Guardian',
    'rel_Child' => 'Child (For elderly patients)',
    'rel_Other' => 'Other',

    // ==========================================
    // COUNTRIES, IDENTITY & FISCAL
    // ==========================================
    'GT' => 'Guatemala',
    'label_division_gt' => 'Department',
    'label_municipality_gt' => 'Municipality',
    'label_identity_gt' => 'DPI',
    'lbl_dpi' => 'DPI',
    'lbl_passport' => 'Passport',
    'placeholder_dpi' => 'Ex: 1234 56789 0101',
    'placeholder_passport' => 'Valid passport number',
    
    'MX' => 'Mexico',
    'label_division_mx' => 'State',
    'label_municipality_mx' => 'Municipality',
    'label_identity_mx' => 'CURP / RFC',
    'lbl_curp' => 'CURP',
    'lbl_rfc' => 'RFC',
    'placeholder_curp' => 'Ex: ABCD123456HDFRRS01',
    'placeholder_rfc' => 'Ex: ABCD123456XXX',
    
    'US' => 'United States',
    'label_division_us' => 'State',
    'label_municipality_us' => 'County',
    'label_identity_us' => 'SSN / Passport',
    'lbl_ssn' => 'Social Security Number (SSN)',
    'placeholder_ssn' => 'Ex: 999-99-9999',
    'label_tax_sales' => 'Sales Tax',
    
    'CA' => 'Canada',
    'label_division_ca' => 'Province',
    'label_municipality_ca' => 'Municipality',
    'label_identity_ca' => 'SIN / Passport',
    'lbl_sin' => 'Social Insurance Number (SIN)',
    'placeholder_sin' => 'Ex: 999-999-999',
    'label_tax_gst' => 'GST',
    
    'SV' => 'El Salvador',
    'label_division_sv' => 'Department',
    'label_municipality_sv' => 'Municipality',
    'label_identity_sv' => 'DUI / Passport',
    'lbl_dui' => 'DUI',
    'placeholder_dui' => 'Ex: 00000000-0',
    
    'HN' => 'Honduras',
    'label_division_hn' => 'Department',
    'label_municipality_hn' => 'Municipality',
    'label_identity_hn' => 'DNI / Passport',
    'lbl_dni' => 'DNI',
    'placeholder_dni' => 'Ex: 0801-1990-12345',
    'label_tax_isv' => 'Sales Tax (ISV)',
    
    'label_tax_iva' => 'VAT',
    'no_identity_types_found' => 'No identity types configured for this country.',

    // ==========================================
    // SYSTEM (404)
    // ==========================================
    '404_title' => 'Page not found...',
    '404_description' => 'It seems the page you are looking for has been misplaced in our digital archive. Don\'t worry, the rest of the system is still operating normally.',
    '404_btn_dashboard' => 'Return to Dashboard',
    '404_btn_back' => 'Try going back',
];