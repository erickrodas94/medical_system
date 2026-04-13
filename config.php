<?php
// Configuración de la Base de Datos
define('DB_HOST', 'localhost:3308');
define('DB_NAME', 'db_medical_system');
define('DB_USER', 'root');
define('DB_PASS', ''); // Tu contraseña de MySQL

// URL Base (Ajusta según tu carpeta local)
define('URL_BASE', 'http://localhost:8080/medical_system/');

// ==========================================
// NUEVO: CREACIÓN DE LA CONEXIÓN PDO
// ==========================================
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    
    // Configuramos PDO para que nos avise si hay errores en el SQL
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Hacemos que por defecto nos devuelva arrays asociativos
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Si la base de datos está apagada o la clave es incorrecta, se detiene todo
    die("Error crítico de conexión a la BD: " . $e->getMessage());
}
// ==========================================

// Configuración de Sesiones
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Detectar idioma (Prioridad: Sesión > Navegador > Por defecto)
if (!isset($_SESSION['lang'])) {
    $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'es', 0, 2);
    $_SESSION['lang'] = in_array($browser_lang, ['en', 'es']) ? $browser_lang : 'es';
}

// 2. Cargar el archivo de traducción
$texts = require_once __DIR__ . '/lang/' . $_SESSION['lang'] . '.php';

// 3. Función global para traducir de forma fácil
function __($key) {
    global $texts;
    return $texts[$key] ?? $key;
}

if (isset($_SESSION['clinic']['timezone'])) {
    date_default_timezone_set($_SESSION['clinic']['timezone']);
} else {
    date_default_timezone_set('America/Guatemala'); // Por defecto
}