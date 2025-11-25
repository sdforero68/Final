<?php
/**
 * Endpoint unificado para pedidos
 * Maneja GET /orders.php?userId=X y POST /orders.php
 */

require_once __DIR__ . '/config.php';

$method = $_SERVER['REQUEST_METHOD'];

// GET: Listar pedidos
if ($method === 'GET') {
    $userId = $_GET['userId'] ?? null;
    
    if (!$userId) {
        sendError('userId es requerido', 400);
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
        $stmt->execute([$userId]);
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
        sendError('Error al obtener pedidos: ' . $e->getMessage(), 500);
    }
}

// POST: Crear pedido
if ($method === 'POST') {
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
    $items = $input['items'] ?? [];
    
    if (!$deliveryMethod || !$paymentMethod) {
        sendError('Método de entrega y pago son requeridos');
    }
    
    if ($deliveryMethod === 'delivery' && empty($customerInfo['address'])) {
        sendError('Dirección requerida para envío a domicilio');
    }
    
    if (empty($items)) {
        sendError('El pedido debe contener al menos un item');
    }
    
    try {
        $db = Database::getInstance()->getConnection();
        $db->beginTransaction();
        
        // Calcular totales desde los items enviados
        $subtotal = 0;
        foreach ($items as $item) {
            $price = is_array($item) ? ($item['price'] ?? 0) : ($item->price ?? 0);
            $quantity = is_array($item) ? ($item['quantity'] ?? 0) : ($item->quantity ?? 0);
            $subtotal += (float)$price * (int)$quantity;
        }
        
        $deliveryFee = ($deliveryMethod === 'delivery') ? ($input['deliveryFee'] ?? 5000) : 0;
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
        
        foreach ($items as $item) {
            $productId = is_array($item) ? ($item['id'] ?? $item['product_id'] ?? 0) : ($item->id ?? 0);
            $productName = is_array($item) ? ($item['name'] ?? '') : ($item->name ?? '');
            $productPrice = is_array($item) ? ($item['price'] ?? 0) : ($item->price ?? 0);
            $quantity = is_array($item) ? ($item['quantity'] ?? 0) : ($item->quantity ?? 0);
            $itemSubtotal = (float)$productPrice * (int)$quantity;
            
            $stmt->execute([
                $orderId,
                $productId ?: null,
                $productName,
                $productPrice,
                $quantity,
                $itemSubtotal
            ]);
        }
        
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
        sendError('Error al crear pedido: ' . $e->getMessage(), 500);
    }
}

// Método no permitido
sendError('Método no permitido', 405);

