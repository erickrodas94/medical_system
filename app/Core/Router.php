<?php
namespace App\Core;

class Router {
    protected $routes = [];
    protected $db;

    public function __construct($dbConnection = null) {
        $this->db = $dbConnection;
    }

    public function get($uri, $controller) {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller) {
        $this->routes['POST'][$uri] = $controller;
    }

    public function run() {
        $uri = isset($_GET['url']) ? trim($_GET['url'], '/') : '/';
        $method = $_SERVER['REQUEST_METHOD'];

        // 1. Primero buscamos si es una ruta exacta (ej. 'pacientes')
        if (isset($this->routes[$method][$uri])) {
            $this->callAction($this->routes[$method][$uri]);
            return;
        }

        // 2. Si no es exacta, buscamos si es una ruta dinámica (ej. 'pacientes/ver/{id}')
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $handler) {
                // Convertimos {id} en un comodín para números o letras
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route);
                $pattern = "@^" . $pattern . "$@";
                
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // Quitamos la coincidencia completa
                    $this->callAction($handler, $matches); // Pasamos el ID al controlador
                    return;
                }
            }
        }

        // 3. // SI LLEGA AQUÍ, NO HUBO COINCIDENCIA -> MOSTRAR 404
        http_response_code(404);
        require_once '../views/errors/404.php';
        exit;
    }

    private function callAction($handler, $params = []) {
        list($controller, $method) = explode('@', $handler);
        $controller = "App\\Controllers\\" . $controller;
        $controllerInstance = new $controller($this->db);
        
        // Ejecutamos el método y le inyectamos los parámetros (ej. el ID)
        call_user_func_array([$controllerInstance, $method], $params);
    }
}