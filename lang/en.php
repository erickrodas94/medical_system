<?php
return [
    // Buttons

    'confirm_btn' => 'Yes, logout',
    'cancel_btn' => 'Cancel',
    'save_btn' => 'Save',

    // Errors

    'msg_login_error_label' => 'The email or password are incorrect.',
    'msg_user_or_password_incorrect_error' => 'The email or password are incorrect.',
    'msg_clinic_not_found_error' => 'The clinic code does not exist.',
    'msg_error_empty_fields' => 'Names, last names, and date of birth cannot be empty.',
    'msg_error_email' => 'The email format entered is invalid.',
    'msg_error_database' => 'Registration error: A problem occurred in the database.',
    'msg_all_fields_required' => 'All fields are required.',

    // Alerts
    'msg_patient_saved' => 'Patient registered successfully. Please complete their medical record.',
    'msg_patient_exists' => 'This patient was already registered in your clinic.',

    // Login
    
    // Titles
    'login_subtitle' => 'Enter your credentials to access',
    // Forms
    'login_clinic_code_label' => 'Clinic Code',
    'login_clinic_code_placeholder' => 'Ej: 241000',
    'login_email_label' => 'Email Address',
    'login_email_placeholder' => 'your@email.com',
    'login_password_label' => 'Password',
    'login_btn' => 'Login',
    // Links
    'login_forgot_password' => 'Forgot Password?',

    // Logout

    // Titles
    'logout_title' => 'Logout',
    // Confirmation
    'logout_success' => 'Session closed successfully.',
    'logout_confirm' => 'Are you sure you want to logout?',

    // Patients

    // Titles
    'patients_files_title' => 'Medical Files',
    'patients_files_subtitle' => 'Administration and management of clinical records of patients.',
    // Buttons
    'patients_files_btn_add' => 'Add Patient',
    // Table and Search
    'search_patient_placeholder' => 'Search by name, email or phone number...',
    'table_patient' => 'Patient',
    'table_contact' => 'Contact',
    'table_status' => 'Status',
    'table_actions' => 'Actions',
    'patients_empty_state' => 'No patients found.',
    'status_active' => 'Active',

    // Modal

    // Modal: General Texts
    'modal_new_patient_title' => 'Register New Patient',
    'modal_new_patient_subtitle' => 'Enter general information to open the medical record.',
    'section_personal_data' => 'Personal Information',
    'section_contact_location' => 'Contact and Location',
    // Modal: Forms
    'lbl_identity_number' => 'Identity Number',
    'lbl_identity_number_help' => 'Patient identity number. Ej: 1234 56789 0101',
    'lbl_identity_type' => 'Identity Type',
    'lbl_first_name' => 'First Name *',
    'lbl_last_name' => 'Last Name *',
    'lbl_birth_date' => 'Date of Birth *',
    'lbl_gender' => 'Gender',
    'lbl_phone' => 'Phone Number',
    'lbl_email' => 'Email Address',
    'select_default' => 'Select...',
    'gender_female' => 'Female',
    'gender_male' => 'Male',
    'gender_other' => 'Other',
    // Modal: Tutor
    'tutor_question' => 'Does the patient require a Tutor / Guardian?',
    'tutor_help_text' => 'Enable this if the patient is a minor or requires legal assistance.',
    'lbl_tutor_identity' => 'Tutor Identity Number *',
    'lbl_tutor_identity_help' => 'Tutor identity number. Ej: 1234 56789 0101',
    'lbl_tutor_identity_type' => 'Tutor Identity Type',
    'lbl_tutor_fname' => 'Tutor First Name *',
    'lbl_tutor_lname' => 'Tutor Last Name *',
    'lbl_tutor_relation' => 'Relationship',
    'lbl_tutor_phone' => 'Tutor Phone Number *',
    // Relationships
    'rel_mother' => 'Mother',
    'rel_father' => 'Father',
    'rel_partner' => 'Partner',
    'rel_grandparent' => 'Grandparent',
    'rel_guardian' => 'Legal Guardian',
    'rel_child' => 'Child (For elderly patients)',
    'rel_other' => 'Other',

    // Countries & Identity
    // Guatemala
    'GT' => 'Guatemala',
    'label_division_gt' => 'Department',
    'label_municipality_gt' => 'Municipality',
    'label_identity_gt' => 'National ID',
    'lbl_dpi' => 'DPI (National ID)',
    'lbl_passport' => 'Passport',
    'placeholder_dpi' => 'Ex: 1234 56789 0101',
    'placeholder_passport' => 'Valid passport number',
    // Mexico
    'MX' => 'Mexico',
    'label_division_mx' => 'State',
    'label_municipality_mx' => 'Municipality',
    'label_identity_mx' => 'CURP / RFC',
    'lbl_curp' => 'CURP (Unique Population Registry Key)',
    'lbl_rfc' => 'RFC (Taxpayer ID)',
    'placeholder_curp' => 'Ex: ABCD123456HDFRRS01',
    'placeholder_rfc' => 'Ex: ABCD123456XXX',
    // USA
    'US' => 'United States',
    'label_division_us' => 'State',
    'label_municipality_us' => 'County',
    'label_identity_us' => 'SSN / Passport',
    'lbl_ssn' => 'Social Security Number (SSN)',
    'placeholder_ssn' => 'Ex: 999-99-9999',
    'label_tax_sales' => 'Sales Tax',
    // Canada
    'CA' => 'Canada',
    'label_division_ca' => 'Province',
    'label_municipality_ca' => 'Municipality',
    'label_identity_ca' => 'SIN / Passport',
    'lbl_sin' => 'Social Insurance Number (SIN)',
    'placeholder_sin' => 'Ex: 999-999-999',
    'label_tax_gst' => 'GST',
    // El Salvador
    'SV' => 'El Salvador',
    'label_division_sv' => 'Department',
    'label_municipality_sv' => 'Municipality',
    'label_identity_sv' => 'DUI / Passport',
    'lbl_dui' => 'DUI (National ID)',
    'placeholder_dui' => 'Ex: 00000000-0',
    // Honduras
    'HN' => 'Honduras',
    'label_division_hn' => 'Department',
    'label_municipality_hn' => 'Municipality',
    'label_identity_hn' => 'DNI / Passport',
    'lbl_dni' => 'DNI (National ID)',
    'placeholder_dni' => 'Ex: 0801-1990-12345',
    'label_tax_isv' => 'Sales Tax (ISV)',
    
    // Fiscal
    'label_tax_iva' => 'VAT',

    // No Identity Types Found
    'no_identity_types_found' => 'No identity types configured for this country.',
];