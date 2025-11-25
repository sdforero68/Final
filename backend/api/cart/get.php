<?php
/**
 * Endpoint: GET /api/cart/get.php
 * Obtener el carrito del usuario autenticado
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Método no permitido', 405);
}

$token = getAuthToken();
$user = validateToken($token);

if (!$user) {
    sendError('No autenticado', 401);
}

try {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->prepare("
        SELECT 
            ci.id,
            ci.product_id,
            ci.quantity,
            p.code,
            p.name,
            p.price,
            p.description,
            p.image,
            c.code as category
        FROM cart_items ci
        INNER JOIN products p ON ci.product_id = p.id
        INNER JOIN categories c ON p.category_id = c.id
        WHERE ci.user_id = ?
        ORDER BY ci.created_at DESC
    ");
    $stmt->execute([$user['id']]);
    $items = $stmt->fetchAll();
    
    // Formatear items
    $cart = [];
    foreach ($items as $item) {
        $cart[] = [
            'id' => $item['code'],
            'name' => $item['name'],
            'price' => (float)$item['price'],
            'quantity' => (int)$item['quantity'],
            'image' => $item['image'],
            'category' => $item['category'],
            'description' => $item['description']
        ];
    }
    
    sendResponse([
        'success' => true,
        'data' => $cart
    ]);
    
} catch (Exception $e) {
    error_log("Error obteniendo carrito: " . $e->getMessage());
    sendError('Error al obtener carrito', 500);
}

