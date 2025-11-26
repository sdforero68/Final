# 🔍 Diagnóstico: Por qué "Failed to fetch" en Swagger

## ❌ Error Actual

Cuando intentas registrar desde Swagger:
- **Error:** "Failed to fetch"
- **Swagger URL:** `https://final-1-0wvc.onrender.com/api/auth/register.php`
- **Servidor responde:** 404 Not Found

## 🔍 Causas Posibles

### 1. Servidor No Está Corriendo en Render

**Síntomas:**
- 404 en todas las rutas
- Health check no responde

**Solución:**
1. Ve a Render Dashboard
2. Verifica que el servicio esté **"Live"** (verde)
3. Si está "Paused" o "Build Failed", revísalo

### 2. Variables de Entorno No Configuradas

**Síntomas:**
- Servidor responde pero da errores de base de datos
- Logs muestran "database.php no encontrado"

**Solución:**
Configura estas variables en Render → Environment:
```
DB_HOST=sql10.freesqldatabase.com
DB_PORT=3306
DB_NAME=sql10809318
DB_USER=sql10809318
DB_PASSWORD=t3qD3KjUSe
DB_SSL=false
PORT=10000
```

### 3. Router No Maneja Correctamente las Rutas

**Síntomas:**
- 404 en rutas como `/api/auth/register.php`
- Health check no funciona

**Solución:**
Ya corregido el router. Sube los cambios:
```bash
git add backend/router.php
git commit -m "Fix router"
git push origin main
```

### 4. CORS Bloqueando la Petición

**Síntomas:**
- Error "Failed to fetch" en el navegador
- Pero curl funciona

**Solución:**
CORS ya está configurado. Si Swagger está en el mismo dominio (`final-1-0wvc.onrender.com`), no debería haber problema.

---

## ✅ Pasos de Diagnóstico

### Paso 1: Verificar Estado del Servidor

```bash
# Ver si el servidor responde
curl -I https://final-1-0wvc.onrender.com/

# Debería responder con HTTP 200 o 404 (no error de conexión)
```

### Paso 2: Probar Health Check

```bash
curl https://final-1-0wvc.onrender.com/health.php
```

**Si responde JSON:** ✅ Servidor funciona  
**Si responde 404:** ❌ Router no funciona  
**Si no responde:** ❌ Servidor no está corriendo

### Paso 3: Probar Registro Directo

```bash
curl -X POST https://final-1-0wvc.onrender.com/api/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"123456"}'
```

**Si responde JSON (éxito o error de validación):** ✅ Todo funciona  
**Si responde 404:** ❌ Router no funciona  
**Si responde error de BD:** ❌ Variables de entorno faltantes

---

## 🚀 Solución Completa

### 1. Configurar Variables de Entorno en Render

**Render Dashboard → Tu Servicio → Environment:**

Agrega estas 7 variables:
```
DB_HOST=sql10.freesqldatabase.com
DB_PORT=3306
DB_NAME=sql10809318
DB_USER=sql10809318
DB_PASSWORD=t3qD3KjUSe
DB_SSL=false
PORT=10000
```

**Guarda** y espera 2-3 minutos.

### 2. Subir Cambios del Router

```bash
cd /Users/sdforero/Desktop/web4/Integrales
git add backend/router.php backend/Dockerfile backend/api/config.php
git commit -m "Fix: Correcciones para Render (router, permisos, config)"
git push origin main
```

Espera 2-3 minutos a que Render se actualice.

### 3. Verificar

```bash
# Health check
curl https://final-1-0wvc.onrender.com/health.php

# Si responde JSON, prueba registro
curl -X POST https://final-1-0wvc.onrender.com/api/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"123456"}'
```

---

## 📋 Checklist Final

- [ ] Servidor en Render está "Live"
- [ ] Variables de entorno configuradas (7 variables)
- [ ] Cambios del router subidos a Git
- [ ] Render reiniciado (espera 2-3 minutos)
- [ ] Health check funciona (responde JSON)
- [ ] Registro funciona desde curl
- [ ] Swagger funciona (puede registrar usuarios)

---

## 💡 Orden de Prioridad

1. **PRIMERO:** Verificar que el servidor esté "Live" en Render
2. **SEGUNDO:** Configurar variables de entorno
3. **TERCERO:** Subir cambios del router
4. **CUARTO:** Probar desde curl
5. **QUINTO:** Probar desde Swagger

---

## 🎯 Resumen

El error "Failed to fetch" probablemente es porque:
- El servidor devuelve 404 (router no funciona o servidor no está corriendo)
- O las variables de entorno no están configuradas

**Empieza verificando que el servidor esté "Live" en Render y que las variables de entorno estén configuradas.**

