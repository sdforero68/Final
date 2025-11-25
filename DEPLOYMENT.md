# 🚀 Guía de Deployment - Anita Integrales

Esta guía te ayudará a publicar tu sitio web en internet con un dominio gratuito para que cualquier persona pueda acceder desde cualquier dispositivo.

## 📋 Opciones de Hosting Gratuito

### Opción 1: Vercel + Render (Recomendado) ⭐

**Ventajas:**
- Gratis y fácil de usar
- Dominio gratuito incluido (`.vercel.app` o dominio personalizado)
- Despliegue automático desde GitHub
- Soporta frontend estático y backend PHP

**Paso a paso:**

#### 1. Frontend en Vercel

1. **Sube tu código a GitHub:**
   ```bash
   cd /Users/sdforero/Desktop/web4/Integrales
   git add .
   git commit -m "Preparar para deployment"
   git push origin main
   ```

2. **Ve a Vercel:**
   - Visita: https://vercel.com
   - Inicia sesión con GitHub
   - Click en "Add New Project"
   - Selecciona tu repositorio `Final`
   - Configuración:
     - **Framework Preset:** Other
     - **Root Directory:** `frontend`
     - **Build Command:** (dejar vacío)
     - **Output Directory:** `frontend`
   - Click "Deploy"
   - Tu sitio estará en: `https://tu-proyecto.vercel.app`

#### 2. Backend en Render

1. **Ve a Render:**
   - Visita: https://render.com
   - Crea cuenta gratuita

2. **Crea un Web Service:**
   - Click "New +" → "Web Service"
   - Conecta tu repositorio de GitHub
   - Configuración:
     - **Name:** `anita-integrales-api`
     - **Environment:** `PHP`
     - **Root Directory:** `backend`
     - **Build Command:** `composer install` (si usas Composer) o dejar vacío
     - **Start Command:** `php -S 0.0.0.0:$PORT -t .`
   - Variables de Entorno:
     ```
     DB_HOST=tu-host-mysql
     DB_PORT=3306
     DB_NAME=anita_integrales
     DB_USER=tu-usuario
     DB_PASSWORD=tu-contraseña
     ```
   - Click "Create Web Service"

3. **Obtén la URL de tu API:**
   - Render te dará una URL como: `https://anita-integrales-api.onrender.com`

#### 3. Configurar Base de Datos MySQL en la Nube

**Opción A: Render MySQL (Gratis por 90 días)**

1. En Render, click "New +" → "PostgreSQL" (o busca MySQL)
2. Render ofrece PostgreSQL gratis, pero necesitas MySQL
3. Alternativa: Usa **PlanetScale** o **Aiven** (tienen planes gratuitos)

**Opción B: PlanetScale (Recomendado para MySQL gratis)**

1. Ve a: https://planetscale.com
2. Crea cuenta gratuita
3. Crea una nueva base de datos
4. Obtén las credenciales de conexión
5. Actualiza las variables de entorno en Render

**Opción C: Free MySQL Hosting**

- **DB4Free:** https://db4free.net
- **Remotemysql:** https://remotemysql.com
- **Freesqldatabase:** https://www.freesqldatabase.com

#### 4. Actualizar URL de la API en Frontend

Actualiza `frontend/js/api/config.js`:

```javascript
// Para producción (ajusta con tu URL de Render)
const API_BASE_URL = 'https://anita-integrales-api.onrender.com/api';
```

Luego vuelve a desplegar en Vercel.

---

### Opción 2: Netlify + Railway

