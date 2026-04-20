<?php
namespace App\Models;

class Evolution {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * GET: Obtiene todas las evoluciones de un caso específico
     */
    public function getByCase($caseId) {
        $sql = "SELECT e.*, u.first_name as doc_fname, u.last_name as doc_lname, DATE(e.created_at) as consultation_date 
                FROM clinic_evolutions e
                LEFT JOIN users u ON e.doctor_ID = u.ID
                WHERE e.case_ID = :caseId AND e.deleted_at IS NULL
                ORDER BY e.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['caseId' => $caseId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * CREATE: Inserta una nueva evolución y registra en la bitácora
     */
    public function create($data) {
        try {
            // 1. Guardar la Evolución
            $sql = "INSERT INTO clinic_evolutions (
                        clinic_ID, person_ID, doctor_ID, case_ID, assistance_type,
                        evolution_notes, physical_exam_notes, patient_instructions, status
                    ) VALUES (
                        :clinic_id, :person_id, :doctor_id, :case_id, :assistance_type,
                        :notes, :physical, :instructions, :status
                    )";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'clinic_id'       => $data['clinic_id'],
                'person_id'       => $data['person_id'],
                'doctor_id'       => $data['doctor_id'],
                'case_id'         => $data['case_id'],
                'assistance_type' => $data['assistance_type'] ?? 'In-Person',
                'notes'           => trim($data['evolution_notes']),
                'physical'        => trim($data['physical_exam_notes']),
                'instructions'    => trim($data['patient_instructions']),
                'status'          => $data['status'] // 'Draft' o 'Finalized'
            ]);
            
            $evolutionId = $this->db->lastInsertId();

            // 2. Registro en Bitácora de Auditoría Global (saas_audit_log)
            $logSql = "INSERT INTO saas_audit_log (
                            clinic_ID, clinic_user_ID, affected_table, affected_record_ID, 
                            action, new_value, notes, ip_address
                       ) VALUES (
                            :clinic_id, :user_id, 'clinic_evolutions', :record_id, 
                            'INSERT', :new_value, 'Apertura de nota clínica', :ip
                       )";
            $logStmt = $this->db->prepare($logSql);
            $logStmt->execute([
                'clinic_id' => $data['clinic_id'],
                'user_id'   => $data['doctor_id'],
                'record_id' => $evolutionId,
                // Guardamos un JSON rápido con el estado inicial como snapshot
                'new_value' => json_encode(['status' => $data['status'], 'assistance_type' => $data['assistance_type'] ?? 'In-Person']),
                'ip'        => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
            ]);

            return ['success' => true, 'evolution_id' => $evolutionId];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * UPDATE: (Futura implementación para cuando editen un Borrador)
     */
    public function update($id, $data) {
        // Aquí irá tu lógica de UPDATE cuando hagamos la función de editar borrador
    }
}