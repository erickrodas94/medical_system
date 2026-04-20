<?php
namespace App\Models;

class Prescription {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function create($data) {
        try {
            // Generamos un código de receta único (Ej: REC-2026-X8B9)
            $prescCode = 'REC-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4));

            // 1. Guardar la Cabecera de la Receta
            $sql = "INSERT INTO evolution_prescriptions (
                        evolution_ID, doctor_user_ID, prescription_code, general_instructions, include_digital_signature
                    ) VALUES (
                        :evo_id, :doc_id, :code, :instructions, 1
                    )";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'evo_id'       => $data['evolution_id'],
                'doc_id'       => $data['doctor_id'],
                'code'         => $prescCode,
                'instructions' => trim($data['general_instructions'] ?? '')
            ]);
            
            $prescriptionId = $this->db->lastInsertId();

            // 2. Guardar los Medicamentos Dinámicos
            if (!empty($data['medications']) && is_array($data['medications'])) {
                $sqlDet = "INSERT INTO prescription_details (
                                prescription_ID, medication_name, dosage, frequency, duration, total_quantity, additional_notes
                           ) VALUES (
                                :pid, :name, :dose, :freq, :dur, :qty, :notes
                           )";
                $stmtDet = $this->db->prepare($sqlDet);

                foreach ($data['medications'] as $med) {
                    if (!empty(trim($med['name'] ?? ''))) {
                        $stmtDet->execute([
                            'pid'   => $prescriptionId,
                            'name'  => trim($med['name']),
                            'dose'  => trim($med['dosage'] ?? ''),
                            'freq'  => trim($med['frequency'] ?? ''),
                            'dur'   => trim($med['duration'] ?? ''),
                            'qty'   => trim($med['total_quantity'] ?? ''),
                            'notes' => trim($med['additional_notes'] ?? '')
                        ]);
                    }
                }
            }

            return ['success' => true, 'prescription_id' => $prescriptionId];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}