Similar a Vercel + Render, pero usando:
- **Frontend:** Netlify (https://netlify.com)
- **Backend:** Railway (https://railway.app) - soporta PHP y MySQL

---

### Opción 3: 000webhost (Todo en uno)

**Ventajas:**
- Todo gratis (PHP, MySQL, hosting)
- Subdominio gratuito o dominio propio

**Pasos:**

1. Ve a: https://www.000webhost.com
2. Crea cuenta gratuita
3. Crea un nuevo sitio web
4. Sube tus archivos via FTP o File Manager
5. Estructura:
   ```
   public_html/
   ├── frontend/  (todo el contenido de frontend/)
   └── backend/   (todo el contenido de backend/)
   ```
6. Configura la base de datos MySQL desde el panel
7. Tu sitio estará en: `https://tu-sitio.000webhostapp.com`

**Configurar dominio personalizado:**

1. En 000webhost, ve a "Domain" → "Add Domain"
2. Puedes usar un dominio gratuito de Freenom (ver más abajo)

---

### Opción 4: InfinityFree (Todo en uno, más fácil)

**Ventajas:**
- Gratis para siempre
- PHP y MySQL incluidos
- Sin anuncios (en algunos planes)

**Pasos:**

1. Ve a: https://infinityfree.net
2. Crea cuenta
3. Crea un sitio web
4. Sube archivos vía FTP o File Manager
5. Crea base de datos MySQL desde el panel
6. Configura las variables de entorno

---

## 🌐 Dominios Gratuitos

### Opción 1: Freenom (Dominios .tk, .ml, .ga, .cf)

1. Ve a: https://www.freenom.com
2. Busca un dominio disponible (ej: `anita-integrales.tk`)
3. Regístralo gratis por 1 año
4. Configura los DNS:
   - **Para Vercel:** Agrega registro CNAME apuntando a tu sitio de Vercel
   - **Para 000webhost:** Configura los DNS que te proporcionen

### Opción 2: Dot.tk

Similar a Freenom, ofrece dominios `.tk` gratuitos.

### Opción 3: Dominio Personalizado con Vercel

Vercel permite agregar dominios personalizados:
1. En tu proyecto de Vercel, ve a "Settings" → "Domains"
2. Agrega tu dominio (puede ser de Freenom)
3. Configura los DNS según las instrucciones

---

## 🔧 Configuración para Producción

### 1. Actualizar Variables de Entorno

**En el Backend (Render/Railway/etc):**
```env
DB_HOST=tu-host-mysql
DB_PORT=3306
DB_NAME=anita_integrales
DB_USER=tu-usuario
DB_PASSWORD=tu-contraseña
```

**En el Frontend (actualizar `frontend/js/api/config.js`):**
```javascript
// Desarrollo
// const API_BASE_URL = 'http://localhost/backend/api';

// Producción
const API_BASE_URL = 'https://tu-api-backend.com/api';
```

### 2. Configurar CORS en el Backend

El backend ya tiene CORS configurado. Solo asegúrate de actualizar `backend/api/config.php`:

```php
// Agregar tu dominio de producción
header('Access-Control-Allow-Origin: https://tu-sitio.vercel.app');
// O para permitir múltiples orígenes:
// header('Access-Control-Allow-Origin: *');
```

### 3. Poblar la Base de Datos en Producción

Una vez que tengas tu base de datos MySQL en la nube:

1. Exporta la base de datos local:
   ```bash
   mysqldump -u root -p anita_integrales > database_backup.sql
   ```

2. Importa en la base de datos remota:
   ```bash
   mysql -h tu-host -u tu-usuario -p anita_integrales < database_backup.sql
   ```

O ejecuta los scripts SQL directamente en la base de datos remota:
- `backend/sql/init.sql` (crear tablas)
- `php backend/sql/populate_products.php` (poblar productos)

---

## 📝 Checklist de Deployment

### Frontend
- [ ] Código subido a GitHub
- [ ] Deployado en Vercel/Netlify/000webhost
- [ ] URL de API actualizada en `config.js`
- [ ] Dominio configurado (opcional)

### Backend
- [ ] Código subido a GitHub
- [ ] Deployado en Render/Railway/etc
- [ ] Variables de entorno configuradas
- [ ] Base de datos MySQL creada en la nube
- [ ] Tablas creadas (ejecutar `init.sql`)
- [ ] Productos insertados (ejecutar `populate_products.php`)
- [ ] CORS configurado para el dominio del frontend

### Base de Datos
- [ ] Base de datos MySQL creada
- [ ] Credenciales guardadas
- [ ] Tablas creadas
- [ ] Datos insertados

### Testing
- [ ] Frontend carga correctamente
- [ ] API responde correctamente
- [ ] Registro de usuarios funciona
- [ ] Login funciona
- [ ] Productos se muestran
- [ ] Carrito funciona
- [ ] Pedidos se crean

---

## 🚀 Deployment Rápido (Recomendado)

### Paso 1: Frontend en Vercel (5 minutos)

```bash
# 1. Asegúrate de estar en la raíz del proyecto
cd /Users/sdforero/Desktop/web4/Integrales

# 2. Sube a GitHub (si aún no lo has hecho)
git add .
git commit -m "Preparar para producción"
git push origin main

# 3. Ve a vercel.com y conecta tu repositorio
# 4. Configura Root Directory: frontend
# 5. Deploy
```

### Paso 2: Base de Datos MySQL Gratis

1. Ve a https://planetscale.com o https://remotemysql.com
2. Crea una base de datos
3. Ejecuta los scripts SQL:
   - Crea tablas con `init.sql`
   - Pobla productos con `populate_products.php` o manualmente

### Paso 3: Backend en Render (10 minutos)

1. Ve a https://render.com
2. Crea un Web Service
3. Conecta tu repositorio
4. Configura:
   - **Root Directory:** `backend`
   - **Start Command:** `php -S 0.0.0.0:$PORT`
5. Agrega variables de entorno con credenciales de MySQL
6. Deploy

### Paso 4: Conectar Todo

1. Actualiza `frontend/js/api/config.js` con la URL de Render
2. Vuelve a desplegar en Vercel
3. ¡Listo! Tu sitio está online

---

## 🌍 Tu URL Final

Después del deployment:
- **Frontend:** `https://tu-sitio.vercel.app` o tu dominio personalizado
- **Backend:** `https://tu-api.onrender.com`
- **Acceso público:** Cualquier persona puede usar tu sitio desde cualquier dispositivo

---

## 📞 Soporte

Si tienes problemas:
1. Verifica que ambos servidores estén corriendo
2. Revisa los logs en Vercel/Render
3. Verifica las variables de entorno
4. Revisa la consola del navegador para errores de CORS

¡Tu sitio estará online en menos de 30 minutos! 🎉

