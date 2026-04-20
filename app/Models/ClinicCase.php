<?php
namespace App\Models;

class ClinicCase {
    
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Obtiene todos los casos clínicos de una persona específica en una clínica
     */
    public function getByPerson($personId, $clinicId) {
        $sql = "SELECT cc.*, u.first_name as doc_fname, u.last_name as doc_lname 
                FROM clinic_cases cc
                LEFT JOIN users u ON cc.doctor_ID = u.ID
                WHERE cc.person_ID = :personId 
                  AND cc.clinic_ID = :clinicId 
                  AND cc.deleted_at IS NULL
                ORDER BY cc.status ASC, cc.created_at DESC"; 
                // Ordena primero los 'Open', luego por fecha
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'personId' => $personId,
            'clinicId' => $clinicId
        ]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo caso clínico
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO clinic_cases (
                        clinic_ID, person_ID, doctor_ID, 
                        title, initial_reason, status, opened_at
                    ) VALUES (
                        :clinic_id, :person_id, :doctor_id, 
                        :title, :initial_reason, 'Open', CURDATE()
                    )";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'clinic_id'      => $data['clinic_id'],
                'person_id'      => $data['person_id'],
                'doctor_id'      => $data['doctor_id'],
                'title'          => trim($data['title']),
                'initial_reason' => trim($data['initial_reason'])
            ]);
            
            return ['success' => true, 'case_id' => $this->db->lastInsertId()];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}