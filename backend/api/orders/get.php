<?php
/**
 * Endpoint: GET /api/orders/get.php?id=xxx
 * Obtener un pedido específico por ID
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

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    sendError('ID de pedido requerido');
}

try {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->prepare("
        SELECT 
            id, order_number, status, subtotal, delivery_fee, total,
            delivery_method, delivery_address, payment_method,
            customer_name, customer_email, customer_phone, notes,
            created_at
        FROM orders
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$orderId, $user['id']]);
    $order = $stmt->fetch();
    
    if (!$order) {
        sendError('Pedido no encontrado', 404);
    }
    
    // Obtener items del pedido
    $stmt = $db->prepare("
        SELECT 
            product_name, product_price, quantity, subtotal
        FROM order_items
        WHERE order_id = ?
    ");
    $stmt->execute([$order['id']]);
    $items = $stmt->fetchAll();
    
    $order['items'] = array_map(function($item) {
        return [
            'name' => $item['product_name'],
            'price' => (float)$item['product_price'],
            'quantity' => (int)$item['quantity'],
            'subtotal' => (float)$item['subtotal']
        ];
    }, $items);
    
    // Convertir tipos
    $order['id'] = (string)$order['id'];
    $order['subtotal'] = (float)$order['subtotal'];
    $order['delivery_fee'] = (float)$order['delivery_fee'];
    $order['total'] = (float)$order['total'];
    
    sendResponse([
        'success' => true,
        'data' => $order
    ]);
    
} catch (Exception $e) {
    error_log("Error obteniendo pedido: " . $e->getMessage());
    sendError('Error al obtener pedido', 500);
}

