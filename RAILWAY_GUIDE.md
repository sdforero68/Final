# 🚂 Guía para Desplegar Backend en Railway

Railway es **mucho más simple** que Render para PHP. Soporta PHP nativo sin necesidad de Docker.

## ✅ Paso 1: Crear Cuenta en Railway

1. Ve a https://railway.app
2. Haz click en **"Start a New Project"**
3. Conecta con tu cuenta de GitHub
4. Autoriza a Railway a acceder a tus repositorios

## ✅ Paso 2: Crear Nuevo Proyecto

1. Click en **"New Project"**
2. Selecciona **"Deploy from GitHub repo"**
3. Selecciona tu repositorio: `sdforero68/Final`
4. Railway detectará automáticamente que es un proyecto PHP

## ✅ Paso 3: Configurar el Servicio

Railway debería detectar PHP automáticamente. Si no:

1. En el servicio creado, ve a **Settings**
2. **Root Directory:** `backend`
3. **Start Command:** `php -S 0.0.0.0:$PORT -t api`

## ✅ Paso 4: Configurar Variables de Entorno

1. En el servicio, ve a la pestaña **Variables**
2. Click en **"+ New Variable"**
3. Agrega cada una:

```
DB_HOST = sql10.freesqldatabase.com
DB_NAME = sql10809318
DB_USER = sql10809318
DB_PASSWORD = t3qD3KjUSe
DB_PORT = 3306
```

## ✅ Paso 5: Deploy

1. Railway debería hacer el deploy automáticamente
2. Ve a la pestaña **Deployments** para ver el progreso
3. Cuando termine, click en **"Settings"** → **"Generate Domain"**
4. Copia la URL que Railway te da (algo como `anita-integrales-backend-production.up.railway.app`)

## ✅ Paso 6: Actualizar Frontend

Una vez tengas la URL del backend:

1. Edita `frontend/js/api/config.js`
2. Cambia `PRODUCTION_API_URL` a tu URL de Railway + `/api`

Ejemplo:
```javascript
const PRODUCTION_API_URL = 'https://anita-integrales-backend-production.up.railway.app/api';
```

## 🎉 ¡Listo!

Tu backend estará funcionando en Railway. Es mucho más simple que Render para PHP.

## 📋 Alternativas si Railway no te funciona:

### Opción 2: Fly.io
- Ve a https://fly.io
- Similar a Railway pero con CLI

### Opción 3: 000webhost (Gratis, PHP nativo)
- Ve a https://www.000webhost.com
- Sube los archivos del backend manualmente
- Muy simple pero menos profesional

### Opción 4: InfinityFree (Gratis, PHP nativo)
- Ve a https://infinityfree.net
- Similar a 000webhost

**Recomendación: Railway es la mejor opción para empezar.**

