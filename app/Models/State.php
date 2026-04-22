<?php
namespace App\Models;

class State {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getByCountry($countryId) {
        // Asumiendo que tu tabla se llama 'states' y tiene las columnas ID, name y country_ID
        $sql = "SELECT ID, name FROM states WHERE country_ID = :country_id ORDER BY name ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['country_id' => $countryId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}