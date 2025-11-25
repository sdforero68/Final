<?php
/**
 * Endpoint: GET /api/auth/verify.php
 * Verificar si el token es válido y obtener información del usuario
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Método no permitido', 405);
}

$token = getAuthToken();

if (!$token) {
    sendError('Token requerido', 401);
}

$user = validateToken($token);

if (!$user) {
    sendError('Token inválido o expirado', 401);
}

sendResponse([
    'success' => true,
    'data' => [
        'user' => [
            'id' => (string)$user['id'],
            'email' => $user['email'],
            'user_metadata' => [
                'name' => $user['name'],
                'phone' => $user['phone']
            ]
        ]
    ]
]);

