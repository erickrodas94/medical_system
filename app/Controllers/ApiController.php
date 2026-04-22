<?php
namespace App\Controllers;

use App\Models\State;

class ApiController {
    
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function getStates() {
        // Configurar el encabezado para que el navegador sepa que es JSON
        header('Content-Type: application/json; charset=utf-8');
        
        $countryId = $_GET['country_id'] ?? 0;
        
        if (empty($countryId)) {
            echo json_encode([]); 
            return;
        }

        $stateModel = new State($this->db);
        $states = $stateModel->getByCountry($countryId);
        
        echo json_encode($states);
    }
}