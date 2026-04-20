<?php
namespace App\Models;

class PatientVital {
    
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Guarda una nueva toma de signos vitales
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO patient_vitals (
                        clinic_ID, patient_ID, case_ID, taken_by_user_ID,
                        blood_pressure_sys, blood_pressure_dia, heart_rate, 
                        oxygen_saturation, temperature_value, temperature_unit,
                        weight_value, weight_unit, height_value, height_unit,
                        clinical_notes
                    ) VALUES (
                        :clinic_id, :patient_id, :case_id, :user_id,
                        :bp_sys, :bp_dia, :hr, :spo2,
                        :temp_val, :temp_unit, :weight_val, :weight_unit,
                        :height_val, :height_unit, :notes
                    )";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'clinic_id'   => $data['clinic_id'],
                'patient_id'  => $data['patient_id'],
                'case_id'     => $data['case_id'],
                'user_id'     => $data['user_id'],
                
                // Usamos null si vienen vacíos del formulario
                'bp_sys'      => !empty($data['bp_sys']) ? $data['bp_sys'] : null,
                'bp_dia'      => !empty($data['bp_dia']) ? $data['bp_dia'] : null,
                'hr'          => !empty($data['heart_rate']) ? $data['heart_rate'] : null,
                'spo2'        => !empty($data['oxygen_saturation']) ? $data['oxygen_saturation'] : null,
                
                'temp_val'    => !empty($data['temperature_value']) ? $data['temperature_value'] : null,
                'temp_unit'   => $data['temperature_unit'],
                
                'weight_val'  => !empty($data['weight_value']) ? $data['weight_value'] : null,
                'weight_unit' => $data['weight_unit'],
                
                'height_val'  => !empty($data['height_value']) ? $data['height_value'] : null,
                'height_unit' => $data['height_unit'],
                
                'notes'       => trim($data['clinical_notes'])
            ]);
            
            return ['success' => true];
            
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}