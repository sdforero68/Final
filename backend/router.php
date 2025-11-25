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
    
    // Si está vacío o es solo index.php, usar index.php de api
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
    
    // Si el archivo existe, servirlo
    if (file_exists($filePath) && is_file($filePath)) {
        // NO cambiar el directorio de trabajo, solo incluir el archivo
        require $filePath;
        return true;
    }
}

// Si no se encuentra, devolver 404
http_response_code(404);
header('Content-Type: application/json');
echo json_encode(['error' => 'Not Found', 'path' => $path]);
return true;
