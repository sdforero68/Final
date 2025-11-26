<?php
/**
 * @OA\Get(
 *     path="/products/list",
 *     tags={"Productos"},
 *     summary="Listar todos los productos",
 *     description="Obtiene una lista de todos los productos disponibles en el catálogo",
 *     @OA\Response(
 *         response=200,
 *         description="Lista de productos obtenida exitosamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="string", example="1"),
 *                     @OA\Property(property="code", type="string", example="ACH001"),
 *                     @OA\Property(property="name", type="string", example="Achiras Grandes"),
 *                     @OA\Property(property="category", type="string", example="panes"),
 *                     @OA\Property(property="category_name", type="string", example="Panes"),
 *                     @OA\Property(property="price", type="number", format="float", example=15000.00),
 *                     @OA\Property(property="description", type="string"),
 *                     @OA\Property(property="ingredients", type="string"),
 *                     @OA\Property(property="benefits", type="string"),
 *                     @OA\Property(property="image", type="string", example="/assets/images/Catálogo/AchirasGrandes.jpg")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(response=500, description="Error del servidor")
 * )
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Método no permitido', 405);
}

try {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->query("
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
        ORDER BY p.name ASC
    ");
    
    $products = $stmt->fetchAll();
    
    // Convertir tipos numéricos
    foreach ($products as &$product) {
        $product['id'] = (string)$product['id'];
        $product['price'] = (float)$product['price'];
    }
    
    sendResponse([
        'success' => true,
        'data' => $products
    ]);
    
} catch (Exception $e) {
    error_log("Error listando productos: " . $e->getMessage());
    sendError('Error al obtener productos', 500);
}

