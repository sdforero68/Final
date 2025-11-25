# 🚀 Configurar Despliegue en Netlify

Esta guía te ayudará a desplegar el frontend de Anita Integrales en Netlify.

## ⚠️ Importante

**Solo el frontend se despliega en Netlify.** El backend PHP permanece en Render (`https://final-1-0wvc.onrender.com`) y está configurado para funcionar desde ahí.

---

## 📋 Configuración en Netlify

Cuando Netlify te pida los datos de configuración, usa estos valores:

### 1️⃣ Branch to deploy
```
main
```
O el nombre de la rama que uses (generalmente `main` o `master`)

### 2️⃣ Base directory
```
frontend
```
Este es el directorio donde está el código del frontend.

### 3️⃣ Build command
```
(Deja este campo VACÍO)
```
No necesitas un comando de build porque el frontend es HTML/CSS/JS puro.

### 4️⃣ Publish directory
```
frontend
```
Este es el directorio que Netlify debe publicar (el mismo que Base directory).

### 5️⃣ Functions directory
```
(Deja este campo con el valor por defecto: netlify/functions)
```
O déjalo vacío si no usas funciones serverless.

---

## 🔧 Pasos Detallados

### Paso 1: Conectar el Repositorio

1. Ve a https://app.netlify.com/
2. Haz clic en **"Add new site"** → **"Import an existing project"**
3. Conecta tu cuenta de GitHub
4. Selecciona el repositorio: `sdforero68/Final`

### Paso 2: Configurar Build Settings

Cuando Netlify te muestre la configuración, completa así:

```
Branch to deploy: main
Base directory: frontend
Build command: (vacío)
Publish directory: frontend
```

### Paso 3: Desplegar

1. Haz clic en **"Deploy site"**
2. Espera 1-2 minutos
3. Tu sitio estará disponible en una URL como: `https://random-name-123.netlify.app`

---

## ✅ Verificación

Una vez desplegado:

1. **Abre tu sitio en Netlify**
2. **Prueba el login:**
   - Ve a la página de login
   - Intenta registrarte o iniciar sesión
   - Debería conectarse al backend en Render

3. **Verifica la consola del navegador:**
   - Presiona F12
   - Ve a la pestaña "Network"
   - Deberías ver requests a `https://final-1-0wvc.onrender.com`

---

## 🌐 Dominio Personalizado (Opcional)

Si quieres usar un dominio personalizado:

1. Ve a **Site settings** → **Domain management**
2. Haz clic en **"Add custom domain"**
3. Ingresa tu dominio
4. Sigue las instrucciones para configurar DNS

---

## 🔗 Configuración Actual

Tu aplicación está configurada así:

- **Frontend**: Netlify (este despliegue)
- **Backend**: Render (`https://final-1-0wvc.onrender.com`)
- **Base de datos**: FreeSQLDatabase.com (`sql10.freesqldatabase.com`)

El frontend ya está configurado para conectarse automáticamente al backend en Render cuando está en producción.

---

## 📝 Notas Importantes

1. **No necesitas cambiar el código** - El frontend detecta automáticamente si está en producción
2. **El backend permanece en Render** - No intentes desplegarlo en Netlify
3. **Netlify solo sirve archivos estáticos** - No ejecuta PHP

---

## ⚡ Archivo netlify.toml

He creado un archivo `netlify.toml` en la raíz del proyecto que automáticamente configura Netlify con los valores correctos. Si Netlify detecta este archivo, usará esos valores automáticamente.

---

## 🆘 Problemas Comunes

### El sitio carga pero la API no funciona

**Solución:** Verifica que el backend en Render esté funcionando:
```bash
curl https://final-1-0wvc.onrender.com
```

### Errores de CORS

**Solución:** El backend ya tiene CORS configurado. Si hay problemas, verifica que en `backend/api/config.php` esté incluido tu dominio de Netlify.

---

¡Listo! Tu sitio estará disponible en Netlify. 🎉

