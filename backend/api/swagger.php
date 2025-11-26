<?php
/**
 * Endpoint: GET /api/swagger.php
 * Genera la especificación OpenAPI/Swagger de la API
 */

// Suprimir warnings y notices para limpiar el JSON
error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 0);

require_once __DIR__ . '/../vendor/autoload.php';

use OpenApi\Generator;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Intentar primero usar la especificación manual (más confiable)
    // Si existe swagger-manual.php, usarlo; si no, intentar escanear automáticamente
    $manualSpecFile = __DIR__ . '/swagger-manual.php';
    
    if (file_exists($manualSpecFile)) {
        // Usar especificación manual - más confiable y completa
        require_once $manualSpecFile;
        return;
    }
    
    // Fallback: Intentar escanear automáticamente (puede no funcionar correctamente)
    $apiPath = __DIR__;
    
    // Incluir el archivo base de OpenAPI antes de escanear
    require_once $apiPath . '/openapi.php';
    
    // Generar la especificación OpenAPI
    $openapi = Generator::scan([$apiPath], [
        'exclude' => [
            'vendor',
            'config'
        ]
    ]);
    
    // Convertir a array para poder modificar si es necesario
    $spec = json_decode($openapi->toJson(), true);
    
    // Si no se encontró información, agregar valores por defecto
    if (!isset($spec['info'])) {
        $spec['info'] = [
            'version' => '1.0.0',
            'title' => 'Anita Integrales API',
            'description' => 'API RESTful para el e-commerce de productos artesanales e integrales'
        ];
    }
    
    // Agregar información del servidor si no está en las anotaciones
    if (!isset($spec['servers']) || empty($spec['servers'])) {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
        $baseUrl = $protocol . '://' . $host . '/api';
        
        $spec['servers'] = [
            [
                'url' => $baseUrl,
                'description' => 'Servidor de desarrollo'
            ],
            [
                'url' => 'https://final-1-0wvc.onrender.com/api',
                'description' => 'Servidor de producción (Render)'
            ]
        ];
    }
    
    echo json_encode($spec, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error generando documentación Swagger',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
}

