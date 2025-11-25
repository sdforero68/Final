<?php
/**
 * Endpoint: POST /api/cart/clear.php
 * Vaciar completamente el carrito del usuario
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Método no permitido', 405);
}

$token = getAuthToken();
$user = validateToken($token);

if (!$user) {
    sendError('No autenticado', 401);
}

try {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$user['id']]);
    
    sendResponse([
        'success' => true,
        'message' => 'Carrito vaciado'
    ]);
    
} catch (Exception $e) {
    error_log("Error vaciando carrito: " . $e->getMessage());
    sendError('Error al vaciar carrito', 500);
}

