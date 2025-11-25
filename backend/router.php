<?php
/**
 * Router para el servidor PHP built-in
 * Redirige las rutas /api/* a la carpeta api/
 */

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Si la ruta empieza con /api, servir desde la carpeta api/
if (strpos($path, '/api') === 0) {
    // Remover /api del inicio y limpiar
    $apiPath = str_replace('/api', '', $path);
    $apiPath = ltrim($apiPath, '/');
    
    // Construir la ruta completa al archivo
    $filePath = __DIR__ . '/api/' . $apiPath;
    
    // Si no tiene extensión, agregar .php
    if (!pathinfo($filePath, PATHINFO_EXTENSION)) {
        $filePath .= '.php';
    }
    
    // Si el archivo existe, servirlo
    if (file_exists($filePath) && is_file($filePath)) {
        $_SERVER['SCRIPT_NAME'] = '/api/' . $apiPath;
        $_SERVER['PHP_SELF'] = '/api/' . $apiPath;
        chdir(__DIR__ . '/api');
        require $filePath;
        return true;
    }
}

// Si no se encuentra, devolver 404
http_response_code(404);
header('Content-Type: application/json');
echo json_encode(['error' => 'Not Found', 'path' => $path]);
return true;
