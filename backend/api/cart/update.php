<?php
/**
 * Endpoint: PUT /api/cart/update.php
 * Actualizar cantidad de un producto en el carrito
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendError('Método no permitido', 405);
}

$token = getAuthToken();
$user = validateToken($token);

if (!$user) {
    sendError('No autenticado', 401);
}

$input = json_decode(file_get_contents('php://input'), true);
$productId = $input['product_id'] ?? $input['id'] ?? null;
$quantity = (int)($input['quantity'] ?? 1);

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
    
    if ($quantity <= 0) {
        // Eliminar del carrito si la cantidad es 0
        $stmt = $db->prepare("
            DELETE FROM cart_items 
            WHERE user_id = ? AND product_id = ?
        ");
        $stmt->execute([$user['id'], $productIdInt]);
    } else {
        // Actualizar cantidad
        $stmt = $db->prepare("
            UPDATE cart_items 
            SET quantity = ?, updated_at = NOW() 
            WHERE user_id = ? AND product_id = ?
        ");
        $stmt->execute([$quantity, $user['id'], $productIdInt]);
        
        if ($stmt->rowCount() === 0) {
            sendError('Item no encontrado en el carrito', 404);
        }
    }
    
    sendResponse([
        'success' => true,
        'message' => 'Carrito actualizado'
    ]);
    
} catch (Exception $e) {
    error_log("Error actualizando carrito: " . $e->getMessage());
    sendError('Error al actualizar carrito', 500);
}

