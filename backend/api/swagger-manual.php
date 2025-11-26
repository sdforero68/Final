<?php
/**
 * Especificación OpenAPI/Swagger generada manualmente
 * Esto asegura que todos los endpoints estén documentados correctamente
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
$baseUrl = $protocol . '://' . $host;

$spec = [
    'openapi' => '3.0.0',
    'info' => [
        'version' => '1.0.0',
        'title' => 'Anita Integrales API',
        'description' => 'API RESTful para el e-commerce de productos artesanales e integrales de Anita Integrales',
        'contact' => [
            'email' => 'info@anitaintegrales.com'
        ]
    ],
    'servers' => [
        [
            'url' => $baseUrl . '/api',
            'description' => 'Servidor actual'
        ],
        [
            'url' => 'https://final-1-0wvc.onrender.com/api',
            'description' => 'Servidor de producción (Render)'
        ]
    ],
    'tags' => [
        ['name' => 'Autenticación', 'description' => 'Endpoints para registro, login y gestión de sesiones'],
        ['name' => 'Productos', 'description' => 'Endpoints para consultar productos y categorías'],
        ['name' => 'Carrito', 'description' => 'Endpoints para gestionar el carrito de compras (requiere autenticación)'],
        ['name' => 'Pedidos', 'description' => 'Endpoints para gestionar pedidos (requiere autenticación)'],
        ['name' => 'Salud', 'description' => 'Endpoints para verificar el estado de la API']
    ],
    'components' => [
        'securitySchemes' => [
            'bearerAuth' => [
                'type' => 'http',
                'scheme' => 'bearer',
                'bearerFormat' => 'JWT',
                'description' => 'Token de autenticación obtenido al hacer login. Incluir en formato: Bearer {token}'
            ]
        ]
    ],
    'paths' => [
        '/health.php' => [
            'get' => [
                'tags' => ['Salud'],
                'summary' => 'Health check',
                'description' => 'Verifica que el servidor esté funcionando correctamente',
                'responses' => [
                    '200' => [
                        'description' => 'Servidor funcionando correctamente',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'status' => ['type' => 'string', 'example' => 'ok'],
                                        'message' => ['type' => 'string', 'example' => 'Servidor funcionando'],
                                        'timestamp' => ['type' => 'string'],
                                        'php_version' => ['type' => 'string']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        '/products/list.php' => [
            'get' => [
                'tags' => ['Productos'],
                'summary' => 'Listar todos los productos',
                'description' => 'Obtiene una lista de todos los productos disponibles en el catálogo',
                'responses' => [
                    '200' => [
                        'description' => 'Lista de productos obtenida exitosamente',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'success' => ['type' => 'boolean'],
                                        'data' => [
                                            'type' => 'array',
                                            'items' => [
                                                'type' => 'object',
                                                'properties' => [
                                                    'id' => ['type' => 'string'],
                                                    'code' => ['type' => 'string'],
                                                    'name' => ['type' => 'string'],
                                                    'category' => ['type' => 'string'],
                                                    'category_name' => ['type' => 'string'],
                                                    'price' => ['type' => 'number'],
                                                    'description' => ['type' => 'string'],
                                                    'ingredients' => ['type' => 'string'],
                                                    'benefits' => ['type' => 'string'],
                                                    'image' => ['type' => 'string']
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        '/products/get.php' => [
            'get' => [
                'tags' => ['Productos'],
                'summary' => 'Obtener un producto por ID o código',
                'description' => 'Obtiene la información detallada de un producto específico',
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'query',
                        'required' => true,
                        'schema' => ['type' => 'string'],
                        'description' => 'ID numérico o código del producto'
                    ]
                ],
                'responses' => [
                    '200' => ['description' => 'Producto obtenido exitosamente'],
                    '404' => ['description' => 'Producto no encontrado']
                ]
            ]
        ],
        '/products/categories.php' => [
            'get' => [
                'tags' => ['Productos'],
                'summary' => 'Listar todas las categorías',
                'description' => 'Obtiene una lista de todas las categorías de productos',
                'responses' => [
                    '200' => ['description' => 'Lista de categorías obtenida exitosamente']
                ]
            ]
        ],
        '/auth/login.php' => [
            'post' => [
                'tags' => ['Autenticación'],
                'summary' => 'Iniciar sesión',
                'description' => 'Autentica un usuario y devuelve un token de sesión',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['email', 'password'],
                                'properties' => [
                                    'email' => ['type' => 'string', 'format' => 'email'],
                                    'password' => ['type' => 'string', 'format' => 'password']
                                ]
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '200' => ['description' => 'Login exitoso'],
                    '401' => ['description' => 'Correo o contraseña incorrectos']
                ]
            ]
        ],
        '/auth/register.php' => [
            'post' => [
                'tags' => ['Autenticación'],
                'summary' => 'Registrar nuevo usuario',
                'description' => 'Crea una nueva cuenta de usuario y devuelve un token de sesión',
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['name', 'email', 'password'],
                                'properties' => [
                                    'name' => ['type' => 'string'],
                                    'email' => ['type' => 'string', 'format' => 'email'],
                                    'phone' => ['type' => 'string'],
                                    'password' => ['type' => 'string', 'minLength' => 6]
                                ]
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '201' => ['description' => 'Usuario registrado exitosamente'],
                    '409' => ['description' => 'El correo electrónico ya está registrado']
                ]
            ]
        ],
        '/cart/get.php' => [
            'get' => [
                'tags' => ['Carrito'],
                'summary' => 'Obtener carrito de compras',
                'description' => 'Obtiene todos los productos en el carrito del usuario autenticado',
                'security' => [['bearerAuth' => []]],
                'responses' => [
                    '200' => ['description' => 'Carrito obtenido exitosamente'],
                    '401' => ['description' => 'No autenticado']
                ]
            ]
        ],
        '/cart/add.php' => [
            'post' => [
                'tags' => ['Carrito'],
                'summary' => 'Agregar producto al carrito',
                'description' => 'Agrega un producto al carrito de compras',
                'security' => [['bearerAuth' => []]],
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['product_id'],
                                'properties' => [
                                    'product_id' => ['type' => 'string'],
                                    'quantity' => ['type' => 'integer', 'default' => 1]
                                ]
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '200' => ['description' => 'Producto agregado al carrito exitosamente'],
                    '401' => ['description' => 'No autenticado']
                ]
            ]
        ],
        '/orders/list.php' => [
            'get' => [
                'tags' => ['Pedidos'],
                'summary' => 'Listar pedidos del usuario',
                'description' => 'Obtiene todos los pedidos realizados por el usuario autenticado',
                'security' => [['bearerAuth' => []]],
                'responses' => [
                    '200' => ['description' => 'Lista de pedidos obtenida exitosamente'],
                    '401' => ['description' => 'No autenticado']
                ]
            ]
        ],
        '/orders/create.php' => [
            'post' => [
                'tags' => ['Pedidos'],
                'summary' => 'Crear nuevo pedido',
                'description' => 'Crea un nuevo pedido desde los productos del carrito',
                'security' => [['bearerAuth' => []]],
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['delivery_method', 'payment_method'],
                                'properties' => [
                                    'delivery_method' => ['type' => 'string', 'enum' => ['delivery', 'pickup']],
                                    'payment_method' => ['type' => 'string'],
                                    'customer_info' => ['type' => 'object']
                                ]
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '201' => ['description' => 'Pedido creado exitosamente'],
                    '400' => ['description' => 'Datos inválidos o carrito vacío'],
                    '401' => ['description' => 'No autenticado']
                ]
            ]
        ]
    ]
];

echo json_encode($spec, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

