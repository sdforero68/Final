<?php
/**
 * @OA\Get(
 *     path="/orders/list",
 *     tags={"Pedidos"},
 *     summary="Listar pedidos del usuario",
 *     description="Obtiene todos los pedidos realizados por el usuario autenticado",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de pedidos obtenida exitosamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="string", example="1"),
 *                     @OA\Property(property="order_number", type="string", example="ORD-20251126-ABC12345"),
 *                     @OA\Property(property="status", type="string", example="pendiente"),
 *                     @OA\Property(property="subtotal", type="number", format="float", example=30000.00),
 *                     @OA\Property(property="delivery_fee", type="number", format="float", example=5000.00),
 *                     @OA\Property(property="total", type="number", format="float", example=35000.00),
 *                     @OA\Property(property="delivery_method", type="string", example="delivery"),
 *                     @OA\Property(property="payment_method", type="string", example="transferencia"),
 *                     @OA\Property(property="created_at", type="string", format="date-time"),
 *                     @OA\Property(
 *                         property="items",
 *                         type="array",
 *                         @OA\Items(
 *                             type="object",
 *                             @OA\Property(property="name", type="string"),
 *                             @OA\Property(property="price", type="number", format="float"),
 *                             @OA\Property(property="quantity", type="integer"),
 *                             @OA\Property(property="subtotal", type="number", format="float")
 *                         )
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(response=401, description="No autenticado"),
 *     @OA\Response(response=500, description="Error del servidor")
 * )
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

