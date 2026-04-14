<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\Patient; // Asegúrate de importar el modelo
use App\Models\IdentityType;
use App\Models\Country;

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

        // Aquí luego le pediremos al Modelo los datos completos del paciente usando el $id
        // Por ahora, solo mostraremos una pantalla en blanco con la alerta de éxito

        require_once '../views/layouts/header.php';
        require_once '../views/layouts/sidebar.php';
        
        echo '<div class="flex-1 flex flex-col h-full overflow-hidden">';
        require_once '../views/layouts/topbar.php';
        
        echo '<main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">';
        echo '<h1 class="text-3xl font-bold text-slate-800">Expediente del Paciente #' . htmlspecialchars($id) . '</h1>';
        echo '<p class="text-slate-500 mt-2">Esta pantalla está en construcción...</p>';
        echo '</main></div>';
        
        require_once '../views/layouts/footer.php';
    }
}