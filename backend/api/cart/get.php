<?php
/**
 * @OA\Get(
 *     path="/cart/get",
 *     tags={"Carrito"},
 *     summary="Obtener carrito de compras",
 *     description="Obtiene todos los productos en el carrito del usuario autenticado",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Carrito obtenido exitosamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="string", example="ACH001"),
 *                     @OA\Property(property="name", type="string", example="Achiras Grandes"),
 *                     @OA\Property(property="price", type="number", format="float", example=15000.00),
 *                     @OA\Property(property="quantity", type="integer", example=2),
 *                     @OA\Property(property="image", type="string"),
 *                     @OA\Property(property="category", type="string", example="panes"),
 *                     @OA\Property(property="description", type="string")
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

