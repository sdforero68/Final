<?php
/**
 * @OA\Get(
 *     path="/health",
 *     tags={"Salud"},
 *     summary="Health check",
 *     description="Verifica que el servidor esté funcionando correctamente",
 *     @OA\Response(
 *         response=200,
 *         description="Servidor funcionando correctamente",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="ok"),
 *             @OA\Property(property="message", type="string", example="Servidor funcionando"),
 *             @OA\Property(property="timestamp", type="string", format="date-time", example="2025-11-26 18:28:45"),
 *             @OA\Property(property="php_version", type="string", example="8.5.0")
 *         )
 *     )
 * )
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

echo json_encode([
    'status' => 'ok',
    'message' => 'Servidor funcionando',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION
]);

