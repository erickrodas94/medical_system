<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User {
    private $db;

    public function __construct() {
        // Obtenemos la instancia de conexión que configuramos en Database.php
        $this->db = Database::getConnection();
    }

    /**
     * Busca un usuario por su email
     */
    public function findByEmailAndClinic($email, $clinicId) {
        $sql = "SELECT u.*, 
                   r.name as role_name, 
                   r.permissions as role_permissions 
            FROM users u
            INNER JOIN roles r ON u.role_ID = r.ID
            WHERE u.email = :email 
              AND u.clinic_ID = :clinicId 
              AND u.deleted_at IS NULL 
              AND u.is_active = 1
              AND r.deleted_at IS NULL
              AND r.is_active = 1
            LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email, 'clinicId' => $clinicId]);
        
        return $stmt->fetch(); // Retorna el registro o false si no existe
    }

    public function invalidateAccessLog($sessionToken) {
        $sql = "UPDATE user_access_logs 
                SET is_valid = 0 
                WHERE session_token = :token 
                AND is_valid = 1"; // Solo actualizamos si aún está activa
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['token' => $sessionToken]);
    }

    /**
     * Obtiene los doctores activos de una clínica específica.
     * Excluye a personal administrativo que no atiende pacientes (is_doctor = false)
     */
    public function getDoctorsByClinic($clinicId) {
        $sql = "SELECT ID, first_name, last_name, email 
                FROM users 
                WHERE clinic_ID = :clinicId 
                  AND is_doctor = 1 
                  AND is_active = 1 
                  AND deleted_at IS NULL 
                ORDER BY first_name ASC, last_name ASC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['clinicId' => $clinicId]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}