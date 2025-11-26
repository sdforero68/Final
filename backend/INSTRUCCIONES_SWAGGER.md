# 🚀 Guía Rápida: Swagger Integrado

## ✅ ¿Qué se ha hecho?

Se ha integrado **Swagger/OpenAPI** a tu API PHP con:

1. ✅ **Dependencias instaladas** (`composer.json` creado)
2. ✅ **Endpoint JSON de Swagger** (`/api/swagger.php`) - Genera la especificación OpenAPI
3. ✅ **Interfaz visual Swagger UI** (`/api/swagger-ui.php`) - Documentación interactiva
4. ✅ **Anotaciones Swagger** agregadas a los endpoints principales:
   - Autenticación (login, register)
   - Productos (list, get, categories)
   - Carrito (get, add)
   - Pedidos (create, list)
   - Health check

## 📋 Pasos para usar Swagger

### 1. Instalar Composer (si no lo tienes)

**En macOS:**
```bash
brew install composer
```

**O descarga desde:**
https://getcomposer.org/download/

### 2. Instalar dependencias de Swagger

```bash
cd backend
composer install
```

**O usa el script automatizado:**
```bash
cd backend
./install-swagger.sh
```

### 3. Iniciar el servidor backend

```bash
cd backend
php -S localhost:8080 router.php
```

### 4. Acceder a la documentación

Abre en tu navegador:
- **Interfaz visual:** http://localhost:8080/swagger-ui.php
- **JSON OpenAPI:** http://localhost:8080/swagger.php

## 🎯 Características

### Interfaz Swagger UI
- ✅ Visualización completa de todos los endpoints
- ✅ Prueba de endpoints directamente desde el navegador
- ✅ Ejemplos de request/response
- ✅ Autenticación con tokens Bearer
- ✅ Documentación automática generada desde código

### Endpoints Documentados

**Autenticación:**
- `POST /auth/register.php`
- `POST /auth/login.php`

**Productos:**
- `GET /products/list.php`
- `GET /products/get.php?id=xxx`
- `GET /products/categories.php`

**Carrito:**
- `GET /cart/get.php` (requiere auth)
- `POST /cart/add.php` (requiere auth)

**Pedidos:**
- `POST /orders/create.php` (requiere auth)
- `GET /orders/list.php` (requiere auth)

**Salud:**
- `GET /health.php`

## 🔧 Agregar más anotaciones

Para documentar más endpoints, agrega comentarios PHPDoc usando el formato OpenAPI:

```php
/**
 * @OA\Get(
 *     path="/tu-endpoint.php",
 *     tags={"Tu Categoría"},
 *     summary="Descripción corta",
 *     description="Descripción detallada",
 *     security={{"bearerAuth": {}}},  // Solo si requiere autenticación
 *     @OA\Parameter(...),
 *     @OA\Response(...)
 * )
 */
```

## 📝 Notas Importantes

1. **Composer es requerido** - Sin él, Swagger no funcionará
2. **Las anotaciones se generan en tiempo real** - Cada vez que accedas a `/swagger.php`, se escanean los archivos
3. **Swagger UI permite probar endpoints** - Puedes hacer requests directamente desde la interfaz
4. **Autenticación automática** - Si tienes un token en localStorage, Swagger UI lo usará automáticamente

## 🐛 Solución de Problemas

### Error: "Class 'OpenApi\Generator' not found"
- **Solución:** Ejecuta `composer install` en el directorio `backend/`

### Error: "Cannot find composer.json"
- **Solución:** Verifica que estés en el directorio `backend/`

### Swagger UI muestra página en blanco
- **Solución:** Verifica que el servidor esté corriendo y accede a `http://localhost:8080/swagger-ui.php`
- Revisa la consola del navegador para errores JavaScript

### Los endpoints no aparecen en Swagger
- **Solución:** Verifica que las anotaciones OpenAPI estén correctamente escritas en los archivos PHP
- Revisa los logs del servidor para errores de parsing

## 📚 Recursos

- **OpenAPI Specification:** https://swagger.io/specification/
- **Swagger PHP Annotations:** https://zircote.github.io/swagger-php/
- **Swagger UI:** https://swagger.io/tools/swagger-ui/

