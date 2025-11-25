<?php
/**
 * Configuración general de la API
 */

// Headers CORS para permitir requests desde el frontend
// Detectar origen del request
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = [
    'http://localhost:8000',  // Desarrollo local
    'http://localhost',       // Desarrollo local alternativo
    'https://sdforero68.github.io',  // GitHub Pages
    'https://sdforero68.github.io/Final',  // GitHub Pages con subdirectorio
];

// Permitir el origen si está en la lista o si es desarrollo
$allowedOrigin = in_array($origin, $allowedOrigins) ? $origin : '*';

// En producción, puedes ser más restrictivo:
// $allowedOrigin = in_array($origin, $allowedOrigins) ? $origin : '';

header("Access-Control-Allow-Origin: $allowedOrigin");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Zona horaria
date_default_timezone_set('America/Bogota');

// Incluir configuración de base de datos
// SOLUCIÓN DEFINITIVA: Buscar database.php en múltiples ubicaciones posibles
$dbPaths = [
    __DIR__ . '/database.php',              // En api/ (preferido para Docker)
    __DIR__ . '/../config/database.php',    // En config/ (desarrollo local)
    dirname(__DIR__) . '/config/database.php' // Alternativa
];

$dbLoaded = false;
foreach ($dbPaths as $dbPath) {
    if (file_exists($dbPath)) {
        require_once $dbPath;
        $dbLoaded = true;
        break;
    }
}

if (!$dbLoaded) {
    // Si no se encuentra, intentar error más descriptivo
    $errorMsg = "ERROR CRITICO: database.php no encontrado. Buscado en:\n";
    foreach ($dbPaths as $path) {
        $errorMsg .= "  - " . $path . " (" . (file_exists($path) ? "EXISTE" : "NO EXISTE") . ")\n";
    }
    $errorMsg .= "\n__DIR__ = " . __DIR__ . "\n";
    $errorMsg .= "Estructura actual:\n";
    if (is_dir(__DIR__)) {
        $errorMsg .= "  Directorios en " . __DIR__ . ":\n";
        foreach (scandir(__DIR__) as $item) {
            if (is_file(__DIR__ . '/' . $item)) {
                $errorMsg .= "    - " . $item . "\n";
            }
        }
    }
    error_log($errorMsg);
    throw new Exception("No se pudo encontrar database.php en ninguna ubicación. Ver logs para detalles.");
}

// Función para enviar respuestas JSON
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit();
}

// Función para enviar errores
function sendError($message, $statusCode = 400) {
    sendResponse([
        'success' => false,
        'error' => $message
    ], $statusCode);
}

// Función para validar token de sesión
function validateToken($token) {
    if (!$token) {
        return null;
    }
    
    try {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT u.*, s.token 
            FROM sessions s
            INNER JOIN users u ON s.user_id = u.id
            WHERE s.token = ? AND s.expires_at > NOW()
        ");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            unset($user['password']); // No devolver la contraseña
            return $user;
        }
        
        return null;
    } catch (Exception $e) {
        error_log("Error validando token: " . $e->getMessage());
        return null;
    }
}

// Función para obtener token del header Authorization
function getAuthToken() {
    $headers = getallheaders();
    
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
    }
    
    // También buscar en parámetros GET/POST por compatibilidad
    return $_GET['token'] ?? $_POST['token'] ?? null;
}
