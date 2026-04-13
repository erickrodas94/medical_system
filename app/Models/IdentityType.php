<?php
namespace App\Models;

class IdentityType {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Obtener todos los tipos de identidad activos para un país específico
     */
    public function getByCountry($countryId) {
        $sql = "SELECT * FROM identity_types 
                WHERE country_ID = :countryId 
                AND deleted_at IS NULL 
                ORDER BY ID ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['countryId' => $countryId]);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}