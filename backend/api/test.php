<?php
/**
 * Endpoint de prueba para verificar que el backend funciona
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

try {
    // Verificar que database.php existe
    $dbFile = __DIR__ . '/database.php';
    $dbExists = file_exists($dbFile);
    
    // Intentar cargar database.php
    if ($dbExists) {
        require_once $dbFile;
    }
    
    // Verificar variables de entorno
    $envVars = [
        'DB_HOST' => getenv('DB_HOST'),
        'DB_PORT' => getenv('DB_PORT'),
        'DB_NAME' => getenv('DB_NAME'),
        'DB_USER' => getenv('DB_USER'),
        'DB_PASSWORD' => getenv('DB_PASSWORD') ? '***' : null,
    ];
    
    // Intentar conectar a la base de datos
    $dbConnected = false;
    $dbError = null;
    if ($dbExists) {
        try {
            $db = Database::getInstance()->getConnection();
            $dbConnected = true;
        } catch (Exception $e) {
            $dbError = $e->getMessage();
        }
    }
    
    echo json_encode([
        'success' => true,
        'backend' => 'OK',
        'database_file_exists' => $dbExists,
        'database_file_path' => $dbFile,
        'environment_variables' => $envVars,
        'database_connected' => $dbConnected,
        'database_error' => $dbError,
        'php_version' => PHP_VERSION,
        'working_directory' => __DIR__,
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);
}

