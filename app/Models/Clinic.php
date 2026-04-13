<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Clinic {
    private $db;

    public function __construct() {
        // Obtenemos la instancia de conexión que configuramos en Database.php
        $this->db = Database::getConnection();
    }

    /**
     * Busca un cliente por su código
     */
    public function findByCode($clinicCode) {
        // Usamos :clinicCode para vincular el valor de forma segura
        $sql = "SELECT * FROM clinics WHERE client_code = :clinicCode AND deleted_at IS NULL LIMIT 1";
        
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['clinicCode' => $clinicCode]);
            
            // Retorna el array con los datos o FALSE si no hay coincidencia
            return $stmt->fetch(); 
        } catch (\PDOException $e) {
            // Log del error para el desarrollador (tú)
            error_log("Error en ClinicModel::findByCode: " . $e->getMessage());
            return false;
        }
    }
}