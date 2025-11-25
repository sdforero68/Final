<?php
/**
 * Endpoint: POST /api/auth/logout.php
 * Cerrar sesión de usuarios
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Método no permitido', 405);
}

$token = getAuthToken();

if (!$token) {
    sendError('Token requerido', 401);
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Eliminar sesión
    $stmt = $db->prepare("DELETE FROM sessions WHERE token = ?");
    $stmt->execute([$token]);
    
    sendResponse([
        'success' => true,
        'message' => 'Sesión cerrada exitosamente'
    ]);
    
} catch (Exception $e) {
    error_log("Error en logout: " . $e->getMessage());
    sendError('Error al cerrar sesión', 500);
}

