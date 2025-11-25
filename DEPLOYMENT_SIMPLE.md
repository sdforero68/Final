# 🚀 Guía Simple de Deployment - Paso a Paso

Esta guía te ayudará a publicar tu sitio en internet para que cualquier persona pueda usarlo desde cualquier dispositivo.

## 🎯 Opción Más Rápida: Vercel + Render (30 minutos)

### Parte 1: Frontend en Vercel (10 minutos)

1. **Ve a Vercel:**
   - Abre: https://vercel.com
   - Click "Sign Up" y crea cuenta con GitHub

2. **Sube tu código a GitHub (si aún no lo hiciste):**
   ```bash
   cd /Users/sdforero/Desktop/web4/Integrales
   git add .
   git commit -m "Preparar para producción"
   git push origin main
   ```

3. **Despliega en Vercel:**
   - En Vercel, click "Add New Project"
   - Selecciona tu repositorio `Final`
   - Configuración:
     - **Framework Preset:** Other
     - **Root Directory:** `frontend` ⚠️ IMPORTANTE
     - **Build Command:** (dejar vacío)
     - **Output Directory:** `frontend`
   - Click "Deploy"
   - Espera 2-3 minutos
   - Tu sitio estará en: `https://final-tu-usuario.vercel.app`

### Parte 2: Base de Datos MySQL Gratis (10 minutos)

**Opción A: Remotemysql.com (Más fácil)**

1. Ve a: https://remotemysql.com
2. Click "Sign Up"
3. Crea tu cuenta
4. Una vez dentro, verás:
   - **Host:** (ej: remotemysql.com)
   - **User:** (tu usuario)
   - **Password:** (tu contraseña)
   - **Database:** (el nombre de tu BD)
5. **Guarda estas credenciales** - las necesitarás después

**Opción B: PlanetScale (Más profesional)**

1. Ve a: https://planetscale.com
2. Crea cuenta gratuita
3. Crea una nueva base de datos
4. Obtén las credenciales de conexión

### Parte 3: Backend en Render (10 minutos)

1. **Ve a Render:**
   - Abre: https://render.com
   - Click "Get Started for Free"
   - Crea cuenta con GitHub

2. **Crea un Web Service:**
   - Click "New +" → "Web Service"
   - Conecta tu repositorio de GitHub
   - Selecciona el repositorio `Final`
   - Configuración:
     - **Name:** `anita-integrales-api`
     - **Environment:** `PHP`
     - **Root Directory:** `backend` ⚠️ IMPORTANTE
     - **Build Command:** (dejar vacío)
     - **Start Command:** `php -S 0.0.0.0:$PORT -t .`
   - Scroll down a "Environment Variables" y agrega:
     ```
     DB_HOST = [el host de tu MySQL, ej: remotemysql.com]
     DB_PORT = 3306
     DB_NAME = [nombre de tu base de datos]
     DB_USER = [tu usuario de MySQL]
     DB_PASSWORD = [tu contraseña de MySQL]
     ```
   - Click "Create Web Service"
   - Espera 5-10 minutos a que se despliegue
   - Tu API estará en: `https://anita-integrales-api.onrender.com`

3. **Crea la base de datos en tu MySQL remoto:**
   - Ve al panel de remotemysql.com o PlanetScale
   - Ejecuta el script `backend/sql/init.sql` (copia y pega en el SQL console)
   - Ejecuta el script de productos (o importa los datos)

### Parte 4: Conectar Todo (5 minutos)

1. **Actualiza la URL de la API:**
   - Edita: `frontend/js/api/config.js`
   - Cambia esta línea:
     ```javascript
     return 'https://tu-backend-url.com/api'; // ⚠️ CAMBIA ESTA URL
     ```
   - Por tu URL de Render:
     ```javascript
     return 'https://anita-integrales-api.onrender.com/api';
     ```

2. **Actualiza CORS en el backend:**
   - Edita: `backend/api/config.php`
   - Agrega tu dominio de Vercel en `$allowedOrigins`:
     ```php
     $allowedOrigins = [
         'http://localhost:8000',
         'http://localhost',
         'https://final-tu-usuario.vercel.app', // ← Agrega esta línea
     ];
     ```

3. **Vuelve a desplegar en Vercel:**
   - En Vercel, ve a tu proyecto
   - Click "Redeploy" o haz push de nuevo a GitHub

4. **¡Listo! 🎉**
   - Tu sitio está en: `https://final-tu-usuario.vercel.app`
   - Cualquier persona puede acceder desde cualquier dispositivo

---

## 🌐 Agregar Dominio Personalizado Gratis

### Opción 1: Dominio .tk Gratis (Freenom)

1. Ve a: https://www.freenom.com
2. Busca un dominio (ej: `anita-integrales`)
3. Selecciona extensión `.tk`, `.ml`, `.ga` o `.cf`
4. Agrégalo al carrito y completa el registro (gratis por 1 año)
5. En Freenom, ve a "Services" → "My Domains"
6. Click "Manage Domain"
7. Ve a "Manage Freenom DNS"
8. Agrega un registro CNAME:
   - **Name:** `@` o `www`
   - **Type:** `CNAME`
   - **Target:** `cname.vercel-dns.com`
   - **TTL:** 3600

9. En Vercel:
   - Ve a tu proyecto → "Settings" → "Domains"
   - Agrega tu dominio (ej: `anita-integrales.tk`)
   - Sigue las instrucciones para configurar DNS

10. Espera 24-48 horas para que el dominio se propague

### Opción 2: Usar el Subdominio de Vercel

Puedes compartir directamente: `https://final-tu-usuario.vercel.app`

---

## ✅ Verificación Final

1. **Frontend funciona:**
   - Abre tu URL de Vercel
   - Debe cargar la página principal

2. **API funciona:**
   - Abre: `https://anita-integrales-api.onrender.com/api/products/list.php`
   - Debe mostrar JSON con productos

3. **Todo conectado:**
   - Intenta registrarte
   - Agrega productos al carrito
   - Crea un pedido

---

## 📱 Compartir tu Sitio

Una vez desplegado, puedes compartir:

**Opción 1: URL de Vercel**
```
https://final-tu-usuario.vercel.app
```

**Opción 2: Dominio personalizado**
```
https://anita-integrales.tk
```

Cualquier persona puede abrir estos enlaces desde:
- 📱 Celular
- 💻 Computadora
- 📟 Tablet
- Cualquier dispositivo con internet

---

## 🔧 Problemas Comunes

### "Error de CORS"
- Verifica que agregaste tu dominio en `backend/api/config.php`
- Vuelve a desplegar en Render

### "404 en la API"
- Verifica que la URL en `frontend/js/api/config.js` sea correcta
- Asegúrate de incluir `/api` al final

### "Error de conexión a la base de datos"
- Verifica las variables de entorno en Render
- Revisa que la base de datos MySQL esté creada
- Verifica las credenciales

---

## 📞 Resumen Rápido

1. **Frontend:** Vercel → `https://tu-sitio.vercel.app`
2. **Backend:** Render → `https://tu-api.onrender.com`
3. **Base de datos:** Remotemysql.com o PlanetScale
4. **Dominio:** Freenom (opcional) → `tu-sitio.tk`

**¡Listo en 30 minutos!** 🚀

