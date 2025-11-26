# 🚀 Guía Completa: Desplegar en Render y Configurar Base de Datos

## 📋 Checklist Pre-Despliegue

- [ ] Código subido a GitHub
- [ ] Base de datos creada en tu proveedor (Remotemysql, PlanetScale, etc.)
- [ ] Tener credenciales de la base de datos listas

## Paso 1: Crear Base de Datos en la Nube

### Opción A: Remotemysql.com (Gratuito)

1. Ve a https://remotemysql.com
2. Crea una cuenta o inicia sesión
3. Crea una nueva base de datos
4. Guarda las credenciales:
   - **Host**: `remotemysql.com`
   - **Puerto**: `3306`
   - **Usuario**: (el que te dan)
   - **Contraseña**: (la que creaste)
   - **Nombre de la BD**: (el que creaste)

### Opción B: PlanetScale (Gratuito con límites)

1. Ve a https://planetscale.com
2. Crea una cuenta
3. Crea una nueva base de datos
4. Obtén las credenciales de conexión

## Paso 2: Crear Tablas en la Base de Datos

### Método 1: Usando phpMyAdmin o cliente MySQL

1. Accede a tu base de datos (phpMyAdmin, MySQL Workbench, etc.)
2. Ejecuta el archivo SQL completo:

```sql
-- Copia y pega el contenido de backend/sql/init.sql
-- O ejecuta cada tabla desde tables/*.sql en orden
```

### Método 2: Desde línea de comandos

```bash
# Si tienes acceso SSH o terminal a tu servidor de BD
mysql -h TU_HOST -u TU_USUARIO -p TU_BASE_DE_DATOS < backend/sql/init.sql
```

### Archivos SQL a ejecutar en orden:

1. `tables/01_users.sql` - Tabla de usuarios
2. `tables/02_sessions.sql` - Tabla de sesiones
3. `tables/03_categories.sql` - Tabla de categorías
4. `tables/04_products.sql` - Tabla de productos
5. `tables/05_cart_items.sql` - Tabla de carrito
6. `tables/06_orders.sql` - Tabla de pedidos
7. `tables/07_order_items.sql` - Tabla de items de pedidos
8. `tables/08_favorites.sql` - Tabla de favoritos

**O simplemente ejecuta:** `backend/sql/init.sql` que incluye todo.

## Paso 3: Poblar Datos Iniciales

### Categorías se crean automáticamente

El archivo `init.sql` ya incluye las categorías básicas.

### Productos

Necesitas insertar los productos. Puedes:

1. **Desde phpMyAdmin/MySQL Workbench**: Ejecutar un script SQL con los productos
2. **Desde la aplicación**: Usar un endpoint de admin (si lo creas)
3. **Manual**: Insertar productos uno por uno

**Ejemplo de inserción de producto:**

```sql
INSERT INTO products (code, name, category_id, price, description, image) 
VALUES (
    'ACH001', 
    'Achiras Grandes', 
    (SELECT id FROM categories WHERE code = 'panaderia'), 
    15000.00,
    'Deliciosas achiras artesanales',
    '/assets/images/Catálogo/AchirasGrandes.jpg'
);
```

## Paso 4: Configurar Render

### 4.1 Crear Servicio en Render

1. Ve a https://dashboard.render.com
2. Click en "New +" → "Web Service"
3. Conecta tu repositorio de GitHub
4. Selecciona el repositorio con tu código
5. Configura:
   - **Name**: `anita-integrales-api` (o el nombre que quieras)
   - **Root Directory**: `backend` ⚠️ **IMPORTANTE**
   - **Runtime**: `Docker`
   - **Region**: El más cercano a ti
   - **Branch**: `main` (o la rama que uses)

### 4.2 Variables de Entorno en Render

En la sección **Environment**, agrega estas variables:

```
DB_HOST=remotemysql.com
DB_PORT=3306
DB_NAME=tu_nombre_de_base_de_datos
DB_USER=tu_usuario
DB_PASSWORD=tu_contraseña
DB_SSL=false
PORT=10000
```

