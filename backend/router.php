<?php
/**
 * Router para el servidor PHP built-in
 * Maneja todas las rutas y las redirige a api/
 */

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);

// Normalizar la ruta
$path = trim($path, '/');

// Si la ruta empieza con 'api/', removerlo (ya estamos en el contexto correcto)
if (strpos($path, 'api/') === 0) {
    $path = substr($path, 4);
}

// Si la ruta está vacía después de remover 'api/', usar index.php
if (empty($path)) {
    $path = 'index.php';
}

// Construir la ruta completa al archivo en api/
// $path ya no tiene 'api/' al inicio (lo removimos arriba)
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
