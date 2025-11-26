<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anita Integrales - Documentación API (Swagger)</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.17.14/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin:0;
            background: #fafafa;
        }
        .topbar {
            background-color: #2b2b2b;
            padding: 10px 0;
            text-align: center;
        }
        .topbar h1 {
            color: #fff;
            margin: 0;
            font-size: 20px;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="topbar">
        <h1>🍞 Anita Integrales - Documentación de API</h1>
    </div>
    <div id="swagger-ui"></div>
    <script src="https://unpkg.com/swagger-ui-dist@5.17.14/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.17.14/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            // Obtener la URL base para construir la ruta al JSON de Swagger
            const protocol = window.location.protocol;
            const host = window.location.host;
            const baseUrl = protocol + '//' + host;
            
            // Construir la ruta al JSON de Swagger
            // El router maneja tanto /swagger.php como /api/swagger.php
            const currentPath = window.location.pathname;
            
            // Detectar la ruta base correcta
            let swaggerUrl;
            if (currentPath.startsWith('/api/')) {
                // Si accedimos a /api/swagger-ui.php, usar /api/swagger.php
                swaggerUrl = baseUrl + '/api/swagger.php';
            } else {
                // Si accedimos a /swagger-ui.php, usar /swagger.php (el router lo redirigirá)
                swaggerUrl = baseUrl + '/swagger.php';
            }
            
            // Fallback: intentar ambas rutas
            console.log('Swagger URL configurada:', swaggerUrl);
            
            const ui = SwaggerUIBundle({
                url: swaggerUrl,
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                tryItOutEnabled: true,
                requestInterceptor: (request) => {
                    // Agregar token de autenticación si existe en localStorage
                    const token = localStorage.getItem('accessToken') || localStorage.getItem('current_session');
                    if (token && request.headers) {
                        request.headers['Authorization'] = 'Bearer ' + token;
                    }
                    return request;
                }
            });
        };
    </script>
</body>
</html>

