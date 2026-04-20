<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Patient; // Asegúrate de importar el modelo
use App\Models\IdentityType;
use App\Models\Country;
use App\Models\ClinicCase;
use App\Models\Evolution;
use App\Models\Triage;
use App\Models\Background;
use App\Models\Transaction;
use App\Models\Prescription;

class PatientController {
    
    private $db;

    public function __construct($dbConnection) {
        // Recibimos la conexión a la base de datos desde el enrutador (index.php principal)
        $this->db = $dbConnection;
    }

    /**
     * Muestra la tabla principal de pacientes
     */
    public function index() {
        // 1. Verificación de Seguridad Básica
        if (!isset($_SESSION['user']['id'])) {
            header('Location: ' . URL_BASE . 'login');
            exit;
        }

        // 2. Extracción de Variables de Sesión
        $userId = $_SESSION['user']['id'];
        $clinicId = $_SESSION['clinic']['id'];
        $clinicCountryId = $_SESSION['clinic']['country_id'] ?? 1;

        // Evaluación de Permisos (JSON)
        $permissions = $_SESSION['user']['permissions'] ?? []; 
        $canSeeAll = isset($permissions['all_access']) || isset($permissions['all_patients']);

        // 3. Instanciar los Modelos
        $userModel = new User($this->db);
        $patientModel = new Patient($this->db);
        $identityTypeModel = new IdentityType($this->db);
        $countryModel = new Country($this->db);

        // 4. Obtener Datos para los Selects del Modal
        $doctors = $userModel->getDoctorsByClinic($clinicId);
        $identityTypes = $identityTypeModel->getByCountry($clinicCountryId);
        $countries = $countryModel->getAll();
        
        // 5. Obtener los Pacientes (Aplicando el filtro de Seguridad/Permisos)
        // Sustituimos getLatestByClinic por getPatientsForUser
        $patients = $patientModel->getPatientsForUser($clinicId, $userId, $canSeeAll);
        
        // ==========================================
        // 6. TRADUCCIÓN AL VUELO PARA CADA PACIENTE
        // ==========================================
        foreach ($patients as &$p) {
            // Traducimos el estado dinámicamente
            $p['status_translated'] = __('status_' . $p['patient_status']);
            
            // Traducimos la relación del tutor (si existe)
            if (!empty($p['tutor_relation'])) {
                $p['tutor_relation_translated'] = __('rel_' . $p['tutor_relation']);
            } else {
                $p['tutor_relation_translated'] = '';
            }
        }
        unset($p); // Buena práctica de seguridad en PHP tras usar referencias

        // 7. Cargar la vista (todas las variables anteriores estarán disponibles en el HTML)
        require_once '../views/patients/index.php';
    }

    /**
     * Procesa el formulario del Modal para guardar un nuevo paciente
     */
    public function store() {
        if (!isset($_SESSION['user']['id'])) { header('Location: ' . URL_BASE . 'login'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // ==========================================
            // 1. VALIDACIÓN BACKEND (Protección contra Hackers/Bots)
            // ==========================================
            
            // Trim limpia los espacios en blanco al inicio y al final
            $firstName = trim($_POST['first_name'] ?? '');
            $lastName  = trim($_POST['last_name'] ?? '');
            $email     = trim($_POST['primary_email'] ?? '');
        
            $requiresTutor = isset($_POST['requires_tutor']);
            $identityNumber = trim($_POST['identity_number'] ?? '');
            $tutorIdentity = trim($_POST['tutor_identity_number'] ?? '');
            
            // A. Verificar campos obligatorios
            if (empty($firstName) || empty($lastName) || empty($_POST['birth_date'])) {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'message' => __('msg_error_empty_fields')
                ];
                header('Location: ' . URL_BASE . 'pacientes');
                exit;
            }

            // B. Verificar formato de Email (si es que ingresó uno, porque no es obligatorio en la BD)
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'message' => __('msg_error_email')
                ];
                header('Location: ' . URL_BASE . 'pacientes');
                exit;
            }
            
