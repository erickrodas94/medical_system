<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\IdentityType;
use App\Models\Country;
use App\Models\ClinicCase;
use App\Models\Evolution;
use App\Models\Triage;
use App\Models\Background;
use App\Models\Transaction;
use App\Models\Prescription;
use App\Models\State;
use App\Models\PatientVital;

class PatientController {
    
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // ==========================================
    // 1. MÓDULO DE LECTURA Y BÚSQUEDA
    // ==========================================

    public function index() {
        if (!isset($_SESSION['user']['id'])) { header('Location: ' . URL_BASE . 'login'); exit; }

        $userId = $_SESSION['user']['id'];
        $clinicId = $_SESSION['clinic']['id'];
        $clinicCountryId = $_SESSION['clinic']['country_id'] ?? 1;

        $permissions = $_SESSION['user']['permissions'] ?? []; 
        $canSeeAll = isset($permissions['all_access']) || isset($permissions['all_patients']);

        $userModel = new User($this->db);
        $patientModel = new Patient($this->db);
        $identityTypeModel = new IdentityType($this->db);
        $countryModel = new Country($this->db);

        $doctors = $userModel->getDoctorsByClinic($clinicId);
        $identityTypes = $identityTypeModel->getByCountry($clinicCountryId);
        $countries = $countryModel->getAll();
        
        $patients = $patientModel->getPatientsForUser($clinicId, $userId, $canSeeAll);
        
        foreach ($patients as &$p) {
            $p['status_translated'] = __('status_' . $p['patient_status']);
            $p['tutor_relation_translated'] = !empty($p['tutor_relation']) ? __('rel_' . $p['tutor_relation']) : '';
        }
        unset($p);

        require_once '../views/patients/index.php';
    }

    public function search() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        $term = $_GET['q'] ?? '';
        $clinicId = $_SESSION['clinic']['id'];
        $patientModel = new Patient($this->db);

        if (trim($term) === '') {
            $patients = $patientModel->getLatestByClinic($clinicId, 50);
        } else {
            $patients = $patientModel->searchPatients($clinicId, trim($term), 50);
        }

        foreach ($patients as &$p) {
            $p['status_translated'] = __('status_' . $p['patient_status']);
            $p['tutor_relation_translated'] = !empty($p['tutor_relation']) ? __('rel_' . $p['tutor_relation']) : '';
        }

