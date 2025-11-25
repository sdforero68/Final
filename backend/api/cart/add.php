<?php
/**
 * Endpoint: POST /api/cart/add.php
 * Agregar un producto al carrito
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

$input = json_decode(file_get_contents('php://input'), true);
$productId = $input['product_id'] ?? $input['id'] ?? null;
$quantity = (int)($input['quantity'] ?? 1);

if (!$productId) {
    sendError('ID de producto requerido');
}

if ($quantity < 1) {
    $quantity = 1;
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Buscar producto por código o ID
    $stmt = $db->prepare("SELECT id FROM products WHERE code = ? OR id = ?");
    $stmt->execute([$productId, $productId]);
    $product = $stmt->fetch();
    
    if (!$product) {
        sendError('Producto no encontrado', 404);
    }
    
    $productIdInt = (int)$product['id'];
    
    // Verificar si el producto ya está en el carrito
    $stmt = $db->prepare("
        SELECT id, quantity 
        FROM cart_items 
        WHERE user_id = ? AND product_id = ?
    ");
    $stmt->execute([$user['id'], $productIdInt]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        // Actualizar cantidad
        $newQuantity = $existing['quantity'] + $quantity;
        $stmt = $db->prepare("
            UPDATE cart_items 
            SET quantity = ?, updated_at = NOW() 
            WHERE id = ?
        ");
        $stmt->execute([$newQuantity, $existing['id']]);
    } else {
        // Insertar nuevo item
        $stmt = $db->prepare("
            INSERT INTO cart_items (user_id, product_id, quantity) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$user['id'], $productIdInt, $quantity]);
    }
    
    sendResponse([
        'success' => true,
        'message' => 'Producto agregado al carrito'
    ]);
    
} catch (Exception $e) {
    error_log("Error agregando al carrito: " . $e->getMessage());
    sendError('Error al agregar producto al carrito', 500);
}

