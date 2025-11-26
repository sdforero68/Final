# 📚 Cómo Usar Swagger UI - Guía Rápida

## 🔐 Autorización en Swagger

### ¿Qué es esta ventana?

La ventana modal de "Available authorizations" te permite agregar un token de autenticación para probar endpoints que requieren estar logueado (como carrito, pedidos, etc.).

### ¿Cómo obtener el token?

#### Opción 1: Desde Swagger (Recomendado)

1. **Cierra esta ventana** (Click en "Close" o X)

2. **Ve a la sección "Autenticación"**

3. **Expande el endpoint `POST /auth/login.php`**

4. **Haz click en "Try it out"**

5. **Ingresa tus credenciales:**
   ```json
   {
     "email": "tu-email@ejemplo.com",
     "password": "tu-contraseña"
   }
   ```

6. **Click en "Execute"**

7. **Copia el token** de la respuesta (está en `data.token`)

8. **Vuelve al botón "Authorize"** (arriba a la derecha, con el candado 🔒)

9. **Pega el token** en el campo "Value"

10. **Click en "Authorize"**

11. **Click en "Close"**

¡Ahora puedes probar todos los endpoints que requieren autenticación!

#### Opción 2: Desde la aplicación frontend

1. Ve a tu aplicación (localhost o producción)
2. Inicia sesión
3. Abre las herramientas de desarrollador (F12)
4. Ve a la pestaña "Application" → "Local Storage"
5. Busca la clave `accessToken` o `current_session`
6. Copia el valor (ese es tu token)

### ¿Cómo usar el token después?

Una vez que hayas agregado el token:

1. **No necesitas volver a autorizar** - El token se guarda en la sesión
2. **Prueba cualquier endpoint protegido:**
   - `GET /cart/get.php` - Ver tu carrito
   - `POST /cart/add.php` - Agregar productos
   - `GET /orders/list.php` - Ver tus pedidos
   - `POST /orders/create.php` - Crear un pedido

3. **Los endpoints públicos NO necesitan token:**
   - `GET /products/list.php` - Listar productos
   - `GET /products/get.php` - Ver un producto
   - `GET /products/categories.php` - Ver categorías
   - `GET /health.php` - Health check

### ¿No tienes un usuario aún?

#### Crea uno desde Swagger:

1. Ve a `POST /auth/register.php`
2. Click en "Try it out"
3. Ingresa tus datos:
   ```json
   {
     "name": "Tu Nombre",
     "email": "tu-email@ejemplo.com",
     "password": "tu-contraseña",
     "phone": "+573001234567"
   }
   ```
4. Click en "Execute"
5. El token se devuelve automáticamente - cópialo y úsalo

### 🔄 Renovar el token

Si el token expira o necesitas cambiarlo:

1. Vuelve a hacer login (`POST /auth/login.php`)
2. Copia el nuevo token
3. Click en "Authorize" de nuevo
4. Pega el nuevo token
5. Click en "Authorize"

### 💡 Tip

**Para facilitar el proceso:**
- Después de hacer login en Swagger, el token aparece en la respuesta
- Copia TODO el token (puede ser largo, tipo: `abc123def456...`)
- No incluyas la palabra "Bearer", solo el token
- Swagger agregará automáticamente "Bearer " antes del token

### ⚠️ Nota importante

- El token tiene una duración limitada (30 días por defecto)
- Si cambias de navegador o limpias el cache, perderás la autorización
- Para producción, asegúrate de usar HTTPS para proteger los tokens

