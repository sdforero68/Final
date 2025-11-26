<?php
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Anita Integrales API",
 *     description="API RESTful para el e-commerce de productos artesanales e integrales de Anita Integrales",
 *     @OA\Contact(
 *         email="info@anitaintegrales.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8080/api",
 *     description="Servidor de desarrollo"
 * )
 * 
 * @OA\Server(
 *     url="https://final-1-0wvc.onrender.com/api",
 *     description="Servidor de producción"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Token de autenticación obtenido al hacer login. Incluir en formato: Bearer {token}"
 * )
 * 
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Endpoints para registro, login y gestión de sesiones"
 * )
 * 
 * @OA\Tag(
 *     name="Productos",
 *     description="Endpoints para consultar productos y categorías"
 * )
 * 
 * @OA\Tag(
 *     name="Carrito",
 *     description="Endpoints para gestionar el carrito de compras (requiere autenticación)"
 * )
 * 
 * @OA\Tag(
 *     name="Pedidos",
 *     description="Endpoints para gestionar pedidos (requiere autenticación)"
 * )
 * 
 * @OA\Tag(
 *     name="Salud",
 *     description="Endpoints para verificar el estado de la API"
 * )
 */

/**
 * Clase base para las anotaciones OpenAPI
 * Esta clase permite que Swagger procese las anotaciones del PHPDoc
 */
class OpenApiSpec {}

