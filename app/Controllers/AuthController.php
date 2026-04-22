<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {

    private $db;

    // 1. Recibimos la base de datos al crear el Router
    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }
    
    public function showLogin() {
        require_once '../views/auth/login.php';
    }

    public function login() {
        $clinicCode = trim($_POST['clinic_code'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $ip = $this->getUserIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

        // 1. Validación de campos vacíos
        if (empty($clinicCode) || empty($email) || empty($password)) {
            $this->sendAlert('error', "ERR-LOG-0001: " . __('msg_all_fields_required'), $email, $clinicCode);
        }

        $clinicModel = new \App\Models\Clinic();
        $clinic = $clinicModel->findByCode($clinicCode);

        // 2. Buscar Clínica
        if (!$clinic) {
            // Registramos el intento fallido (clinic_id 0 porque no existe)
            $this->logLoginAttempt(null, $email, $ip, false);
            $this->sendAlert('error', "ERR-LOG-0002: " . __('msg_clinic_not_found_error'), $email, $clinicCode);
        }

        // 3. Buscar Usuario dentro de la clínica
        $userModel = new \App\Models\User();
        $user = $userModel->findByEmailAndClinic($email, $clinic['ID']);

        $isSuccess = ($user && hash('sha256', $password) === $user['password_hash']);
        $this->logLoginAttempt($clinic['ID'], $email, $ip, $isSuccess);

        if (!$isSuccess) {
            $this->sendAlert('error', "ERR-LOG-0003: " . __('msg_user_or_password_incorrect_error'), $email, $clinicCode);
        }

        // 4. Login Exitoso
        // Configuración de Sesiones
        session_destroy();
        session_start();

        // Decodificamos el JSON de la base de datos a un array de PHP
        $userPermissions = json_decode($user['role_permissions'], true) ?? [];

        $_SESSION['is_logged_in'] = true;
        $_SESSION['clinic'] = [
            'id' => $clinic['ID'],
            'uuid' => $clinic['uuid'],
            'clinic_code' => $clinicCode,
            'logo_path' => $clinic['logo_path'],
            'country_id' => $clinic['country_ID'],
            'state_id' => $clinic['state_ID'],
            'storage_limit_gb' => $clinic['storage_limit_gb'],
            'storage_used_bytes' => $clinic['storage_used_bytes'],
            'timezone' => $clinic['timezone'],
            'currency_iso' => $clinic['currency_iso'],
            'date_format' => $clinic['date_format']
        ];
        $_SESSION['user'] = [
            'id' => $user['ID'],
            'clinic_id' => $user['clinic_ID'],
            'full_name' => $user['first_name'] . ' ' . $user['last_name'],
            'email' => $user['email'],
            'lang' => $user['preferred_language'] ?? 'es',
            'profile_pic' => $user['profile_pic_path'],
            'is_doctor' => $user['is_doctor'],
            'role_name' => $user['role_name'],
            'permissions' => $userPermissions
        ];
        
        $_SESSION['lang'] = $user['preferred_language'];

        $this->createUserAccessLog($user['ID'], $user['clinic_ID'], $ip, $userAgent);
        
        // Limpieza y redirección inmediata
        if (ob_get_length()) ob_clean(); 
        header('Location: ' . URL_BASE . 'dashboard');
        exit;
    }

    // Métodos privados para manejar los inserts en las nuevas tablas
    private function logLoginAttempt($clinicId, $email, $ip, $success) {
        $db = \App\Core\Database::getConnection();
        $stmt = $db->prepare("INSERT INTO login_attempts (clinic_ID, entered_email, ip_address, is_success) VALUES (?, ?, ?, ?)");
        $stmt->execute([$clinicId, $email, $ip, $success ? 1 : 0]);
    }

    private function createUserAccessLog($userId, $clinicId, $ip, $userAgent) {
        $db = \App\Core\Database::getConnection();
        $token = bin2hex(random_bytes(32)); // Generamos un token de sesión único
        $expires = date('Y-m-d H:i:s', strtotime('+8 hours')); // Expira en 8 horas
        
        $stmt = $db->prepare("INSERT INTO user_access_logs (user_ID, clinic_ID, ip_address, user_agent, session_token, expires_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $clinicId, $ip, $userAgent, $token, $expires]);
        
        // Guardamos el token en sesión por si queremos validar sesiones concurrentes luego
        $_SESSION['user']['session_token'] = $token;
    }

    public function logout() {
        // 1. OBTENER EL TOKEN: Primero recuperamos el identificador de la sesión actual
        // Este token debiste guardarlo en $_SESSION al momento del login
        $token = $_SESSION['user']['session_token'] ?? null;

        // 2. ACTUALIZAR BASE DE DATOS: Si hay un token, marcamos el log como inválido
        if ($token) {
            $userModel = new \App\Models\User();
            $userModel->invalidateAccessLog($token);
        }

        // 3. LIMPIAR PHP: Ahora que la base de datos ya está enterada, 
        // borramos los datos del servidor
        unset($_SESSION['user']);
        unset($_SESSION['is_logged_in']);
        session_regenerate_id(true);

        // 4. NOTIFICAR: Usamos tu función para mandar el mensaje de éxito
        $this->sendAlert('info', __('logout_success'));
    }

    private function getUserIP() {
        // Si el sitio está detrás de un proxy (como Cloudflare o un Load Balancer)
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } 
        // Si la IP viene reenviada
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } 
        // La forma estándar
        else {
            return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
    }

    private function sendAlert($type, $message, $email = '', $clinicCode = '') {
        $_SESSION['message'] = [
            'type'    => $type,    // 'error', 'warning', 'success', 'info'
            'message' => $message
        ];
        
        $_SESSION['last_email'] = $email;
        $_SESSION['last_clinic_code'] = $clinicCode;
        
        header('Location: ' . URL_BASE . 'login');
        exit;
    }
}