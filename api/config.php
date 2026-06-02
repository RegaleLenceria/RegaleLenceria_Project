<?php
/**
 * api/config.php
 * Configuración de conexión a la base de datos
 * Las credenciales leen desde variables de entorno (Docker)
 * con fallback a valores locales de desarrollo
 */

define('DB_HOST',     getenv('DB_HOST')     ?: 'db');
define('DB_USER',     getenv('DB_USER')     ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '8825358Nsu');
define('DB_NAME',     getenv('DB_NAME')     ?: 'regalele_base');
define('DB_PORT',     getenv('DB_PORT')     ?: 3306);

// URL base para las imágenes (upload/)
define('UPLOAD_BASE', getenv('UPLOAD_BASE') ?: 'http://localhost:8081/upload');

function getDB(): mysqli {
    static $conn = null;
    if ($conn !== null) return $conn;

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, (int)DB_PORT);

    if ($conn->connect_error) {
        http_response_code(503);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
        exit;
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}

// Headers CORS para que React (Vite dev :5173) pueda llamar a la API
function setCORSHeaders(): void {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    header('Content-Type: application/json; charset=utf-8');

    // Preflight
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}
