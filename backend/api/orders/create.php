<?php
/**
 * Endpoint: POST /api/orders/create.php
 * Crear un nuevo pedido desde el carrito
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

// Validar datos requeridos
$deliveryMethod = $input['delivery_method'] ?? $input['deliveryMethod'] ?? null;
$paymentMethod = $input['payment_method'] ?? $input['paymentMethod'] ?? null;
$customerInfo = $input['customer_info'] ?? $input['customerInfo'] ?? [];

if (!$deliveryMethod || !$paymentMethod) {
    sendError('Método de entrega y pago son requeridos');
}

if ($deliveryMethod === 'delivery' && empty($customerInfo['address'])) {
    sendError('Dirección requerida para envío a domicilio');
}

try {
    $db = Database::getInstance()->getConnection();
    $db->beginTransaction();
    
    // Obtener items del carrito
    $stmt = $db->prepare("
        SELECT 
            ci.product_id,
            ci.quantity,
            p.code,
            p.name,
            p.price
        FROM cart_items ci
        INNER JOIN products p ON ci.product_id = p.id
        WHERE ci.user_id = ?
    ");
    $stmt->execute([$user['id']]);
    $cartItems = $stmt->fetchAll();
    
    if (empty($cartItems)) {
        $db->rollBack();
        sendError('El carrito está vacío', 400);
    }
    
    // Calcular totales
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += (float)$item['price'] * (int)$item['quantity'];
    }
    
    $deliveryFee = ($deliveryMethod === 'delivery') ? 5000 : 0;
    $total = $subtotal + $deliveryFee;
    
    // Generar número de pedido
    $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
    
    // Crear pedido
    $stmt = $db->prepare("
        INSERT INTO orders (
            order_number, user_id, status, subtotal, delivery_fee, total,
            delivery_method, delivery_address, payment_method,
            customer_name, customer_email, customer_phone, notes
        ) VALUES (?, ?, 'pendiente', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $orderNumber,
        $user['id'],
        $subtotal,
        $deliveryFee,
        $total,
        $deliveryMethod,
        $customerInfo['address'] ?? null,
        $paymentMethod,
        $customerInfo['name'] ?? $user['name'],
        $customerInfo['email'] ?? $user['email'],
        $customerInfo['phone'] ?? $user['phone'] ?? '',
        $customerInfo['notes'] ?? null
    ]);
    
    $orderId = $db->lastInsertId();
    
    // Crear items del pedido
    $stmt = $db->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, product_price, quantity, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($cartItems as $item) {
        $itemSubtotal = (float)$item['price'] * (int)$item['quantity'];
        $stmt->execute([
            $orderId,
            $item['product_id'],
            $item['name'],
            $item['price'],
            $item['quantity'],
            $itemSubtotal
        ]);
    }
    
    // Vaciar carrito
    $stmt = $db->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$user['id']]);
    
    $db->commit();
    
    // Obtener pedido completo
    $stmt = $db->prepare("
        SELECT 
            id, order_number, status, subtotal, delivery_fee, total,
            delivery_method, delivery_address, payment_method,
            customer_name, customer_email, customer_phone, notes,
            created_at
        FROM orders
        WHERE id = ?
    ");
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    // Obtener items del pedido
    $stmt = $db->prepare("
        SELECT 
            product_name, product_price, quantity, subtotal
        FROM order_items
        WHERE order_id = ?
    ");
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll();
    
    sendResponse([
        'success' => true,
        'message' => 'Pedido creado exitosamente',
        'data' => [
            'order' => [
                'id' => (string)$order['id'],
                'order_number' => $order['order_number'],
                'status' => $order['status'],
                'subtotal' => (float)$order['subtotal'],
                'delivery_fee' => (float)$order['delivery_fee'],
                'total' => (float)$order['total'],
                'delivery_method' => $order['delivery_method'],
                'delivery_address' => $order['delivery_address'],
                'payment_method' => $order['payment_method'],
                'customer_name' => $order['customer_name'],
                'customer_email' => $order['customer_email'],
                'customer_phone' => $order['customer_phone'],
                'notes' => $order['notes'],
                'created_at' => $order['created_at'],
                'items' => array_map(function($item) {
                    return [
                        'name' => $item['product_name'],
                        'price' => (float)$item['product_price'],
                        'quantity' => (int)$item['quantity'],
                        'subtotal' => (float)$item['subtotal']
                    ];
                }, $orderItems)
            ]
        ]
    ], 201);
    
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    error_log("Error creando pedido: " . $e->getMessage());
    sendError('Error al crear pedido', 500);
}

