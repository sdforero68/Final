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
$path = str_replace('/api', '', $path); // Remover /api si está presente
$path = trim($path, '/');
$pathParts = explode('/', $path);

// Si no hay ruta, mostrar información de la API
if (empty($path) || $path === 'index.php') {
    sendResponse([
        'success' => true,
        'message' => 'Anita Integrales API',
        'version' => '1.0',
        'endpoints' => [
            'auth' => [
                'POST /api/auth/register.php' => 'Registrar usuario',
                'POST /api/auth/login.php' => 'Iniciar sesión',
                'POST /api/auth/logout.php' => 'Cerrar sesión',
                'GET /api/auth/verify.php' => 'Verificar token'
            ],
            'products' => [
                'GET /api/products/list.php' => 'Listar productos',
                'GET /api/products/get.php?id=xxx' => 'Obtener producto',
                'GET /api/products/categories.php' => 'Listar categorías'
            ],
            'cart' => [
                'GET /api/cart/get.php' => 'Obtener carrito',
                'POST /api/cart/add.php' => 'Agregar producto',
                'PUT /api/cart/update.php' => 'Actualizar cantidad',
                'DELETE /api/cart/remove.php' => 'Eliminar producto',
                'DELETE /api/cart/clear.php' => 'Vaciar carrito'
            ],
            'orders' => [
                'POST /api/orders/create.php' => 'Crear pedido',
                'GET /api/orders/list.php' => 'Listar pedidos',
                'GET /api/orders/get.php?id=xxx' => 'Obtener pedido'
            ]
        ]
    ]);
}

// Router simple basado en la ruta
$route = $pathParts[0] ?? '';
$subRoute = $pathParts[1] ?? '';

// Mapeo de rutas a archivos
$routes = [
    // Autenticación
    'auth' => [
        'register' => __DIR__ . '/auth/register.php',
        'login' => __DIR__ . '/auth/login.php',
        'logout' => __DIR__ . '/auth/logout.php',
        'verify' => __DIR__ . '/auth/verify.php'
    ],
    // Productos
    'products' => [
        'list' => __DIR__ . '/products/list.php',
        'get' => __DIR__ . '/products/get.php',
        'categories' => __DIR__ . '/products/categories.php'
    ],
    // Carrito
    'cart' => [
        'get' => __DIR__ . '/cart/get.php',
        'add' => __DIR__ . '/cart/add.php',
        'update' => __DIR__ . '/cart/update.php',
        'remove' => __DIR__ . '/cart/remove.php',
        'clear' => __DIR__ . '/cart/clear.php'
    ],
    // Pedidos
    'orders' => [
        'create' => __DIR__ . '/orders/create.php',
        'list' => __DIR__ . '/orders/list.php',
        'get' => __DIR__ . '/orders/get.php'
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
$directPath = __DIR__ . '/' . $path . '.php';
if (file_exists($directPath)) {
    require_once $directPath;
    exit;
}

// Ruta no encontrada
sendError('Endpoint no encontrado', 404);

