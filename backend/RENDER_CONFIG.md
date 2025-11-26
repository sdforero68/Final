# 🔧 Configuración para Render.com

## Variables de Entorno Requeridas

Configura estas variables de entorno en Render para que la base de datos funcione:

### En Render Dashboard → Tu Servicio → Environment

```
DB_HOST=tu-host-de-base-de-datos
DB_PORT=3306
DB_NAME=anita_integrales
DB_USER=tu-usuario
DB_PASSWORD=tu-contraseña
DB_SSL=false
```

### Ejemplo con servicios populares:

**Si usas Remotemysql.com:**
```
DB_HOST=remotemysql.com
DB_PORT=3306
DB_NAME=tu_nombre_db
DB_USER=tu_usuario
DB_PASSWORD=tu_password
DB_SSL=false
```

**Si usas PlanetScale o similar con SSL:**
```
DB_HOST=tu-host.planetscale.net
DB_PORT=3306
DB_NAME=tu_database
DB_USER=tu_usuario
DB_PASSWORD=tu_password
DB_SSL=true
```

## 📝 Pasos para Configurar en Render

1. Ve a tu servicio en Render Dashboard
2. Click en "Environment"
3. Agrega cada variable de entorno:
   - `DB_HOST` → Host de tu base de datos
   - `DB_PORT` → Puerto (generalmente 3306)
   - `DB_NAME` → Nombre de la base de datos
   - `DB_USER` → Usuario de la base de datos
   - `DB_PASSWORD` → Contraseña de la base de datos
   - `DB_SSL` → `true` o `false` según tu proveedor

4. Guarda los cambios
5. Render reiniciará automáticamente tu servicio

## 🔍 Verificar Configuración

Una vez desplegado, verifica que funciona:

```bash
# Health check
curl https://tu-url-en-render.com/health.php

# Verificar productos (debe conectar a la BD)
curl https://tu-url-en-render.com/products/list.php
```

## 🐛 Solución de Problemas

### Error: "Error de configuración del servidor"
- Verifica que todas las variables de entorno estén configuradas
- Verifica que los valores sean correctos (sin espacios extra)
- Revisa los logs de Render para ver el error específico

### Error: "Error de conexión a la base de datos"
- Verifica que `DB_HOST` sea correcto
- Verifica que `DB_USER` y `DB_PASSWORD` sean correctos
- Verifica que la base de datos permita conexiones desde Render (whitelist IPs)
- Si tu proveedor requiere SSL, configura `DB_SSL=true`

### Swagger no funciona
- Verifica que Composer se haya instalado (debe aparecer en logs de build)
- Verifica que `vendor/autoload.php` existe
- Revisa los logs de Render para errores de PHP

## ✅ Checklist de Despliegue

- [ ] Variables de entorno configuradas en Render
- [ ] Base de datos accesible desde Render (verificar firewall/whitelist)
- [ ] Dockerfile incluye instalación de Composer
- [ ] Composer instala dependencias correctamente
- [ ] Health check responde correctamente
- [ ] Endpoints de API responden correctamente
- [ ] Swagger UI accesible

## 📚 Archivos Importantes

- `backend/Dockerfile` - Configuración del contenedor
- `backend/api/database.php` - Conexión a BD (lee variables de entorno)
- `backend/router.php` - Manejo de rutas
- `backend/api/config.php` - Configuración general y CORS

