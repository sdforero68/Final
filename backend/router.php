<?php
/**
 * Router para el servidor PHP built-in
 * Maneja todas las rutas y las redirige a api/
 */

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);

// Normalizar la ruta
$path = trim($path, '/');

// Remover 'api/' del inicio si está presente (para compatibilidad)
if (strpos($path, 'api/') === 0) {
    $path = substr($path, 4);
}

// Si la ruta es vacía, usar index.php
if (empty($path)) {
    $path = 'index.php';
}

// Construir la ruta completa al archivo en api/
$filePath = __DIR__ . '/api/' . $path;

// Si no tiene extensión y no es un directorio, agregar .php
if (!pathinfo($filePath, PATHINFO_EXTENSION) && !is_dir($filePath)) {
    $filePath .= '.php';
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
    'filePath' => $filePath,
    'exists' => file_exists($filePath),
    'message' => 'El endpoint solicitado no existe'
]);
return true;