        echo json_encode($patients);
    }

    public function show($id) {
        if (!isset($_SESSION['user']['id'])) { header('Location: ' . URL_BASE . 'login'); exit; }

        $userId = $_SESSION['user']['id'];
        $clinicId = $_SESSION['clinic']['id'];
        
        $permissions = $_SESSION['user']['permissions'] ?? [];
        $canSeeAll = isset($permissions['all_access']) || isset($permissions['all_patients']);

        $patientModel = new Patient($this->db);
        $caseModel = new ClinicCase($this->db);
        $evolutionModel = new Evolution($this->db);
        $bgModel = new Background($this->db);
        $triageModel = new Triage($this->db);

        $patient = $patientModel->getByIdAndUser($id, $clinicId, $userId, $canSeeAll);

        if (!$patient) {
            $_SESSION['message'] = ['type' => 'error', 'message' => __('msg_error_access_denied')];
            header('Location: ' . URL_BASE . 'pacientes');
            exit;
        }

        $cases = $caseModel->getByPerson($patient['ID'], $clinicId);
        $activeCaseId = isset($_GET['case_id']) ? $_GET['case_id'] : (!empty($cases) ? $cases[0]['ID'] : null);
        $backgroundData = $bgModel->getPatientBackground($patient['ID'], $userId);

        // --- CAMBIO AQUÍ: OBTENEMOS LA LÍNEA DE TIEMPO UNIFICADA EN VEZ DE SOLO EVOLUCIONES ---
        $timeline = [];
        $doctors = [];
        if ($activeCaseId) {
            // Llamamos a la función unificada pasando el caso activo
            $timeline = $patientModel->getUnifiedTimeline($patient['ID'], $activeCaseId);
            
            // Extraemos los doctores que han participado para el filtro
            foreach ($timeline as $item) {
                $doctors[$item['doctor_id']] = $item['doctor_full_name'];
            }
        }
        // -------------------------------------------------------------------------------------

        // ========================================
        // Obtener Historial de Signos Vitales
        // ========================================
        $vitalsHistory = $triageModel->getVitalsHistory($patient['ID']);
        $vitalsJson = json_encode($vitalsHistory);

        $specialty = $_SESSION['user']['specialty'] ?? '';
        $pediatricData = [];

        if ($specialty === 'specialty_pediatrics') {
            $patientModel = new Patient($this->db);
            $rawHistory = $patientModel->getPediatricGrowthHistory($id);
            
            // Normalizamos los datos a kg y cm para comparar con la OMS
            foreach ($rawHistory as $row) {
                $weight = ($row['weight_unit'] === 'lb') ? $row['weight_value'] * 0.453592 : $row['weight_value'];
                $height = ($row['height_unit'] === 'mt') ? $row['height_value'] * 100 : $row['height_value'];
                $head = ($row['head_circumference_unit'] === 'in') ? $row['head_circumference'] * 2.54 : $row['head_circumference'];
                
                $pediatricData[] = [
                    'x' => $row['age_months'],
                    'weight' => round($weight, 2),
                    'height' => round($height, 2),
                    'head'   => round($head, 2),
                    'gender' => $row['gender']
                ];
            }
        }

        $pediatricJson = json_encode($pediatricData);

        require_once '../views/patients/show.php';
    }

    // ==========================================
    // 2. MÓDULO DE EDICIÓN Y CREACIÓN DE PACIENTE
    // ==========================================

    public function store() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name'] ?? '');
        $email     = trim($_POST['primary_email'] ?? '');
        $requiresTutor = isset($_POST['requires_tutor']);
        $identityNumber = trim($_POST['identity_number'] ?? '');
        $tutorIdentity = trim($_POST['tutor_identity_number'] ?? '');
        
        if (empty($firstName) || empty($lastName) || empty($_POST['birth_date'])) {
            $_SESSION['message'] = ['type' => 'error', 'message' => __('msg_error_empty_fields')];
            header('Location: ' . URL_BASE . 'pacientes'); exit;
        }

        if (!$requiresTutor && empty($identityNumber)) {
            $_SESSION['message'] = ['type' => 'error', 'message' => __('msg_error_identity_required') ?? 'ID requerido.'];
            header('Location: ' . URL_BASE . 'pacientes'); exit;
        }

        $data = [
            'identity_type_ID'   => $_POST['identity_type_ID'] ?? null,
            'identity_number'    => $identityNumber,
            'first_name'         => $firstName,
            'last_name'          => $lastName,
            'birth_date'         => $_POST['birth_date'],
            'gender'             => $_POST['gender'] ?: null,
            'primary_cellphone'  => trim($_POST['primary_cellphone']) ?: null,
            'primary_email'      => $email ?: null,
            'country_ID'         => $_POST['country_ID'] ?? 1,
            'state_ID'           => $_POST['state_ID'] ?? 1,
            'assigned_doctor_ID' => $_POST['assigned_doctor_ID'] ?? null,
            'requires_tutor'           => isset($_POST['requires_tutor']) ? 1 : 0,
            'tutor_country_ID'         => $_POST['tutor_country_ID'] ?? $_POST['country_ID'],
            'tutor_identity_type_ID'   => $_POST['tutor_identity_type_ID'] ?? null,
            'tutor_identity_number'    => $tutorIdentity,
            'tutor_first_name'         => trim($_POST['tutor_first_name'] ?? ''),
            'tutor_last_name'          => trim($_POST['tutor_last_name'] ?? ''),
            'tutor_phone'              => trim($_POST['tutor_phone'] ?? ''),
            'tutor_relationship'       => $_POST['tutor_relationship'] ?? null
        ];

        $patientModel = new Patient($this->db);
        $result = $patientModel->create($data);

        if ($result['success']) {
            $_SESSION['message'] = [
                'type' => 'success',
                'message' => $result['is_new'] ? __('msg_patient_saved') : __('msg_patient_exists')
            ];
            header('Location: ' . URL_BASE . 'pacientes/ver/' . $result['patient_id']);
        } else {
            $_SESSION['message'] = ['type' => 'error', 'message' => $result['error']];
            header('Location: ' . URL_BASE . 'pacientes');
        }
        exit;
    }

    public function edit($id) {
        if (!isset($_SESSION['user']['id'])) { header('Location: ' . URL_BASE . 'login'); exit; }

        $userId = $_SESSION['user']['id'];
        $clinicId = $_SESSION['clinic']['id'];
        $clinicCountryId = $_SESSION['clinic']['country_id'] ?? 1;
        $permissions = $_SESSION['user']['permissions'] ?? [];
        $canSeeAll = isset($permissions['all_access']) || isset($permissions['all_patients']);
        
        if (!$canSeeAll && $_SESSION['user']['role_id'] != 1) {
            $_SESSION['message'] = ['type' => 'error', 'message' => __('msg_error_permission_denied') ?? 'Acceso denegado.'];
            header('Location: ' . URL_BASE . 'pacientes/ver/' . $id); exit;
        }

        $patientModel = new Patient($this->db);
        $patient = $patientModel->getByIdAndUser($id, $clinicId, $userId, $canSeeAll);
        
        if (!$patient) { header('Location: ' . URL_BASE . 'pacientes'); exit; }

        // 1. CARGAMOS LOS PAÍSES
        $countryModel = new Country($this->db);
        $countries = $countryModel->getAll(); 
        
        // 2. PRE-CARGAMOS ESTADOS Y CIUDADES (Si el paciente ya tiene)
        $states = [];
        
        if (!empty($clinicCountryId)) {
            $stateModel = new State($this->db);
            $states = $stateModel->getByCountry($clinicCountryId);
        }
        
        // Cargar vista
        require_once '../views/patients/edit.php';
    }

    public function update() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $this->db->beginTransaction();
        try {
            $patientId = $_POST['patient_id']; 
            $personId = $_POST['person_id'];   

            // MAPEAMOS TODOS LOS CAMPOS QUE VIENEN DEL FORMULARIO
            // Dentro del método update()
            $updateData = [
                'person_id'              => $_POST['person_id'],
                'first_name'             => trim($_POST['first_name']),
                'last_name'              => trim($_POST['last_name']),
                'gender'                 => $_POST['gender'] ?? null,
                'marital_status'         => $_POST['marital_status'] ?? 'Single',
                'ethnicity'              => $_POST['ethnicity'] ?? null,
                'religion'               => $_POST['religion'] ?? null,
                'primary_cellphone'      => trim($_POST['primary_cellphone'] ?? ''),
                'landline_phone'         => trim($_POST['landline_phone'] ?? ''),
                'primary_email'          => trim($_POST['primary_email'] ?? ''),
                'country_ID'             => !empty($_POST['country_ID']) ? $_POST['country_ID'] : null,
                'state_ID'               => !empty($_POST['state_ID']) ? $_POST['state_ID'] : null,
                'postal_code'            => trim($_POST['postal_code'] ?? ''),
                'address_line1'          => trim($_POST['address_line1'] ?? ''),
                'address_line2'          => trim($_POST['address_line2'] ?? ''),
                'blood_type'             => $_POST['blood_type'] ?? 'Unknown',
                'emergency_contact_name' => trim($_POST['emergency_contact_name'] ?? ''),
                'emergency_contact_phone'=> trim($_POST['emergency_contact_phone'] ?? ''),
                'emergency_contact_relationship' => $_POST['emergency_contact_relationship'] ?? null,
                'critical_medical_alert' => trim($_POST['critical_medical_alert'] ?? '')
            ];

            $patientModel = new Patient($this->db);
            $result = $patientModel->update($updateData);
            
            if (!$result['success']) throw new \Exception($result['error']);

            // Auditoría
            $logSql = "INSERT INTO saas_audit_log (clinic_ID, clinic_user_ID, affected_table, affected_record_ID, action, notes, ip_address) 
                    VALUES (:cid, :uid, 'persons', :rid, 'UPDATE', 'Actualización integral de perfil del paciente', :ip)";
            $logStmt = $this->db->prepare($logSql);
            $logStmt->execute([
                'cid' => $_SESSION['clinic']['id'], 
                'uid' => $_SESSION['user']['id'], 
                'rid' => $personId, 
                'ip'  => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
            ]);

            $this->db->commit();
            $_SESSION['message'] = ['type' => 'success', 'message' => __('msg_profile_updated') ?? 'Perfil actualizado correctamente.'];
            
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            $_SESSION['message'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
        
        header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id']);
        exit;
    }

    // ==========================================
    // 3. MÓDULO CLÍNICO INDIVIDUAL (POR PESTAÑAS)
    // ==========================================

    public function storeCase() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $caseModel = new ClinicCase($this->db);
        $data = [
            'clinic_id'      => $_SESSION['clinic']['id'],
            'person_id'      => $_POST['person_id'],
            'doctor_id'      => $_SESSION['user']['id'],
            'title'          => $_POST['title'],
            'initial_reason' => $_POST['initial_reason']
        ];

        $result = $caseModel->create($data);
        if ($result['success']) {
            $_SESSION['message'] = ['type' => 'success', 'message' => __('msg_case_saved') ?? 'Caso creado con éxito.'];
            header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id'] . '?case_id=' . $result['case_id']);
        } else {
            $_SESSION['message'] = ['type' => 'error', 'message' => 'Error al crear el caso.'];
            header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id']);
        }
        exit;
    }

    public function storeTriage() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        
        $triageModel = new Triage($this->db);
        $data = array_merge($_POST, ['user_id' => $_SESSION['user']['id']]);
        
        $triageModel->create($data);
        $_SESSION['message'] = ['type' => 'success', 'message' => __('msg_triage_saved') ?? 'Triaje guardado.'];
        header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id'] . '?case_id=' . $_POST['case_id']);
        exit;
    }

    public function storeEvolution() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $this->db->beginTransaction(); 
        try {
            $evolutionModel = new Evolution($this->db);
            $data = [
                'clinic_id'            => $_SESSION['clinic']['id'],
                'person_id'            => $_POST['person_id'], 
                'case_id'              => $_POST['case_id'],
                'doctor_id'            => $_SESSION['user']['id'],
                'assistance_type'      => $_POST['assistance_type'] ?? 'In-Person',
                'evolution_notes'      => $_POST['evolution_notes'] ?? '',
                'physical_exam_notes'  => $_POST['physical_exam_notes'] ?? '',
                'patient_instructions' => $_POST['patient_instructions'] ?? '',
                'status'               => isset($_POST['is_draft']) ? 'Draft' : 'Finalized'
            ];

            $result = $evolutionModel->create($data);
            if (!$result['success']) throw new \Exception(__('msg_error_saving_evolution')); 
            
            if (!empty($_POST['consultation_price']) && is_numeric($_POST['consultation_price']) && $_POST['consultation_price'] > 0) {
                $transactionModel = new Transaction($this->db);
                $txData = [
                    'clinic_id'       => $_SESSION['clinic']['id'],
                    'person_id'       => $_POST['person_id'],
                    'user_id'         => $_SESSION['user']['id'],
                    'evo_id'          => $result['evolution_id'],
                    'movement_type'   => 'Charge',
                    'currency'        => $_SESSION['clinic']['currency_iso'] ?? 'GTQ',
                    'original_amount' => $_POST['consultation_price'],
                    'amount'          => $_POST['consultation_price'],
                    'status'          => 'Pending',
                    'notes'           => __('consultation_medical_fees')
                ];
                $txResult = $transactionModel->create($txData);
                if (!$txResult['success']) throw new \Exception(__('msg_error_saving_transaction')); 
            }

            $this->db->commit();
            $_SESSION['message'] = ['type' => 'success', 'message' => __('msg_evolution_saved')];
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['message'] = ['type' => 'error', 'message' => __('msg_error_action_failed')];
        }
        header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id'] . '?case_id=' . $_POST['case_id']);
        exit;
    }

    /**
     * Guarda una receta rápida (Sin pasar por el flujo unificado)
     */
    public function storePrescription() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $this->db->beginTransaction();
        try {
            $clinicId = $_SESSION['clinic']['id'];
            $userId = $_SESSION['user']['id'];
            
            // 1. Creamos una Evolución Rápida para cumplir con la Base de Datos Legal
            $evolutionModel = new Evolution($this->db);
            $evoData = [
                'clinic_id'            => $clinicId,
                'person_id'            => $_POST['person_id'],
                'case_id'              => $_POST['case_id'],
                'doctor_id'            => $userId,
                'assistance_type'      => 'In-Person',
                'evolution_notes'      => __('prescription_only_evolution') ?? 'Emisión de receta médica',
                'physical_exam_notes'  => '',
                'patient_instructions' => $_POST['general_instructions'] ?? '',
                'status'               => 'Finalized'
            ];
            
            $evoResult = $evolutionModel->create($evoData);
            if (!$evoResult['success']) throw new \Exception($evoResult['error']);
            $evolutionId = $evoResult['evolution_id'];

            // 2. Creamos la Receta Médica enlazada
            $prescriptionModel = new Prescription($this->db);
            $prescData = [
                'evolution_id'         => $evolutionId,
                'doctor_id'            => $userId,
                'general_instructions' => $_POST['general_instructions'] ?? '',
                'medications'          => $_POST['medications'] ?? []
            ];
            
            $prescResult = $prescriptionModel->create($prescData);
            if (!$prescResult['success']) throw new \Exception($prescResult['error']);

            $this->db->commit();
            $_SESSION['message'] = ['type' => 'success', 'message' => __('msg_prescription_saved') ?? 'Receta emitida correctamente.'];

        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['message'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }

        header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id'] . '?case_id=' . $_POST['case_id']);
        exit;
    }

    // ==========================================
    // 4. MÓDULO DE CONSULTA UNIFICADA (MODO ENFOQUE)
    // ==========================================

    public function newConsultation($patientId) {
        if (!isset($_SESSION['user']['id'])) { header('Location: ' . URL_BASE . 'login'); exit; }

        $patientModel = new Patient($this->db);
        $caseModel = new ClinicCase($this->db);
        
        $patient = $patientModel->getByIdAndUser($patientId, $_SESSION['clinic']['id'], $_SESSION['user']['id'], true);
        if (!$patient) { header('Location: ' . URL_BASE . 'pacientes'); exit; }

        $cases = $caseModel->getByPerson($patient['ID'], $_SESSION['clinic']['id']);
        $activeCaseId = $_GET['case_id'] ?? (!empty($cases) ? $cases[0]['ID'] : null);

        if (!$activeCaseId) {
            $_SESSION['message'] = ['type' => 'error', 'message' => 'Debe crear un caso clínico primero.'];
            header('Location: ' . URL_BASE . 'pacientes/ver/' . $patientId); exit;
        }
        require_once '../views/patients/consultation_form.php';
    }

    // ==========================================
    // 5. ALMACENAR CONSULTA (TODA LA CONSULTA)
    // ==========================================
    public function storeConsultation() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $this->db->beginTransaction();
        try {
            $clinicId = $_SESSION['clinic']['id'];
            $userId = $_POST['person_id'];
            $patientId = $_POST['patient_id'];
            $caseId = $_POST['case_id'];

            // 1. GUARDAR EVOLUCIÓN
            $evolutionModel = new Evolution($this->db);
            $evoData = [
                'clinic_id'            => $clinicId,
                'person_id'            => $patientId,
                'case_id'              => $caseId,
                'doctor_id'            => $userId,
                'assistance_type'      => $_POST['assistance_type'] ?? 'In-Person',
                'evolution_notes'      => $_POST['evolution_notes'],
                'physical_exam_notes'  => $_POST['physical_exam_notes'] ?? '',
                'patient_instructions' => $_POST['patient_instructions'] ?? '',
                'status'               => (isset($_POST['is_draft']) && $_POST['is_draft'] == '1') ? 'Draft' : 'Finalized'
            ];
            $evoResult = $evolutionModel->create($evoData);
            if (!$evoResult['success']) throw new \Exception("Evolution Save Error: " . $evoResult['error']);
            $evolutionId = $evoResult['evolution_id'];

            // 2. GUARDAR TRIAJE
            // 2. GUARDAR TRIAJE / SIGNOS VITALES
            if (!empty($_POST['weight_value']) || !empty($_POST['systolic'])) {
                $triageModel = new Triage($this->db);
                $triageData = [
                    'person_id'            => $patientId, // clinic_patients.ID
                    'evolution_id'         => $evolutionId,
                    'user_id'              => $userId,
                    'weight_value'         => $_POST['weight_value'] ?? null,
                    'weight_unit'          => $_POST['weight_unit'] ?? 'lb',
                    'height_value'         => $_POST['height_value'] ?? null,
                    'height_unit'          => $_POST['height_unit'] ?? 'cm',
                    'temperature_value'    => $_POST['temperature_value'] ?? null,
                    'temperature_unit'     => $_POST['temperature_unit'] ?? 'C',
                    'systolic_bp'          => $_POST['systolic'] ?? null,
                    'diastolic_bp'         => $_POST['diastolic'] ?? null,
                    'heart_rate_bpm'       => $_POST['heart_rate'] ?? null,
                    'respiratory_rate_rpm' => $_POST['respiratory_rate'] ?? null, // Si agregaste este input
                    'oxygen_saturation_pct'=> $_POST['spo2'] ?? null
                ];
                $triageResult = $triageModel->create($triageData);
                if (!$triageResult['success']) throw new \Exception("Triage Save Error: " . $triageResult['error']);
            }

            // 3. GUARDAR RECETA
            // 3. GUARDAR RECETA
            $hasInstructions = !empty(trim($_POST['patient_instructions'] ?? ''));
            $hasMedications = !empty($_POST['medications']) && is_array($_POST['medications']);

            if ($hasInstructions || $hasMedications) {
                $formattedMedications = [];

                if ($hasMedications) {
                    foreach ($_POST['medications'] as $med) {
                        // Saltamos si no tiene nombre de medicamento
                        if (empty(trim($med['name'] ?? ''))) continue;

                        // Concatenamos los valores para mantener compatibilidad con la DB
                        $formattedMedications[] = [
                            'name'           => trim($med['name']),
                            'dosage'         => trim(($med['dosage_val'] ?? '') . ' ' . ($med['dosage_unit'] ?? '')),
                            'frequency'      => "Cada " . ($med['freq_val'] ?? '') . " horas",
                            'duration'       => trim(($med['dur_val'] ?? '') . ' ' . ($med['dur_unit'] ?? '')),
                            'total_quantity' => trim($med['total_quantity'] ?? ''),
                            'additional_notes'=> trim($med['additional_notes'] ?? '')
                        ];
                    }
                }

                $prescriptionModel = new Prescription($this->db);
                $prescData = [
                    'evolution_id'         => $evolutionId,
                    'doctor_id'            => $userId,
                    'general_instructions' => $_POST['patient_instructions'] ?? '',
                    'medications'          => $formattedMedications // Enviamos la lista ya formateada
                ];

                $prescResult = $prescriptionModel->create($prescData);
                if (!$prescResult['success']) throw new \Exception("Prescription Save Error: " . $prescResult['error']);
            }

            // 4. GENERAR COBRO
            if (!empty($_POST['consultation_price']) && is_numeric($_POST['consultation_price']) && $_POST['consultation_price'] > 0) {
                $transactionModel = new Transaction($this->db);
                $txData = [
                    'clinic_id'       => $clinicId,
                    'person_id'       => $patientId,
                    'user_id'         => $userId,
                    'evo_id'          => $evolutionId,
                    'movement_type'   => 'Charge',
                    'currency'        => $_SESSION['clinic']['currency_iso'] ?? 'GTQ',
                    'original_amount' => $_POST['consultation_price'],
                    'amount'          => $_POST['consultation_price'],
                    'status'          => 'Pending',
                    'notes'           => __('consultation_medical_fees') ?? 'Honorarios médicos'
                ];
                $txResult = $transactionModel->create($txData);
                if (!$txResult['success']) throw new \Exception("Transaction Error: " . $txResult['error']);
            }

            // 5. AUDITORÍA
            $logSql = "INSERT INTO saas_audit_log (clinic_ID, clinic_user_ID, affected_table, affected_record_ID, action, notes, ip_address) 
                       VALUES (:cid, :uid, 'clinic_evolutions', :rid, 'INSERT', 'Consulta unificada completada', :ip)";
            $logStmt = $this->db->prepare($logSql);
            $logStmt->execute([
                'cid' => $clinicId, 'uid' => $userId, 'rid' => $evolutionId, 'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
            ]);

            $this->db->commit();
            $_SESSION['message'] = ['type' => 'success', 'message' => __('msg_consultation_saved') ?? 'Consulta finalizada con éxito.'];

        } catch (\Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            
            // Guardamos lo que el usuario escribió en la sesión temporalmente
            $_SESSION['old_input'] = $_POST; 
            $_SESSION['message'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];
            
            // Redireccionamos atrás
            // header('Location: ' . $_SERVER['HTTP_REFERER']);
            // exit;
        }
        header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id'] . '?case_id=' . $_POST['case_id']);
        exit;
    }
}