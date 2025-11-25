<?php
/**
 * Router para el servidor PHP built-in
 * Maneja todas las rutas y las redirige a api/
 */

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Si la ruta es / o /index.php, mostrar información de la API
if ($path === '/' || $path === '/index.php') {
    $indexFile = __DIR__ . '/api/index.php';
    if (file_exists($indexFile)) {
        require $indexFile;
        return true;
    }
}

// Todas las demás rutas van a api/
// Remover la barra inicial
$apiPath = ltrim($path, '/');

// Si está vacío, usar index.php
if (empty($apiPath)) {
    $filePath = __DIR__ . '/api/index.php';
} else {
    // Construir la ruta completa al archivo en api/
    $filePath = __DIR__ . '/api/' . $apiPath;
    
    // Si no tiene extensión y no es un directorio, agregar .php
    if (!pathinfo($filePath, PATHINFO_EXTENSION) && !is_dir($filePath)) {
        $filePath .= '.php';
    }
}

// Si el archivo existe, servirlo
if (file_exists($filePath) && is_file($filePath)) {
    require $filePath;
    return true;
}

// Si no se encuentra, devolver 404
http_response_code(404);
header('Content-Type: application/json');
echo json_encode([
    'error' => 'Not Found',
    'path' => $path,
    'message' => 'El endpoint solicitado no existe'
]);
return true;
