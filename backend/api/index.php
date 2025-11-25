<?php
/**
 * Página principal de la API
 */

require_once __DIR__ . '/config.php';

sendResponse([
    'name' => 'Anita Integrales API',
    'version' => '1.0.0',
    'status' => 'active',
    'endpoints' => [
        'POST /auth/register.php' => 'Registrar nuevo usuario',
        'POST /auth/login.php' => 'Iniciar sesión',
        'POST /auth/logout.php' => 'Cerrar sesión',
        'GET /auth/verify.php' => 'Verificar token',
        'GET /orders.php?userId=X' => 'Obtener pedidos del usuario',
        'POST /orders.php' => 'Crear nuevo pedido',
        'GET /orders/list.php' => 'Listar pedidos (requiere autenticación)',
        'GET /orders/create.php' => 'Crear pedido (requiere autenticación)',
        'GET /products/list.php' => 'Listar productos',
        'GET /products/categories.php' => 'Listar categorías',
        'GET /products/get.php?id=xxx' => 'Obtener producto',
        'GET /cart/get.php' => 'Obtener carrito',
        'POST /cart/add.php' => 'Agregar al carrito',
        'GET /health.php' => 'Health check',
        'GET /test.php' => 'Endpoint de prueba'
    ],
    'documentation' => 'Consulta la documentación para más detalles'
]);
