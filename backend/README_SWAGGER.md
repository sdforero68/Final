# 📚 Documentación Swagger - Anita Integrales API

## Instalación

### 1. Instalar Composer (si no lo tienes)

En macOS:
```bash
brew install composer
```

O descarga desde: https://getcomposer.org/download/

### 2. Instalar dependencias

```bash
cd backend
composer install
```

### 3. Acceder a la documentación

Una vez instalado Composer y las dependencias:

**Opción 1: Interfaz visual de Swagger UI**
```
http://localhost:8080/swagger-ui.php
```

**Opción 2: JSON de la especificación OpenAPI**
```
http://localhost:8080/swagger.php
```

## Endpoints documentados

Los siguientes endpoints ya tienen anotaciones Swagger:

### Autenticación
- ✅ `POST /auth/register.php` - Registrar usuario
- ✅ `POST /auth/login.php` - Iniciar sesión
- ⚠️ `POST /auth/logout.php` - Cerrar sesión (pendiente)
- ⚠️ `GET /auth/verify.php` - Verificar token (pendiente)

### Productos
- ✅ `GET /products/list.php` - Listar productos
- ✅ `GET /products/get.php?id=xxx` - Obtener producto
- ✅ `GET /products/categories.php` - Listar categorías

### Carrito
- ✅ `GET /cart/get.php` - Obtener carrito
- ✅ `POST /cart/add.php` - Agregar al carrito
- ⚠️ `PUT /cart/update.php` - Actualizar cantidad (pendiente)
- ⚠️ `DELETE /cart/remove.php` - Eliminar del carrito (pendiente)
- ⚠️ `DELETE /cart/clear.php` - Vaciar carrito (pendiente)

### Pedidos
- ✅ `POST /orders/create.php` - Crear pedido
- ✅ `GET /orders/list.php` - Listar pedidos
- ⚠️ `GET /orders/get.php?id=xxx` - Obtener pedido (pendiente)

### Salud
- ✅ `GET /health.php` - Health check

## Agregar más anotaciones

Para documentar más endpoints, agrega anotaciones OpenAPI usando el formato:

```php
/**
 * @OA\Get(
 *     path="/tu-endpoint.php",
 *     tags={"Tu Tag"},
 *     summary="Descripción corta",
 *     description="Descripción detallada",
 *     security={{"bearerAuth": {}}},  // Solo si requiere autenticación
 *     @OA\Parameter(...),
 *     @OA\Response(...),
 *     @OA\Response(...)
 * )
 */
```

## Verificar que funciona

1. Instala Composer y las dependencias
2. Inicia el servidor backend:
   ```bash
   cd backend
   php -S localhost:8080 router.php
   ```
3. Abre en el navegador:
   - http://localhost:8080/swagger-ui.php

## Notas

- Las anotaciones Swagger se generan automáticamente desde los comentarios PHPDoc
- El JSON de Swagger se genera en tiempo real al acceder a `/swagger.php`
- Swagger UI permite probar los endpoints directamente desde el navegador
- Los endpoints marcados con ⚠️ aún no tienen anotaciones Swagger completas