            // C. Si NO requiere tutor (es adulto) y el ID está vacío
            if (!$requiresTutor && empty($identityNumber)) {
                $_SESSION['message'] = ['type' => 'error', 'message' => __('msg_error_identity_required')];
                header('Location: ' . URL_BASE . 'pacientes');
                exit;
            }

            // D. Si SI requiere tutor y el ID del tutor está vacío
            if ($requiresTutor && empty($tutorIdentity)) {
                $_SESSION['message'] = ['type' => 'error', 'message' => __('msg_error_tutor_identity_required')];
                header('Location: ' . URL_BASE . 'pacientes');
                exit;
            }

            // ==========================================
            // 2. PREPARACIÓN DE DATOS
            // ==========================================
            $data = [
                // Identidad del Paciente
                'identity_type_ID'   => $_POST['identity_type_ID'] ?? null, // <-- FALTABA ESTE
                'identity_number'    => $identityNumber,
                
                // Datos Personales
                'first_name'         => $firstName,
                'last_name'          => $lastName,
                'birth_date'         => $_POST['birth_date'],
                'gender'             => $_POST['gender'] ?: null,
                
                // Contacto y Ubicación
                'primary_cellphone'  => trim($_POST['primary_cellphone']) ?: null,
                'primary_email'      => $email ?: null,
                'country_ID'         => $_POST['country_ID'] ?? 1,
                'state_ID'           => $_POST['state_ID'] ?? 1,
                'city_ID'            => $_POST['city_ID'] ?? 1,

                // Usuario asignado
                'assigned_doctor_ID' => $_POST['assigned_doctor_ID'] ?? null,
                
                // Datos del Tutor
                'requires_tutor'           => isset($_POST['requires_tutor']) ? 1 : 0,
                'tutor_country_ID'         => $_POST['tutor_country_ID'] ?? $_POST['country_ID'],
                'tutor_identity_type_ID'   => $_POST['tutor_identity_type_ID'] ?? null, // <-- FALTABA ESTE
                'tutor_identity_number'    => $tutorIdentity, // <-- FALTABA ESTE
                'tutor_first_name'         => trim($_POST['tutor_first_name'] ?? ''),
                'tutor_last_name'          => trim($_POST['tutor_last_name'] ?? ''),
                'tutor_phone'              => trim($_POST['tutor_phone'] ?? ''),
                'tutor_relationship'       => $_POST['tutor_relationship'] ?? null
            ];

            // Instanciar modelo y guardar
            $patientModel = new \App\Models\Patient($this->db);
            $result = $patientModel->create($data);

