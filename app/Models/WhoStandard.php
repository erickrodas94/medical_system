<?php
namespace App\Models;

class WhoStandard {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Trae los 60 meses continuos para dibujar las líneas de fondo
    public function getCurves($gender, $metricType) {
        $sql = "SELECT age_months, P3, P15, P50, P85, P97 
                FROM who_standards 
                WHERE gender = :gender AND metric_type = :metric 
                ORDER BY age_months ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':gender' => $gender, ':metric' => $metricType]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}