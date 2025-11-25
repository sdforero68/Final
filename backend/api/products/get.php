<?php
/**
 * Endpoint: GET /api/products/get.php?id=xxx
 * Obtener un producto por ID o código
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

