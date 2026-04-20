<?php
namespace App\Models;

class Background {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Obtiene el cuestionario completo con las respuestas del paciente
     */
    public function getPatientBackground($personId, $doctorId) {
        $sql = "SELECT q.*, r.switch_value, r.detail_text, r.is_applicable
                FROM doctor_background_questions q
                LEFT JOIN person_background_responses r 
                    ON q.ID = r.question_ID AND r.person_ID = :personId
                WHERE q.doctor_user_ID = :doctorId AND q.is_visible = 1
                ORDER BY q.category, q.sort_order";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['personId' => $personId, 'doctorId' => $doctorId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}