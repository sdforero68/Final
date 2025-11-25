<?php
/**
 * Router para el servidor PHP built-in
 * Maneja las rutas /api/* y las redirige a la carpeta api/
 */

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);

// Debug: Log de la ruta (solo en desarrollo)
if (getenv('DEBUG') === 'true') {
    error_log("Router: Request URI: $requestUri, Path: $path");
}

// Si la ruta empieza con /api, servir desde la carpeta api/
if (strpos($path, '/api') === 0) {
    // Remover /api del inicio
    $apiPath = str_replace('/api', '', $path);
    $apiPath = ltrim($apiPath, '/');
    
    // Si está vacío, usar index.php
    if (empty($apiPath) || $apiPath === 'index.php') {
        $filePath = __DIR__ . '/api/index.php';
    } else {
        // Construir la ruta completa al archivo
        $filePath = __DIR__ . '/api/' . $apiPath;
        
        // Si no tiene extensión, agregar .php
        if (!pathinfo($filePath, PATHINFO_EXTENSION)) {
            $filePath .= '.php';
        }
    }
    
    // Debug
    if (getenv('DEBUG') === 'true') {
        error_log("Router: apiPath: $apiPath, filePath: $filePath, exists: " . (file_exists($filePath) ? 'yes' : 'no'));
    }
    
    // Si el archivo existe, servirlo
    if (file_exists($filePath) && is_file($filePath)) {
        require $filePath;
        return true;
    }
}

// Si no se encuentra, devolver 404
http_response_code(404);
header('Content-Type: application/json');
echo json_encode([
    'error' => 'Not Found',
    'path' => $path,
    'apiPath' => $apiPath ?? '',
    'filePath' => $filePath ?? '',
    'message' => 'El endpoint solicitado no existe'
]);
return true;
