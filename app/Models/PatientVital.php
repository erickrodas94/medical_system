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
            $sql = "INSERT INTO clinic_triage (
                        person_ID, evolution_ID, created_by_user_ID,
                        weight_value, weight_unit, height_value, height_unit,
                        temperature_value, temperature_unit,
                        systolic_bp, diastolic_bp, heart_rate_bpm, 
                        respiratory_rate_rpm, oxygen_saturation_pct
                    ) VALUES (
                        :person_id, :evolution_id, :user_id,
                        :weight_val, :weight_unit, :height_val, :height_unit,
                        :temp_val, :temp_unit,
                        :sys, :dia, :hr, :rr, :spo2
                    )";
                    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'person_id'    => $data['person_id'], // Este debe ser el clinic_patient_ID 
                'evolution_id' => $data['evolution_id'] ?? null,
                'user_id'      => $data['user_id'],
                'weight_val'   => !empty($data['weight_value']) ? $data['weight_value'] : null,
                'weight_unit'  => $data['weight_unit'] ?? 'kg',
                'height_val'   => !empty($data['height_value']) ? $data['height_value'] : null,
                'height_unit'  => $data['height_unit'] ?? 'cm',
                'temp_val'     => !empty($data['temperature_value']) ? $data['temperature_value'] : null,
                'temp_unit'    => $data['temperature_unit'] ?? 'C',
                'sys'          => !empty($data['systolic_bp']) ? $data['systolic_bp'] : null,
                'dia'          => !empty($data['diastolic_bp']) ? $data['diastolic_bp'] : null,
                'hr'           => !empty($data['heart_rate_bpm']) ? $data['heart_rate_bpm'] : null,
                'rr'           => !empty($data['respiratory_rate_rpm']) ? $data['respiratory_rate_rpm'] : null,
                'spo2'         => !empty($data['oxygen_saturation_pct']) ? $data['oxygen_saturation_pct'] : null
            ]);
            
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}