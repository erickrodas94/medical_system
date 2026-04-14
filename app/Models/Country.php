<?php
namespace App\Models;

class Country {
    
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Obtiene todos los países ordenados alfabéticamente
     */
    public function getAll() {
        $sql = "SELECT ID, iso_code FROM countries ORDER BY iso_code ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}