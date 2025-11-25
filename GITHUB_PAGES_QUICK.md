# ⚡ GitHub Pages - Inicio Rápido

## ✅ Respuesta Rápida

**SÍ, funciona perfectamente con GitHub Pages.** Tu backend y base de datos siguen funcionando igual.

## 🚀 Pasos Rápidos (5 minutos)

### 1. Activar GitHub Pages

1. Ve a: https://github.com/sdforero68/Final/settings/pages
2. En **"Source"**, selecciona:
   - **Branch:** `main`
   - **Folder:** `/frontend` ⚠️
3. Click **"Save"**
4. Espera 2 minutos
5. Tu sitio: `https://sdforero68.github.io/Final/`

### 2. Actualizar URL de la API

Edita `frontend/js/api/config.js`:

Busca esta línea (línea 18):
```javascript
const PRODUCTION_API_URL = 'https://anita-integrales-api.onrender.com/api';
```

**Cambia `anita-integrales-api.onrender.com` por la URL de tu backend en Render.**

### 3. Actualizar CORS en Backend

Edita `backend/api/config.php`:

Agrega en el array `$allowedOrigins`:
```php
'https://sdforero68.github.io',
```

### 4. Hacer Commit

```bash
git add .
git commit -m "Configurar para GitHub Pages"
git push origin main
```

**¡Listo!** GitHub Pages se actualiza automáticamente.

## 📱 Compartir tu Sitio

Tu sitio estará en:
```
https://sdforero68.github.io/Final/
```

Cualquier persona puede abrir este enlace desde cualquier dispositivo.

## 📚 Documentación Completa

Para más detalles, lee: `DEPLOYMENT_GITHUB_PAGES.md`

---

**¿Necesitas el backend?**  
Sigue usando Render o Railway. GitHub Pages solo sirve el frontend, y ese frontend se conecta a tu backend.