            if ($result['success']) {
                $patientId = $result['patient_id'];
                
                if ($result['is_new']) {
                    $_SESSION['message'] = [
                        'type' => 'success',
                        'message' => __('msg_patient_saved')
                    ];
                } else {
                    $_SESSION['message'] = [
                        'type' => 'info',
                        'message' => __('msg_patient_exists')
                    ];
                }
                
                // MAGIA: Redirigimos directamente a la vista de "Ver Expediente"
                header('Location: ' . URL_BASE . 'pacientes/ver/' . $patientId);
                exit;
            } else {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'message' => $result['error'] ?? __('msg_error_database')
                ];
                header('Location: ' . URL_BASE . 'pacientes');
                exit;
            }
        }

        // Si alguien intenta entrar a /pacientes/guardar desde la URL (GET), lo regresamos
        header('Location: ' . URL_BASE . 'pacientes');
        exit;
    }

    /**
     * Busca pacientes por nombre o documento
     */
    public function search() {
        // Le decimos al navegador que esto es un JSON, no un HTML
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(['error' => 'No autorizado']);
            return;
        }

        $term = $_GET['q'] ?? '';
        $clinicId = $_SESSION['clinic']['id'];
        $patientModel = new Patient($this->db);

        // Si borró la búsqueda, devolvemos los últimos 50. Si escribió algo, buscamos.
        if (trim($term) === '') {
            $patients = $patientModel->getLatestByClinic($clinicId, 50);
        } else {
            $patients = $patientModel->searchPatients($clinicId, trim($term), 50);
        }

        // Iteramos para traducir los ENUMs antes de enviarlos a JS
        foreach ($patients as &$p) {
            // Traducimos el estado
            $p['status_translated'] = __('status_' . $p['patient_status']);
            
            // Traducimos la relación del tutor (si existe)
            if (!empty($p['tutor_relation'])) {
                $p['tutor_relation_translated'] = __('rel_' . $p['tutor_relation']);
            } else {
                $p['tutor_relation_translated'] = '';
            }
        }

        // Imprimimos el resultado para que JS lo atrape
        echo json_encode($patients);
    }

    /**
     * Muestra el expediente completo de un paciente específico
     */
    public function show($id) {
        if (!isset($_SESSION['user']['id'])) { header('Location: ' . URL_BASE . 'login'); exit; }

        $userId = $_SESSION['user']['id'];
        $clinicId = $_SESSION['clinic']['id'];
        
        // Evaluamos permisos del JSON de la sesión
        $permissions = $_SESSION['user']['permissions'] ?? [];
        $canSeeAll = isset($permissions['all_access']) || isset($permissions['all_patients']);

        $patientModel = new Patient($this->db);
        $caseModel = new ClinicCase($this->db);
        $evolutionModel = new Evolution($this->db);
        $bgModel = new Background($this->db);
        
        // 1. Consultamos el paciente con validación de seguridad
        $patient = $patientModel->getByIdAndUser($id, $clinicId, $userId, $canSeeAll);

        // 2. EXPULSIÓN TEMPRANA: Si no existe o no tiene permiso, no hacemos más consultas
        if (!$patient) {
            $_SESSION['message'] = [
                'type' => 'error', 
                'message' => __('msg_error_access_denied')
            ];
            header('Location: ' . URL_BASE . 'pacientes');
            exit;
        }

        // 3. Obtenemos los casos de este paciente
        // (Nota: Asegúrate de usar $patient['ID'] que es el person_ID para tu tabla clinic_cases)
        $cases = $caseModel->getByPerson($patient['ID'], $clinicId);

        // 4. DEFINIMOS el Caso Activo (obtenido por URL o tomamos el más reciente)
        $activeCaseId = isset($_GET['case_id']) ? $_GET['case_id'] : (!empty($cases) ? $cases[0]['ID'] : null);

        // 5. Obtenemos el historial médico del paciente
        $backgroundData = $bgModel->getPatientBackground($patient['ID'], $userId);

        // 6. AHORA SÍ, sabiendo cuál es el activeCaseId, buscamos sus evoluciones
        $evolutions = [];
        if ($activeCaseId) {
            $evolutions = $evolutionModel->getByCase($activeCaseId);
        }

        require_once '../views/patients/show.php';
    }

    /**
     * Muestra la pantalla dedicada para editar el perfil del paciente
     */
    public function edit($id) {
        if (!isset($_SESSION['user']['id'])) { header('Location: ' . URL_BASE . 'login'); exit; }

        $userId = $_SESSION['user']['id'];
        $clinicId = $_SESSION['clinic']['id'];

        // ==========================================
        // VALIDACIÓN DE ROLES Y PERMISOS
        // ==========================================
        $permissions = $_SESSION['user']['permissions'] ?? [];
        
        // Supongamos que tu JSON de permisos tiene una llave "can_edit_patients"
        $canEdit = isset($permissions['all_access']) || isset($permissions['can_edit_patients']);
        
        // *Hack temporal*: Si eres el Super Admin (ID 1) o estamos en desarrollo, lo permitimos.
        // Después lo amarraremos 100% a la interfaz de Roles.
        if (!$canEdit && $_SESSION['user']['role_id'] != 1) {
            $_SESSION['message'] = [
                'type' => 'error', 
                'message' => __('msg_error_permission_denied') ?? 'No tienes los permisos necesarios para editar datos maestros.'
            ];
            header('Location: ' . URL_BASE . 'pacientes/ver/' . $id);
            exit;
        }

        $patientModel = new Patient($this->db);
        // Obtenemos los datos actuales
        $patient = $patientModel->getByIdAndUser($id, $clinicId, $userId, true);
        
        if (!$patient) {
            header('Location: ' . URL_BASE . 'pacientes'); exit;
        }

        // Necesitamos catálogos para los selects del formulario
        $countryModel = new \App\Models\Country($this->db);
        $countries = $countryModel->getAll();
        
        // Cargamos la vista dedicada
        require_once '../views/patients/edit.php';
    }

    /**
     * Procesa la actualización de los datos en la tabla `persons` y guarda el Log
     */
    public function update() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $this->db->beginTransaction();

        try {
            $patientId = $_POST['patient_id']; // ID en clinic_patients (para URL)
            $personId = $_POST['person_id'];   // ID en persons (para la BD)

            // 1. Recopilar datos (similar a store, pero para UPDATE)
            $updateData = [
                'person_id'              => $personId,
                'first_name'             => trim($_POST['first_name']),
                'last_name'              => trim($_POST['last_name']),
                'blood_type'             => $_POST['blood_type'] ?? 'Unknown',
                'critical_medical_alert' => trim($_POST['critical_medical_alert'] ?? ''),
                // ... (El resto de campos como email, teléfono, dirección, etc.)
            ];

            // 2. Ejecutar el Update en el Modelo (Que deberás crear en Patient.php)
            $patientModel = new Patient($this->db);
            $result = $patientModel->update($updateData);

            if (!$result['success']) throw new \Exception($result['error']);

            // 3. BITÁCORA DE AUDITORÍA (LOG) - Obligatorio para modificaciones de identidad [cite: 108-109]
            $logSql = "INSERT INTO saas_audit_log (clinic_ID, clinic_user_ID, affected_table, affected_record_ID, action, notes, ip_address) 
                       VALUES (:cid, :uid, 'persons', :rid, 'UPDATE', 'Actualización de perfil del paciente', :ip)";
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
            $this->db->rollBack();
            $_SESSION['message'] = ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }

        header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id']);
        exit;
    }

    /**
     * Guarda un nuevo caso clínico desde el modal
     */
    public function storeCase() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URL_BASE . 'login');
            exit;
        }

        $caseModel = new \App\Models\ClinicCase($this->db);
        
        $patientId = $_POST['patient_id']; // Para redireccionar (URL)
        $personId  = $_POST['person_id'];  // Para guardar en la DB
        
        $data = [
            'clinic_id'      => $_SESSION['clinic']['id'],
            'person_id'      => $personId,
            'doctor_id'      => $_SESSION['user']['id'],
            'title'          => $_POST['title'],
            'initial_reason' => $_POST['initial_reason']
        ];

        $result = $caseModel->create($data);

        if ($result['success']) {
            $_SESSION['message'] = ['type' => 'success', 'message' => 'Caso clínico aperturado con éxito.'];
            header('Location: ' . URL_BASE . 'pacientes/ver/' . $patientId . '?case_id=' . $result['case_id']);
        } else {
            $_SESSION['message'] = ['type' => 'error', 'message' => 'Error al crear el caso: ' . $result['error']];
            header('Location: ' . URL_BASE . 'pacientes/ver/' . $patientId);
        }
        exit;
    }

    /**
     * Guarda una nueva Evolución Médica (SOAP) y genera el cargo financiero si aplica
     */
    public function storeEvolution() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        // Usamos transacciones de base de datos para asegurar integridad (Todo o Nada)
        $this->db->beginTransaction(); 
        
        try {
            $evolutionModel = new \App\Models\Evolution($this->db);
            
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

            // 1. Guardar la nota clínica
            $result = $evolutionModel->create($data);
            
            if (!$result['success']) {
                // Lanzamos excepción, pero la capturamos abajo para mostrar el mensaje limpio
                throw new \Exception(__('msg_error_saving_evolution')); 
            }
            
            $evolutionId = $result['evolution_id'];

            // 2. Integración Financiera usando su propio Modelo
            if (!empty($_POST['consultation_price']) && is_numeric($_POST['consultation_price']) && $_POST['consultation_price'] > 0) {
                
                $transactionModel = new Transaction($this->db);
                
                $txData = [
                    'clinic_id'       => $_SESSION['clinic']['id'],
                    'person_id'       => $_POST['person_id'],
                    'user_id'         => $_SESSION['user']['id'],
                    'evo_id'          => $evolutionId,
                    'movement_type'   => 'Charge',
                    'currency'        => $_SESSION['clinic']['currency_iso'] ?? 'GTQ',
                    'original_amount' => $_POST['consultation_price'],
                    'amount'          => $_POST['consultation_price'],
                    'status'          => 'Pending',
                    'notes'           => __('consultation_medical_fees') // <-- Texto traducido
                ];
                
                $txResult = $transactionModel->create($txData);
                
                if (!$txResult['success']) {
                    throw new \Exception(__('msg_error_saving_transaction')); 
                }
            }

            // Confirmar todos los cambios en la BD
            $this->db->commit();
            
            // Éxito total
            $_SESSION['message'] = [
                'type' => 'success', 
                'message' => __('msg_evolution_saved')
            ];

        } catch (\Exception $e) {
            // Si algo falló, revertimos todo (No se guarda ni la nota ni el cobro)
            $this->db->rollBack();
            
            // NOTA: En producción, es recomendable no mostrar el error SQL puro ($e->getMessage()) 
            // al usuario final por seguridad. Usamos un mensaje traducido genérico.
            // Opcionalmente, aquí podrías registrar $e->getMessage() en un archivo log interno (ej. error_log).
            
            $_SESSION['message'] = [
                'type' => 'error', 
                'message' => __('msg_error_action_failed') 
            ];
        }header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id'] . '?case_id=' . $_POST['case_id']);
        exit;
    }

    /*
    * Guarda el triaje / signos vitales
    */
    public function storeTriage() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $triageModel = new Triage($this->db);
        $data = array_merge($_POST, [
            'user_id' => $_SESSION['user']['id']
        ]);

        $triageModel->create($data);
        header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id'] . '?case_id=' . $_POST['case_id']);
        exit;
    }

    /**
     * Muestra la pantalla de "Modo Enfoque" para una nueva consulta unificada
     */
    public function newConsultation($patientId) {
        if (!isset($_SESSION['user']['id'])) { header('Location: ' . URL_BASE . 'login'); exit; }

        $userId = $_SESSION['user']['id'];
        $clinicId = $_SESSION['clinic']['id'];
        
        $patientModel = new Patient($this->db);
        $caseModel = new ClinicCase($this->db);
        
        $patient = $patientModel->getByIdAndUser($patientId, $clinicId, $userId, true);
        if (!$patient) {
            header('Location: ' . URL_BASE . 'pacientes'); exit;
        }

        $cases = $caseModel->getByPerson($patient['ID'], $clinicId);
        $activeCaseId = $_GET['case_id'] ?? (!empty($cases) ? $cases[0]['ID'] : null);

        // Validamos que exista un caso para poder consultar
        if (!$activeCaseId) {
            $_SESSION['message'] = ['type' => 'error', 'message' => 'Debe crear un caso clínico primero.'];
            header('Location: ' . URL_BASE . 'pacientes/ver/' . $patientId); exit;
        }

        require_once '../views/patients/consultation_form.php';
    }

    /**
     * Guarda el Triaje, la Evolución, el Cobro y el Log en un solo movimiento
     */
    public function storeConsultation() {
        if (!isset($_SESSION['user']['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $this->db->beginTransaction();

        try {
            $clinicId = $_SESSION['clinic']['id'];
            $userId = $_SESSION['user']['id'];
            $personId = $_POST['person_id'];
            $caseId = $_POST['case_id'];

            // 1. GUARDAR EVOLUCIÓN (Obligatorio)
            $evolutionModel = new Evolution($this->db);
            $evoData = [
                'clinic_id'            => $clinicId,
                'person_id'            => $personId,
                'case_id'              => $caseId,
                'doctor_id'            => $userId,
                'assistance_type'      => $_POST['assistance_type'] ?? 'In-Person',
                'evolution_notes'      => $_POST['evolution_notes'],
                'physical_exam_notes'  => $_POST['physical_exam_notes'] ?? '',
                'patient_instructions' => $_POST['patient_instructions'] ?? '',
                'status' => (isset($_POST['is_draft']) && $_POST['is_draft'] == '1') ? 'Draft' : 'Finalized'
            ];
            $evoResult = $evolutionModel->create($evoData);
            if (!$evoResult['success']) throw new \Exception($evoResult['error']);
            $evolutionId = $evoResult['evolution_id'];

            // 2. GUARDAR TRIAJE (Solo si el médico ingresó al menos el peso o la presión)
            if (!empty($_POST['weight_value']) || !empty($_POST['systolic_bp'])) {
                $triageModel = new Triage($this->db);
                $triageData = [
                    'person_id'             => $personId,
                    'user_id'               => $userId,
                    'evolution_id'          => $evolutionId,
                    'weight_value'          => $_POST['weight_value'] ?? null,
                    'weight_unit'           => $_POST['weight_unit'] ?? 'kg',
                    'height_value'          => $_POST['height_value'] ?? null,
                    'height_unit'           => $_POST['height_unit'] ?? 'cm',
                    'temperature_value'     => $_POST['temperature_value'] ?? null,
                    'temperature_unit'      => $_POST['temperature_unit'] ?? 'C',
                    'systolic_bp'           => $_POST['systolic_bp'] ?? null,
                    'diastolic_bp'          => $_POST['diastolic_bp'] ?? null,
                    'heart_rate_bpm'        => $_POST['heart_rate_bpm'] ?? null,
                    'respiratory_rate_rpm'  => $_POST['respiratory_rate_rpm'] ?? null,
                    'oxygen_saturation_pct' => $_POST['oxygen_saturation_pct'] ?? null,
                    'glucose_value'         => $_POST['glucose_value'] ?? null
                ];
                $triageResult = $triageModel->create($triageData);
                if (!$triageResult['success']) throw new \Exception($triageResult['error']);
            }

            // 3 GUARDAR RECETA (Si hay instrucciones generales o medicamentos detallados)
            $hasInstructions = !empty(trim($_POST['patient_instructions'] ?? ''));
            $hasMedications = !empty($_POST['medications']) && is_array($_POST['medications']);

            if ($hasInstructions || $hasMedications) {
                $prescriptionModel = new Prescription($this->db);
                
                $prescData = [
                    'evolution_id'         => $evolutionId, // Lo obtenemos del Paso 1
                    'doctor_id'            => $userId,
                    'general_instructions' => $_POST['patient_instructions'] ?? '',
                    'medications'          => $_POST['medications'] ?? [] // Array de inputs del HTML
                ];
                
                $prescResult = $prescriptionModel->create($prescData);
                
                if (!$prescResult['success']) {
                    throw new \Exception("Error al generar la receta: " . $prescResult['error']);
                }
            }

            // 4. GENERAR COBRO FINANCIERO (Si el precio > 0)
            if (!empty($_POST['consultation_price']) && is_numeric($_POST['consultation_price']) && $_POST['consultation_price'] > 0) {
                $transactionModel = new Transaction($this->db);
                $txData = [
                    'clinic_id'       => $clinicId,
                    'person_id'       => $personId,
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
                if (!$txResult['success']) throw new \Exception("Error financiero: " . $txResult['error']);
            }

            // 4. BITÁCORA DE AUDITORÍA (LOG) - Registramos la acción unificada
            $logSql = "INSERT INTO saas_audit_log (clinic_ID, clinic_user_ID, affected_table, affected_record_ID, action, notes, ip_address) 
                       VALUES (:cid, :uid, 'clinic_evolutions', :rid, 'INSERT', 'Consulta unificada completada', :ip)";
            $logStmt = $this->db->prepare($logSql);
            $logStmt->execute([
                'cid' => $clinicId, 'uid' => $userId, 'rid' => $evolutionId, 'ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
            ]);

            // ¡ÉXITO! Confirmamos todo
            $this->db->commit();
            $_SESSION['message'] = ['type' => 'success', 'message' => __('msg_consultation_saved') ?? 'Consulta finalizada con éxito.'];

        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['message'] = ['type' => 'error', 'message' => 'No se guardaron los cambios. Error: ' . $e->getMessage()];
        }

        header('Location: ' . URL_BASE . 'pacientes/ver/' . $_POST['patient_id'] . '?case_id=' . $_POST['case_id']);
        exit;
    }

}