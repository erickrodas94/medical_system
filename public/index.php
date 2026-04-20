<?php
// 1. Cargar configuración base
require_once '../config.php';

// 2. Cargar nuestras funciones de ayuda
require_once '../app/Helpers/helper.php';

spl_autoload_register(function ($class) {
    $file = '../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Router;

$router = new Router($pdo);

// Rutas del Sistema / AJAX
$router->get('documentos/por-pais', 'SystemController@getIdentityTypesByCountry');

// Rutas de Autenticación
$router->get('login', 'AuthController@showLogin');
$router->post('login', 'AuthController@login');
$router->get('logout', 'AuthController@logout');

// Rutas del Sistema
$router->get('/', 'DashboardController@index'); // Para la raíz con /
$router->get('dashboard', 'DashboardController@index'); // Para /dashboard

// Rutas de Pacientes
$router->get('pacientes', 'PatientController@index');
$router->post('pacientes/guardar', 'PatientController@store');
$router->get('pacientes/buscar', 'PatientController@search');
$router->get('pacientes/ver/{id}', 'PatientController@show');

// Edición de Datos Maestros
$router->get('pacientes/editar/{id}', 'PatientController@edit');
$router->post('pacientes/actualizar', 'PatientController@update');

// Rutas de Casos Clínicos
$router->post('pacientes/caso/guardar', 'PatientController@storeCase');
$router->post('pacientes/triage/guardar', 'PatientController@storeTriage');
$router->post('pacientes/evolucion/guardar', 'PatientController@storeEvolution');
$router->post('pacientes/receta/guardar', 'PatientController@storePrescription');

// Flujo Unificado de Consulta (Modo Enfoque)
$router->get('pacientes/consulta/{id}', 'PatientController@newConsultation');
$router->post('pacientes/consulta/guardar', 'PatientController@storeConsultation');

// Arrancar el sistema
$router->run();