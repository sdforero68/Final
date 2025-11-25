<?php
/**
 * Endpoint de salud - Verifica que el servidor funciona
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'status' => 'ok',
    'message' => 'Servidor funcionando',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION
]);

