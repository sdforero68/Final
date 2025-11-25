<?php
/**
 * Endpoint: GET /api/orders/list.php
 * Listar pedidos del usuario autenticado
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
            id, order_number, status, subtotal, delivery_fee, total,
            delivery_method, delivery_address, payment_method,
            customer_name, customer_email, customer_phone, notes,
            created_at
        FROM orders
        WHERE user_id = ?
        ORDER BY created_at DESC
    ");
    $stmt->execute([$user['id']]);
    $orders = $stmt->fetchAll();
    
    // Obtener items para cada pedido
    foreach ($orders as &$order) {
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
    }
    
    sendResponse([
        'success' => true,
        'data' => $orders
    ]);
    
} catch (Exception $e) {
    error_log("Error listando pedidos: " . $e->getMessage());
    sendError('Error al obtener pedidos', 500);
}

