<?php
/**
 * @OA\Post(
 *     path="/auth/login",
 *     tags={"Autenticación"},
 *     summary="Iniciar sesión",
 *     description="Autentica un usuario y devuelve un token de sesión",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="usuario@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="contraseña123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Login exitoso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Login exitoso"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="token", type="string", example="abc123def456..."),
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="string", example="1"),
 *                     @OA\Property(property="email", type="string", example="usuario@example.com"),
 *                     @OA\Property(
 *                         property="user_metadata",
 *                         type="object",
 *                         @OA\Property(property="name", type="string", example="Juan Pérez"),
 *                         @OA\Property(property="phone", type="string", example="+573001234567")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(response=400, description="Email y contraseña son requeridos"),
 *     @OA\Response(response=401, description="Correo o contraseña incorrectos"),
 *     @OA\Response(response=500, description="Error del servidor")
 * )
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

