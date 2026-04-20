<?php
namespace App\Models;

class Triage {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function create($data) {
        try {
            $sql = "INSERT INTO clinic_triage (
                        person_ID, created_by_user_ID, 
                        weight_value, weight_unit, height_value, height_unit,
                        temperature_value, temperature_unit,
                        systolic_bp, diastolic_bp, heart_rate_bpm, oxygen_saturation_pct
                    ) VALUES (
                        :person_id, :user_id,
                        :w_val, :w_unit, :h_val, :h_unit,
                        :t_val, :t_unit,
                        :sys, :dia, :hr, :spo2
                    )";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'person_id' => $data['person_id'],
                'user_id'   => $data['user_id'],
                'w_val'     => !empty($data['weight_value']) ? $data['weight_value'] : null,
                'w_unit'    => $data['weight_unit'],
                'h_val'     => !empty($data['height_value']) ? $data['height_value'] : null,
                'h_unit'    => $data['height_unit'],
                't_val'     => !empty($data['temperature_value']) ? $data['temperature_value'] : null,
                't_unit'    => $data['temperature_unit'],
                'sys'       => !empty($data['systolic_bp']) ? $data['systolic_bp'] : null,
                'dia'       => !empty($data['diastolic_bp']) ? $data['diastolic_bp'] : null,
                'hr'        => !empty($data['heart_rate_bpm']) ? $data['heart_rate_bpm'] : null,
                'spo2'      => !empty($data['oxygen_saturation_pct']) ? $data['oxygen_saturation_pct'] : null
            ]);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}