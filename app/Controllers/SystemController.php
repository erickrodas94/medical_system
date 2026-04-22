<?php
namespace App\Controllers;

use App\Models\IdentityType;

class SystemController {
    
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Endpoint AJAX para obtener los documentos según el país
     */
    public function getIdentityTypesByCountry() {
        // Validación de seguridad básica para endpoints AJAX
        if (!isset($_SESSION['user']['id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $countryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($countryId > 0) {
            $identityModel = new IdentityType($this->db);
            $types = $identityModel->getByCountry($countryId);

            // Traducimos cada tipo antes de enviarlo
            foreach ($types as &$type) {
                // Reemplazamos la llave por el valor real del diccionario
                $type['label_translated'] = __($type['label_key']);
            }
            
            // Devolvemos el array en formato JSON para que JavaScript lo dibuje
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($types);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'ID de país inválido']);
        }
        exit;
    }
}