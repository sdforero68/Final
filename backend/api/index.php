<?php
/**
 * Página principal de la API
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'success' => true,
    'message' => 'Anita Integrales API',
    'version' => '1.0',
    'status' => 'online',
    'endpoints' => [
        'test' => 'GET /test.php - Endpoint de diagnóstico',
        'products' => [
            'GET /products/list.php' => 'Listar productos',
            'GET /products/categories.php' => 'Listar categorías',
            'GET /products/get.php?id=xxx' => 'Obtener producto'
        ],
        'auth' => [
            'POST /auth/register.php' => 'Registrar usuario',
            'POST /auth/login.php' => 'Iniciar sesión'
        ]
    ]
], JSON_PRETTY_PRINT);
