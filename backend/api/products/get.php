<?php
/**
 * @OA\Get(
 *     path="/products/get",
 *     tags={"Productos"},
 *     summary="Obtener un producto por ID o código",
 *     description="Obtiene la información detallada de un producto específico usando su ID o código",
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         required=true,
 *         description="ID numérico o código del producto",
 *         @OA\Schema(type="string", example="1")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Producto obtenido exitosamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="string", example="1"),
 *                 @OA\Property(property="code", type="string", example="ACH001"),
 *                 @OA\Property(property="name", type="string", example="Achiras Grandes"),
 *                 @OA\Property(property="category", type="string", example="panes"),
 *                 @OA\Property(property="category_name", type="string", example="Panes"),
 *                 @OA\Property(property="price", type="number", format="float", example=15000.00),
 *                 @OA\Property(property="description", type="string"),
 *                 @OA\Property(property="ingredients", type="string"),
 *                 @OA\Property(property="benefits", type="string"),
 *                 @OA\Property(property="image", type="string")
 *             )
 *         )
 *     ),
 *     @OA\Response(response=400, description="ID de producto requerido"),
 *     @OA\Response(response=404, description="Producto no encontrado"),
 *     @OA\Response(response=500, description="Error del servidor")
 * )
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Método no permitido', 405);
}

$id = $_GET['id'] ?? null;

if (!$id) {
    sendError('ID de producto requerido');
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Intentar buscar por ID numérico o código
    $stmt = $db->prepare("
        SELECT 
            p.id,
            p.code,
            p.name,
            c.code as category,
            c.name as category_name,
            p.price,
            p.description,
            p.ingredients,
            p.benefits,
            p.image
        FROM products p
        INNER JOIN categories c ON p.category_id = c.id
        WHERE p.id = ? OR p.code = ?
    ");
    $stmt->execute([$id, $id]);
    $product = $stmt->fetch();
    
    if (!$product) {
        sendError('Producto no encontrado', 404);
    }
    
    // Convertir tipos
    $product['id'] = (string)$product['id'];
    $product['price'] = (float)$product['price'];
    
    sendResponse([
        'success' => true,
        'data' => $product
    ]);
    
} catch (Exception $e) {
    error_log("Error obteniendo producto: " . $e->getMessage());
    sendError('Error al obtener producto', 500);
}

