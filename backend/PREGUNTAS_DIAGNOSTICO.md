# ❓ Preguntas de Diagnóstico

Para identificar el problema exacto, necesito saber:

## 🔍 Información Crítica

### 1. ¿Qué error específico ves?

**A) En Swagger:**
- [ ] "Failed to fetch"
- [ ] Error 404
- [ ] Error 500
- [ ] Otro: _______________

**B) En los Logs de Render:**
- Ve a Render Dashboard → Tu Servicio → Pestaña "Logs"
- ¿Qué errores ves? Copia los últimos 5-10 líneas de error

### 2. ¿El servidor está corriendo en Render?

- Ve a Render Dashboard
- ¿El estado es **"Live"** (verde) o está "Paused"/"Build Failed"?

### 3. ¿Configuraste las variables de entorno?

- Ve a Render → Environment
- ¿Ves estas 7 variables configuradas?
  ```
  DB_HOST
  DB_PORT
  DB_NAME
  DB_USER
  DB_PASSWORD
  DB_SSL
  PORT
  ```

### 4. ¿Creaste las tablas en phpMyAdmin?

- En phpMyAdmin, ¿ves estas tablas en el menú lateral?
  - users
  - sessions
  - categories
  - products
  - cart_items
  - orders
  - order_items
  - favorites

### 5. ¿Qué respuesta obtienes cuando pruebas con curl?

Ejecuta esto en tu terminal:

```bash
curl -X POST https://final-1-0wvc.onrender.com/api/auth/register.php \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"123456"}'
```

¿Qué respuesta obtienes? (copia y pega la respuesta completa)

---

## 🚨 Errores Comunes y Soluciones

### Error: "database.php no encontrado"

**Causa:** Variables de entorno no configuradas

**Solución:**
1. Render → Environment
2. Agrega las 7 variables
3. Guarda y espera 2-3 minutos

### Error: 404 Not Found

**Causa:** Router no funciona o ruta incorrecta

**Solución:**
1. Verifica que el servidor esté "Live"
2. Prueba rutas alternativas:
   - `/health.php`
   - `/api/health.php`
   - `/auth/register.php`

### Error: "Connection refused" o "Can't connect to database"

**Causa:** Credenciales incorrectas o BD no accesible

**Solución:**
1. Verifica que las credenciales sean exactas (sin espacios)
2. Verifica que la base de datos exista
3. Verifica que puedas conectarte desde phpMyAdmin

### Error: "Failed to fetch" en Swagger

**Causa:** CORS o servidor no responde

**Solución:**
1. Primero prueba con curl (sin CORS)
2. Si curl funciona, es problema de CORS
3. Si curl no funciona, es problema del servidor

---

## 🔧 Pasos de Diagnóstico Rápido

### Paso 1: Ejecutar Script de Diagnóstico

```bash
cd /Users/sdforero/Desktop/web4/Integrales/backend
./DIAGNOSTICO_RAPIDO.sh
```

Este script probará automáticamente varios endpoints y te dirá qué funciona y qué no.

### Paso 2: Revisar Logs de Render

1. Render Dashboard → Tu Servicio
2. Pestaña **"Logs"**
3. Busca líneas rojas (errores)
4. Copia los últimos errores

### Paso 3: Verificar Variables de Entorno

1. Render Dashboard → Tu Servicio → **Environment**
2. Verifica que existan estas 7 variables
3. Verifica que los valores sean correctos (sin espacios extras)

---

## 📝 Información que Necesito

Para ayudarte mejor, necesito:

1. **Estado del servidor en Render** (Live/Paused/Failed)
2. **Últimos 10-20 líneas de los Logs de Render** (copia y pega)
3. **Respuesta de curl** (el comando de arriba)
4. **Screenshot o descripción** del error que ves en Swagger
5. **¿Las tablas existen en phpMyAdmin?** (Sí/No y cuáles)

Con esta información podré darte una solución específica.

---

## 🆘 Si Nada Funciona

Si después de seguir todos los pasos nada funciona:

1. **Verifica que el repositorio esté conectado correctamente en Render**
   - Render → Settings → Build & Deploy
   - Verifica que el Root Directory sea `backend`
   - Verifica que el Build Command esté vacío o sea correcto

2. **Verifica que el Dockerfile esté en el lugar correcto**
   - Debe estar en: `backend/Dockerfile`

3. **Intenta un "Manual Deploy" en Render**
   - Render → Manual Deploy → Deploy latest commit

4. **Revisa que no haya errores de build**
   - En la pestaña "Events" de Render, verifica que el build haya sido exitoso




