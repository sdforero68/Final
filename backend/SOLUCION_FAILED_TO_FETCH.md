# 🔧 Solución al Error "Failed to fetch" en Swagger

## ❌ Problema Actual

Al intentar registrar un usuario desde Swagger, aparece:
- **Error:** "Failed to fetch"
- **Posibles causas:** CORS, Network Failure

## 🔍 Diagnóstico

El servidor en Render está devolviendo **404 Not Found** para `/api/auth/register.php`. Esto significa que:

1. ❌ El router no está encontrando el archivo
2. ❌ O el servidor no está corriendo correctamente

## ✅ Soluciones

### Solución 1: Verificar que el Servidor Esté Corriendo en Render

1. **Ve a Render Dashboard:**
   - Abre tu servicio backend
   - Verifica que el estado sea **"Live"** (verde) y no "Paused" o "Build Failed"

2. **Revisa los Logs:**
   - Ve a la pestaña **"Logs"**
   - Busca errores de conexión o problemas al iniciar
   - Si ves errores de `database.php`, las variables de entorno no están configuradas

### Solución 2: Configurar Variables de Entorno (CRÍTICO)

Si el servidor no puede conectarse a la base de datos, fallará. Verifica:

1. **Render Dashboard → Environment:**
   ```
   DB_HOST=sql10.freesqldatabase.com
   DB_PORT=3306
   DB_NAME=sql10809318
   DB_USER=sql10809318
   DB_PASSWORD=t3qD3KjUSe
   DB_SSL=false
   PORT=10000
   ```

2. **Reinicia el servicio** después de agregar las variables

### Solución 3: Probar la URL Correcta

El router debería manejar estas rutas:

✅ **URLs que deberían funcionar:**
- `https://final-1-0wvc.onrender.com/api/auth/register.php`
- `https://final-1-0wvc.onrender.com/api/health.php`
- `https://final-1-0wvc.onrender.com/auth/register.php` (sin /api/)

### Solución 4: Verificar CORS

El error "Failed to fetch" puede ser CORS. Ya está configurado en `config.php`, pero verifica que Swagger UI esté en un origen permitido.

**Agregar Swagger a orígenes permitidos:**

Si Swagger está en `https://final-1-0wvc.onrender.com/swagger-ui.php`, ya debería estar permitido.

---

## 🧪 Pruebas

### Test 1: Health Check

```bash
curl https://final-1-0wvc.onrender.com/health.php
```

**Si devuelve 404:** El servidor no está usando el router correctamente.

**Si devuelve JSON con status "ok":** El servidor funciona, pero hay problema con las rutas.

### Test 2: Registro Directo

```bash
curl -X POST https://final-1-0wvc.onrender.com/api/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"123456"}'
```

**Si devuelve 404:** Problema con el router.

**Si devuelve error de base de datos:** Variables de entorno no configuradas.

**Si devuelve JSON con éxito:** Todo funciona, problema es solo CORS en Swagger.

---

## 🔧 Correcciones Aplicadas

1. ✅ **Router mejorado** - Maneja mejor las rutas con `/api/`
2. ✅ **Dockerfile con permisos** - Archivos tienen permisos correctos
3. ✅ **Config.php robusto** - Maneja mejor errores de database.php

---

## 📋 Checklist

- [ ] Servidor en Render está "Live" (verde)
- [ ] Variables de entorno configuradas (7 variables)
- [ ] Logs de Render no muestran errores críticos
- [ ] Router actualizado (cambios subidos a Git)
- [ ] Health check responde (no 404)
- [ ] Registro funciona desde curl

---

## 🚀 Próximos Pasos

1. **Verifica el estado del servidor en Render**
2. **Configura las variables de entorno si no están**
3. **Sube los cambios del router a Git:**
   ```bash
   git add backend/router.php
   git commit -m "Fix: Mejorar manejo de rutas en router"
   git push origin main
   ```
4. **Espera 2-3 minutos** a que Render se actualice
5. **Prueba nuevamente desde Swagger**

---

## 💡 Si Sigue Fallando

### Ver Logs de Render:

1. Ve a tu servicio en Render
2. Pestaña **"Logs"**
3. Busca errores específicos
4. Comparte el error para más ayuda

### Verificar URL:

Asegúrate de que en Swagger uses la URL exacta:
```
https://final-1-0wvc.onrender.com/api/auth/register.php
```

---

## 🎯 Resumen

El error "Failed to fetch" puede ser:
1. **Servidor no responde** (404) → Verifica que esté Live
2. **Variables de entorno faltantes** → Configúralas en Render
3. **Router no funciona** → Ya corregido, sube los cambios
4. **CORS** → Ya configurado, pero puede necesitar ajustes

**Primero verifica que el servidor esté funcionando con curl, luego prueba desde Swagger.**

