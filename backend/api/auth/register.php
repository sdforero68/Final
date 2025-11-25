<?php
/**
 * Endpoint: POST /api/auth/register.php
 * Registro de nuevos usuarios
 */

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError('Método no permitido', 405);
}

$input = json_decode(file_get_contents('php://input'), true);

// Validar datos
$name = trim($input['name'] ?? '');
$email = trim(strtolower($input['email'] ?? ''));
$phone = trim($input['phone'] ?? '');
$password = $input['password'] ?? '';

if (empty($name) || empty($email) || empty($password)) {
    sendError('Nombre, email y contraseña son requeridos');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendError('Email inválido');
}

if (strlen($password) < 6) {
    sendError('La contraseña debe tener al menos 6 caracteres');
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Verificar si el email ya existe
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        sendError('Este correo electrónico ya está registrado', 409);
    }
    
    // Hashear contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar usuario
    $stmt = $db->prepare("
        INSERT INTO users (email, password, name, phone) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$email, $hashedPassword, $name, $phone ?: null]);
    $userId = $db->lastInsertId();
    
    // Crear sesión automáticamente
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    $stmt = $db->prepare("
        INSERT INTO sessions (user_id, token, expires_at) 
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$userId, $token, $expiresAt]);
    
    // Obtener datos del usuario (sin contraseña)
    $stmt = $db->prepare("SELECT id, email, name, phone, created_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    sendResponse([
        'success' => true,
        'message' => 'Usuario registrado exitosamente',
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
    ], 201);
    
} catch (Exception $e) {
    error_log("Error en registro: " . $e->getMessage());
    sendError('Error al registrar usuario', 500);
}

