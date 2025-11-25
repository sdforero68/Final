<?php
/**
 * Endpoint: DELETE /api/cart/remove.php?product_id=xxx
 * Eliminar un producto del carrito
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    sendError('Método no permitido', 405);
}

$token = getAuthToken();
$user = validateToken($token);

if (!$user) {
    sendError('No autenticado', 401);
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = $_GET['product_id'] ?? $input['product_id'] ?? $input['id'] ?? null;

if (!$productId) {
    sendError('ID de producto requerido');
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Buscar producto
    $stmt = $db->prepare("SELECT id FROM products WHERE code = ? OR id = ?");
    $stmt->execute([$productId, $productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        sendError('Producto no encontrado', 404);
    }
    
    $productIdInt = (int)$product['id'];
    
    // Eliminar del carrito
    $stmt = $db->prepare("
        DELETE FROM cart_items 
        WHERE user_id = ? AND product_id = ?
    ");
    $stmt->execute([$user['id'], $productIdInt]);
    
    sendResponse([
        'success' => true,
        'message' => 'Producto eliminado del carrito'
    ]);
    
} catch (Exception $e) {
    error_log("Error eliminando del carrito: " . $e->getMessage());
    sendError('Error al eliminar producto del carrito', 500);
}

