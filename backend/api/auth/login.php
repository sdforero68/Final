<?php
/**
 * Endpoint: POST /api/auth/login.php
 * Inicio de sesión de usuarios
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Método no permitido', 405);
}

$input = json_decode(file_get_contents('php://input'), true);

// Validar datos
$email = trim(strtolower($input['email'] ?? ''));
$password = $input['password'] ?? '';

if (empty($email) || empty($password)) {
    sendError('Email y contraseña son requeridos');
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Buscar usuario
    $stmt = $db->prepare("SELECT id, email, password, name, phone FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user || !password_verify($password, $user['password'])) {
        sendError('Correo o contraseña incorrectos', 401);
    }
    
    // Crear nueva sesión
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    // Eliminar sesiones expiradas del usuario
    $stmt = $db->prepare("DELETE FROM sessions WHERE user_id = ? AND expires_at <= NOW()");
    $stmt->execute([$user['id']]);
    
    // Insertar nueva sesión
    $stmt = $db->prepare("
        INSERT INTO sessions (user_id, token, expires_at) 
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$user['id'], $token, $expiresAt]);
    
    sendResponse([
        'success' => true,
        'message' => 'Login exitoso',
        'data' => [
            'token' => $token,
            'user' => [
                'id' => (string)$user['id'],
                'email' => $user['email'],
                'user_metadata' => [
                    'name' => $user['name'],
                    'phone' => $user['phone']
                ]
            ]
        ]
    ]);
    
} catch (Exception $e) {
    error_log("Error en login: " . $e->getMessage());
    sendError('Error al iniciar sesión', 500);
}

