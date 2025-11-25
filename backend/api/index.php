<?php
/**
 * Router principal de la API
 * Este archivo maneja todas las rutas de la API
 */

// Incluir configuración
require_once __DIR__ . '/config.php';

// Obtener la ruta solicitada
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Remover query string y limpiar la ruta
$path = parse_url($requestUri, PHP_URL_PATH);
$path = trim($path, '/');

// Si es la ruta raíz o index.php, mostrar información
if (empty($path) || $path === 'index.php') {
    sendResponse([
        'success' => true,
        'message' => 'Anita Integrales API',
        'version' => '1.0',
        'status' => 'online',
        'endpoints' => [
            'test' => 'GET /test.php - Endpoint de diagnóstico',
            'auth' => [
                'POST /auth/register.php' => 'Registrar usuario',
                'POST /auth/login.php' => 'Iniciar sesión',
                'POST /auth/logout.php' => 'Cerrar sesión',
                'GET /auth/verify.php' => 'Verificar token'
            ],
            'products' => [
                'GET /products/list.php' => 'Listar productos',
                'GET /products/get.php?id=xxx' => 'Obtener producto',
                'GET /products/categories.php' => 'Listar categorías'
            ],
            'cart' => [
                'GET /cart/get.php' => 'Obtener carrito',
                'POST /cart/add.php' => 'Agregar producto',
                'PUT /cart/update.php' => 'Actualizar cantidad',
                'DELETE /cart/remove.php' => 'Eliminar producto',
                'DELETE /cart/clear.php' => 'Vaciar carrito'
            ],
            'orders' => [
                'POST /orders/create.php' => 'Crear pedido',
                'GET /orders/list.php' => 'Listar pedidos',
                'GET /orders/get.php?id=xxx' => 'Obtener pedido'
            ]
        ]
    ]);
}

// Si la ruta es test.php, ejecutarlo
if ($path === 'test.php') {
    if (file_exists(__DIR__ . '/test.php')) {
        require_once __DIR__ . '/test.php';
        exit;
    } else {
        sendError('test.php no encontrado', 404);
    }
}

// Router simple basado en la ruta
$pathParts = explode('/', $path);
$route = $pathParts[0] ?? '';
$subRoute = $pathParts[1] ?? '';

// Mapeo de rutas a archivos
$routes = [
    // Autenticación
    'auth' => [
        'register.php' => __DIR__ . '/auth/register.php',
        'login.php' => __DIR__ . '/auth/login.php',
        'logout.php' => __DIR__ . '/auth/logout.php',
        'verify.php' => __DIR__ . '/auth/verify.php'
    ],
    // Productos
    'products' => [
        'list.php' => __DIR__ . '/products/list.php',
        'get.php' => __DIR__ . '/products/get.php',
        'categories.php' => __DIR__ . '/products/categories.php'
    ],
    // Carrito
    'cart' => [
        'get.php' => __DIR__ . '/cart/get.php',
        'add.php' => __DIR__ . '/cart/add.php',
        'update.php' => __DIR__ . '/cart/update.php',
        'remove.php' => __DIR__ . '/cart/remove.php',
        'clear.php' => __DIR__ . '/cart/clear.php'
    ],
    // Pedidos
    'orders' => [
        'create.php' => __DIR__ . '/orders/create.php',
        'list.php' => __DIR__ . '/orders/list.php',
        'get.php' => __DIR__ . '/orders/get.php'
    ]
];

// Buscar y ejecutar el endpoint
if (isset($routes[$route][$subRoute])) {
    $file = $routes[$route][$subRoute];
    if (file_exists($file)) {
        require_once $file;
        exit;
    }
}

// Si la ruta no se encuentra, intentar acceso directo a archivos PHP
// Esto permite compatibilidad con las rutas antiguas
$directPath = __DIR__ . '/' . $path;
if (file_exists($directPath) && is_file($directPath)) {
    require_once $directPath;
    exit;
}

// Ruta no encontrada
sendError('Endpoint no encontrado: ' . $path, 404);
