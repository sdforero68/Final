# ⚡ Pasos Rápidos para Desplegar en Render

## 🎯 Resumen Ejecutivo

### 1. Crear Base de Datos (5 minutos)

Ve a https://remotemysql.com y crea una base de datos. Guarda:
- Host: `remotemysql.com`
- Puerto: `3306`
- Usuario, Contraseña, Nombre de BD

### 2. Crear Tablas (2 minutos)

Accede a tu base de datos (phpMyAdmin o similar) y ejecuta:
```
backend/sql/init.sql
```

O ejecuta cada archivo de `tables/` en orden (01_users.sql, 02_sessions.sql, etc.)

### 3. Configurar Render (5 minutos)

1. Ve a https://dashboard.render.com
2. "New +" → "Web Service"
3. Conecta tu repositorio GitHub
4. Configura:
   - **Root Directory**: `backend` ⚠️ IMPORTANTE
   - **Runtime**: `Docker`
   - **Branch**: `main`

### 4. Variables de Entorno en Render

En Render → Environment, agrega:

```
DB_HOST=remotemysql.com
DB_PORT=3306
DB_NAME=tu_nombre_bd
DB_USER=tu_usuario
DB_PASSWORD=tu_contraseña
DB_SSL=false
PORT=10000
```

### 5. Desplegar

Click en "Create Web Service" y espera 5-10 minutos.

### 6. Verificar

Una vez desplegado, prueba:
- Health: `https://tu-url.onrender.com/health.php`
- Swagger: `https://tu-url.onrender.com/swagger-ui.php`
- Productos: `https://tu-url.onrender.com/products/list.php`

### 7. Agregar Productos

Ejecuta en tu base de datos:
```
backend/sql/populate_products.sql
```

O agrega productos manualmente desde la aplicación.

---

**📖 Guía Completa:** Lee `GUIA_DESPLIEGUE_RENDER.md` para más detalles

