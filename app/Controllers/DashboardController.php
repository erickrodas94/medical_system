<?php
namespace App\Controllers;

class DashboardController {

    private $db;

    // 1. Recibimos la base de datos al crear el Router
    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    public function index() {
        if (!isset($_SESSION['user']['id'])) {
            header('Location: ' . URL_BASE . 'login');
            exit;
        }

        // Cargamos la vista
        require_once '../views/dashboard/index.php';
    }
}