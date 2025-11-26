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
    'https://final-1-0wvc.onrender.com',  // Backend en Render
];

// Permitir el origen si está en la lista o si es desarrollo
$allowedOrigin = in_array($origin, $allowedOrigins) ? $origin : '*';

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
// Intentar múltiples ubicaciones posibles
$dbFiles = [
    __DIR__ . '/database.php',           // api/database.php (prioridad 1)
    __DIR__ . '/../config/database.php', // config/database.php (prioridad 2)
];

$dbFile = null;
foreach ($dbFiles as $file) {
    if (file_exists($file)) {
        $dbFile = $file;
        break;
    }
}

// Cargar database.php si existe
if ($dbFile) {
    require_once $dbFile;
} else {
    // Si no existe el archivo, verificar si hay variables de entorno
    $hasEnvVars = getenv('DB_HOST') || getenv('DATABASE_URL') || getenv('DATABASE_HOST');
    
    if ($hasEnvVars) {
        // Si hay variables de entorno, cargar database.php desde api/
        // Si no existe, la clase Database debería manejar variables de entorno
        if (file_exists(__DIR__ . '/database.php')) {
            require_once __DIR__ . '/database.php';
        } else {
            // Si no existe database.php, definir la clase Database aquí
            if (!class_exists('Database')) {
                require_once __DIR__ . '/../config/database.php';
            }
        }
    } else {
        // Solo fallar si no hay variables de entorno ni archivo
        error_log("ERROR: database.php no encontrado y no hay variables de entorno configuradas");
        error_log("Buscado en: " . implode(', ', $dbFiles));
        http_response_code(500);
        echo json_encode([
            'error' => 'Error de configuración del servidor',
            'message' => 'No se encontró database.php ni variables de entorno de base de datos configuradas',
            'hint' => 'Configura las variables de entorno DB_HOST, DB_NAME, DB_USER, DB_PASSWORD en Render',
            'debug' => [
                'files_checked' => $dbFiles,
                'env_vars' => [
                    'DB_HOST' => getenv('DB_HOST') ? 'SET' : 'NOT SET',
                    'DATABASE_URL' => getenv('DATABASE_URL') ? 'SET' : 'NOT SET',
                ]
            ]
        ]);
        exit();
    }
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