**⚠️ IMPORTANTE:**
- Reemplaza los valores con los de tu base de datos
- Si usas PlanetScale, `DB_SSL=true`
- `PORT` debe ser el puerto que Render asigna (generalmente 10000 o el que te indiquen)

### 4.3 Configurar Build

Render usará el `Dockerfile` automáticamente. Asegúrate de que:
- El Dockerfile está en la carpeta `backend/`
- El archivo `composer.json` está en `backend/`

## Paso 5: Desplegar

1. Click en "Create Web Service"
2. Render comenzará a construir la imagen
3. Espera a que termine el build (puede tardar 5-10 minutos)
4. Una vez desplegado, Render te dará una URL como: `https://tu-servicio.onrender.com`

## Paso 6: Verificar que Funciona

### 6.1 Health Check

```bash
curl https://tu-servicio.onrender.com/health.php
```

Debería responder:
```json
{
  "status": "ok",
  "message": "Servidor funcionando",
  "timestamp": "...",
  "php_version": "8.1.x"
}
```

### 6.2 Verificar Base de Datos

```bash
curl https://tu-servicio.onrender.com/products/list.php
```

Si hay productos, deberías ver un JSON con el array de productos.
Si no hay productos, verás: `{"success":true,"data":[]}`

### 6.3 Verificar Swagger

Abre en el navegador:
```
https://tu-servicio.onrender.com/swagger-ui.php
```

Deberías ver la documentación de Swagger con todos los endpoints.

## Paso 7: Actualizar Frontend

Una vez que tengas la URL de Render, actualiza el frontend:

1. Edita `frontend/js/api/config.js`
2. Cambia la URL de producción:

```javascript
const PRODUCTION_API_URL = 'https://tu-servicio.onrender.com';
```

3. Haz commit y push
4. GitHub Pages se actualizará automáticamente

## 🐛 Solución de Problemas

### Error: "Error de configuración del servidor"

**Causa:** Variables de entorno no configuradas o incorrectas

**Solución:**
1. Ve a Render → Environment
2. Verifica que todas las variables estén escritas correctamente
3. No dejes espacios antes o después de los valores
4. Verifica que los nombres de las variables sean exactamente: `DB_HOST`, `DB_NAME`, etc.

### Error: "Error de conexión a la base de datos"

**Causa:** Credenciales incorrectas o firewall bloqueando conexiones

**Solución:**
1. Verifica las credenciales en tu proveedor de BD
2. Algunos servicios requieren whitelist de IPs:
   - Remotemysql: Permite conexiones desde cualquier IP
   - Otros: Puede que necesites agregar la IP de Render

### No aparecen productos

**Causa:** Base de datos vacía

**Solución:**
1. Verifica que las tablas existan (ejecuta `init.sql`)
2. Inserta productos manualmente o con script SQL
3. Verifica que la categoría tenga productos asociados

### Swagger no carga

**Causa:** Composer no instaló dependencias

**Solución:**
1. Revisa los logs de build en Render
2. Verifica que `composer.json` existe
3. Verifica que el Dockerfile instala Composer correctamente

## 📝 Resumen de URLs

Una vez desplegado:

- **API Health**: `https://tu-servicio.onrender.com/health.php`
- **Productos**: `https://tu-servicio.onrender.com/products/list.php`
- **Swagger UI**: `https://tu-servicio.onrender.com/swagger-ui.php`
- **Swagger JSON**: `https://tu-servicio.onrender.com/swagger.php`

## ✅ Checklist Final

- [ ] Base de datos creada
- [ ] Tablas creadas (ejecutado init.sql)
- [ ] Categorías insertadas
- [ ] Productos insertados (opcional, puedes agregarlos después)
- [ ] Servicio creado en Render
- [ ] Variables de entorno configuradas
- [ ] Deploy exitoso
- [ ] Health check funciona
- [ ] Swagger funciona
- [ ] Frontend actualizado con nueva URL

## 🎉 ¡Listo!

Tu API debería estar funcionando en Render. Si tienes problemas, revisa los logs en Render Dashboard → Logs.

