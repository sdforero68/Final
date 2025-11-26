<?php
/**
 * @OA\Post(
 *     path="/cart/add",
 *     tags={"Carrito"},
 *     summary="Agregar producto al carrito",
 *     description="Agrega un producto al carrito de compras del usuario autenticado. Si el producto ya existe, incrementa la cantidad",
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_id"},
 *             @OA\Property(property="product_id", type="string", example="1", description="ID o código del producto"),
 *             @OA\Property(property="quantity", type="integer", example=1, description="Cantidad a agregar (por defecto: 1)")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Producto agregado al carrito exitosamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Producto agregado al carrito")
 *         )
 *     ),
 *     @OA\Response(response=401, description="No autenticado"),
 *     @OA\Response(response=404, description="Producto no encontrado"),
 *     @OA\Response(response=500, description="Error del servidor")
 * )
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

