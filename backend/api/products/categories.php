<?php
/**
 * Endpoint: GET /api/products/categories.php
 * Listar todas las categorías
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError('Método no permitido', 405);
}

try {
    $db = Database::getInstance()->getConnection();
    
    $stmt = $db->query("
        SELECT 
            id,
            code as id,
            name
        FROM categories
        ORDER BY name ASC
    ");
    
    $categories = $stmt->fetchAll();
    
    // Agregar categoría "Todos"
    array_unshift($categories, [
        'id' => 'all',
        'name' => 'Todos'
    ]);
    
    // Convertir IDs a string
    foreach ($categories as &$category) {
        if (isset($category['id']) && is_numeric($category['id'])) {
            $category['id'] = (string)$category['id'];
        }
    }
    
    sendResponse([
        'success' => true,
        'data' => $categories
    ]);
    
} catch (Exception $e) {
    error_log("Error listando categorías: " . $e->getMessage());
    sendError('Error al obtener categorías', 500);
}

