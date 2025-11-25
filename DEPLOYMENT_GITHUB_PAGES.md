# 🚀 Deployment con GitHub Pages - Guía Completa

## ✅ Sí, Funciona Perfectamente

**GitHub Pages** solo sirve contenido estático (HTML, CSS, JavaScript), y tu frontend ya está configurado para hacer peticiones a la API. Por lo tanto:

✅ **Frontend en GitHub Pages** → Sirve tu HTML/CSS/JS  
✅ **Backend en Render** → Tu API PHP (ya configurada)  
✅ **Base de datos MySQL** → En la nube (ya configurada)  

**¡Todo funcionará perfectamente!**

## 📋 Pasos para Deployment con GitHub Pages

### Paso 1: Configurar GitHub Pages (5 minutos)

1. **Asegúrate de que tu código esté en GitHub:**
   ```bash
   cd /Users/sdforero/Desktop/web4/Integrales
   git add .
   git commit -m "Preparar para GitHub Pages"
   git push origin main
   ```

2. **En GitHub:**
   - Ve a tu repositorio: https://github.com/sdforero68/Final
   - Click en **"Settings"** (Configuración)
   - En el menú lateral, click en **"Pages"**
   - En **"Source"**, selecciona:
     - **Branch:** `main`
     - **Folder:** `/frontend` ⚠️ IMPORTANTE
   - Click **"Save"**

3. **Espera 2-3 minutos** y GitHub Pages se activará
4. Tu sitio estará en: `https://sdforero68.github.io/Final/`

### Paso 2: Backend en Render (Ya deberías tener esto)

Si aún no lo tienes:

1. Ve a: https://render.com
2. Crea un **Web Service** con:
   - **Root Directory:** `backend`
   - **Start Command:** `php -S 0.0.0.0:$PORT -t .`
   - **Environment Variables:** (tus credenciales de MySQL)
3. Tu API estará en: `https://anita-integrales-api.onrender.com`

### Paso 3: Base de Datos MySQL (Ya deberías tener esto)

- Usa: https://remotemysql.com o https://planetscale.com
- Ya deberías tener tus credenciales guardadas

### Paso 4: Actualizar URLs para Producción

#### 4.1. Actualizar URL de la API en el Frontend

Edita `frontend/js/api/config.js`:

```javascript
const getApiBaseUrl = () => {
  // Si estamos en localhost (desarrollo)
  if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    return 'http://localhost/backend/api';
  }
  
  // Si estamos en GitHub Pages (producción)
  // Cambia esta URL por la de tu backend en Render
  return 'https://anita-integrales-api.onrender.com/api'; // ← CAMBIA ESTA URL
};
```

#### 4.2. Actualizar CORS en el Backend

Edita `backend/api/config.php`:

```php
$allowedOrigins = [
    'http://localhost:8000',  // Desarrollo local
    'http://localhost',
    'https://sdforero68.github.io', // ← Agrega esta línea para GitHub Pages
    // Agregar más si necesitas
];
```

#### 4.3. Hacer commit y push

```bash
git add .
git commit -m "Configurar URLs para producción"
git push origin main
```

GitHub Pages se actualizará automáticamente en 1-2 minutos.

### Paso 5: Verificar que Todo Funciona

1. **Abre tu sitio:** `https://sdforero68.github.io/Final/`
2. **Prueba la API:** `https://anita-integrales-api.onrender.com/api/products/list.php`
3. **Verifica la consola del navegador** (F12) para ver si hay errores

## 🌐 Agregar Dominio Personalizado (Opcional)

### Opción 1: Subdominio en GitHub Pages

1. En GitHub → Settings → Pages
2. En "Custom domain", agrega tu dominio (ej: `anita-integrales.tk`)
3. Configura los DNS según las instrucciones de GitHub

### Opción 2: Dominio Gratis (Freenom)

1. Ve a: https://www.freenom.com
2. Registra un dominio `.tk` gratis
3. Configura DNS:
   - **Tipo:** CNAME
   - **Nombre:** `@` o `www`
   - **Valor:** `sdforero68.github.io`
4. En GitHub Pages, agrega tu dominio en Settings → Pages

## ✅ Ventajas de GitHub Pages

- ✅ **Completamente gratis**
- ✅ **HTTPS automático**
- ✅ **Subdominio incluido:** `tu-usuario.github.io/tu-repo`
- ✅ **Deploy automático** al hacer push
- ✅ **Sin límites de ancho de banda**
- ✅ **Funciona perfectamente** con tu backend en Render

## 🔧 Estructura de Rutas

### Desarrollo Local
- Frontend: `http://localhost:8000`
- Backend: `http://localhost/backend/api`

### Producción con GitHub Pages
- Frontend: `https://sdforero68.github.io/Final/`
- Backend: `https://anita-integrales-api.onrender.com/api`

## 📝 Notas Importantes

1. **GitHub Pages es estático:** No puede ejecutar PHP, solo sirve archivos HTML/CSS/JS
2. **Tu backend sigue en Render:** GitHub Pages solo sirve el frontend
3. **Las peticiones funcionan:** El frontend hace llamadas AJAX/fetch a tu API en Render
4. **CORS configurado:** El backend ya permite peticiones desde GitHub Pages

## 🐛 Solución de Problemas

### Error de CORS

Si ves errores de CORS en la consola:

1. Verifica que agregaste `https://sdforero68.github.io` en `backend/api/config.php`
2. Vuelve a desplegar en Render
3. Espera 2-3 minutos

### La API no responde

1. Verifica que tu backend en Render esté corriendo
2. Prueba la URL directamente: `https://anita-integrales-api.onrender.com/api/products/list.php`
3. Revisa los logs en Render

### Las imágenes no se muestran

Asegúrate de que las rutas de imágenes en el frontend sean relativas:
- ✅ Correcto: `./assets/images/logo.jpg`
- ❌ Incorrecto: `/assets/images/logo.jpg` (puede fallar en subdirectorios)

## 🎉 Resultado Final

Después de seguir estos pasos:

- ✅ Tu frontend estará en: `https://sdforero68.github.io/Final/`
- ✅ Tu backend seguirá en: `https://anita-integrales-api.onrender.com`
- ✅ Todo conectado y funcionando
- ✅ Cualquier persona puede acceder desde cualquier dispositivo

## 📚 Resumen

1. **GitHub Pages** → Frontend (HTML/CSS/JS) ✅
2. **Render** → Backend (PHP/API) ✅
3. **MySQL Cloud** → Base de datos ✅

**¡Todo funciona perfectamente!** 🚀

---

**¿Necesitas ayuda con algún paso? Todo está documentado arriba.** 📖

