# 🔐 Variables de Despliegue en Render - Configuración Completa

## 📋 Tu Docente Tenía Razón: Problemas de Permisos

El problema puede ser tanto de **variables de entorno** como de **permisos de archivos**. Aquí está la solución completa.

---

## ✅ Paso 1: Configurar Variables de Despliegue en Render

### Cómo Agregar Variables de Entorno

1. **Ve a Render Dashboard:**
   - Abre [dashboard.render.com](https://dashboard.render.com)
   - Selecciona tu servicio backend

2. **Ve a Environment Variables:**
   - En el menú lateral, haz clic en **"Environment"**
   - O ve directamente a: Settings → Environment

3. **Agrega estas variables una por una:**

   Haz clic en **"Add Environment Variable"** y agrega cada una:

   | Clave | Valor |
   |-------|-------|
   | `DB_HOST` | `sql10.freesqldatabase.com` |
   | `DB_PORT` | `3306` |
   | `DB_NAME` | `sql10809318` |
   | `DB_USER` | `sql10809318` |
   | `DB_PASSWORD` | `t3qD3KjUSe` |
   | `DB_SSL` | `false` |
   | `PORT` | `10000` |

4. **Importante:**
   - ✅ NO pongas espacios antes o después del `=`
   - ✅ NO pongas comillas alrededor de los valores
   - ✅ Respeta mayúsculas/minúsculas exactamente
   - ✅ Guarda cada variable antes de agregar la siguiente

5. **Guarda todos los cambios:**
   - Haz clic en **"Save Changes"**
   - Render reiniciará automáticamente el servicio

---

## 🔒 Paso 2: Verificar Permisos (Ya Corregido en Dockerfile)

Ya corregí el Dockerfile para que los permisos sean correctos. Los archivos ahora tendrán:

- **Archivos PHP:** Permisos `644` (lectura para todos, escritura para propietario)
- **Directorio:** Permisos `755` (lectura/ejecución para todos, escritura para propietario)

Esto asegura que PHP pueda leer todos los archivos necesarios.

---

## 📸 Captura de Pantalla de Cómo Debería Verse

En Render Environment, deberías ver algo así:

```
Environment Variables
┌─────────────────────┬──────────────────────────────────┐
│ DB_HOST             │ sql10.freesqldatabase.com       │
│ DB_NAME             │ sql10809318                      │
│ DB_PASSWORD         │ ••••••••••                       │
│ DB_PORT             │ 3306                             │
│ DB_SSL              │ false                            │
│ DB_USER             │ sql10809318                      │
│ PORT                │ 10000                            │
└─────────────────────┴──────────────────────────────────┘
```

---

## ✅ Paso 3: Verificar que las Variables Estén Configuradas

### Desde Render Dashboard:

1. Ve a tu servicio
2. Ve a **Environment**
3. Deberías ver las 7 variables listadas arriba

### Desde Logs (Después de Reiniciar):

En los logs de Render, deberías ver que el servicio inicia correctamente sin errores de conexión a la base de datos.

---

## 🧪 Paso 4: Probar la Configuración

Después de configurar las variables y que Render reinicie (espera 2-3 minutos):

### Test 1: Health Check

```bash
curl https://final-1-0wvc.onrender.com/health.php
```

**Debería responder:**
```json
{
  "status": "ok",
  "message": "Servidor funcionando",
  "php_version": "8.1.x"
}
```

### Test 2: Registrar Usuario

```bash
curl -X POST https://final-1-0wvc.onrender.com/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Usuario Prueba",
    "email": "prueba@ejemplo.com",
    "password": "123456"
  }'
```

**Debería responder:**
```json
{
  "success": true,
  "message": "Usuario registrado exitosamente",
  "data": {
    "token": "abc123...",
    "user": {...}
  }
}
```

---

## 🔍 Problemas Comunes y Soluciones

### ❌ Error: "database.php no encontrado"

**Causa:** Permisos incorrectos o archivo no copiado

**Solución:**
1. ✅ Ya corregido en el Dockerfile (permisos ahora son 644/755)
2. ✅ Variables de entorno configuradas (el código puede funcionar sin el archivo)

### ❌ Error: "Connection refused" o "Can't connect to database"

**Causa:** Variables de entorno mal configuradas

**Solución:**
1. Verifica que las variables estén escritas exactamente (sin espacios)
2. Verifica que el host sea: `sql10.freesqldatabase.com`
3. Verifica que el puerto sea: `3306`
4. Verifica que el nombre de la base de datos sea: `sql10809318`

### ❌ Error: "Access denied"

**Causa:** Usuario o contraseña incorrectos

**Solución:**
1. Usuario debe ser: `sql10809318`
2. Contraseña debe ser: `t3qD3KjUSe` (exactamente, respeta mayúsculas/minúsculas)

### ❌ El servicio no inicia

**Causa:** Error en variables de entorno o permisos

**Solución:**
1. Revisa los logs de Render para ver el error específico
2. Verifica que todas las variables estén configuradas
3. Verifica que no haya espacios extra en los valores

---

## 📝 Checklist Completo

- [ ] Variables de entorno configuradas en Render (7 variables)
- [ ] Valores copiados exactamente (sin espacios extra)
- [ ] Cambios guardados en Render
- [ ] Dockerfile actualizado con permisos correctos (ya hecho)
- [ ] Cambios subidos a Git (`git push`)
- [ ] Render reiniciado (espera 2-3 minutos)
- [ ] Health check funciona
- [ ] Registro de usuarios funciona

---

## 🎯 Resumen de Variables

**Copia y pega esto para referencia rápida:**

```
DB_HOST=sql10.freesqldatabase.com
DB_PORT=3306
DB_NAME=sql10809318
DB_USER=sql10809318
DB_PASSWORD=t3qD3KjUSe
DB_SSL=false
PORT=10000
```

---

## 💡 Tip Importante

**Las variables de entorno tienen PRIORIDAD sobre los archivos de configuración.**

Esto significa que:
- ✅ Si las variables están configuradas, Render las usará
- ✅ No necesitas el archivo `database.env` en producción
- ✅ Es más seguro (las credenciales no están en el código)

---

## 🔐 Seguridad

### ✅ Lo que SÍ debes hacer:

- ✅ Usar variables de entorno en Render (más seguro)
- ✅ No subir `database.env` al repositorio (ya está en .gitignore)
- ✅ Usar contraseñas fuertes

### ❌ Lo que NO debes hacer:

- ❌ Hardcodear credenciales en el código
- ❌ Compartir las credenciales públicamente
- ❌ Subir `database.env` a Git

---

## 📞 Siguiente Paso

Después de configurar las variables:

1. ✅ Espera 2-3 minutos a que Render reinicie
2. ✅ Prueba el health check
3. ✅ Prueba registrar un usuario
4. ✅ Verifica en phpMyAdmin que aparezcan los datos

¡Listo! 🎉

