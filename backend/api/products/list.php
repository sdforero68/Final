<?php
/**
 * Endpoint: GET /api/products/list.php
 * Listar todos los productos
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

