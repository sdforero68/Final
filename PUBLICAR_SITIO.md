# 🌐 Cómo Publicar tu Sitio Web en Internet

## ✅ Lo que ya está LISTO

1. ✅ **Frontend conectado con la API**
   - Login/Registro → API backend
   - Carrito → API backend (con fallback a localStorage)
   - Todo funcional

2. ✅ **Backend completo**
   - API PHP + MySQL funcionando
   - Todos los endpoints creados
   - Base de datos estructurada

3. ✅ **Configuración automática**
   - Detecta si estás en desarrollo o producción
   - URLs configuradas correctamente

## 🚀 Pasos para Publicar (30 minutos)

### Paso 1: Frontend en Vercel (10 min)

1. Ve a: https://vercel.com
2. Inicia sesión con GitHub
3. Click "Add New Project"
4. Selecciona tu repositorio `Final`
5. Configura:
   - **Root Directory:** `frontend`
   - **Framework:** Other
6. Click "Deploy"
7. **Anota tu URL:** `https://final-xxxx.vercel.app`

### Paso 2: Base de Datos MySQL (10 min)

1. Ve a: https://remotemysql.com
2. Crea cuenta (gratis)
3. Crea una base de datos
4. **Guarda:**
   - Host
   - Usuario
   - Contraseña
   - Nombre de BD
5. Ejecuta los scripts SQL:
   - Copia `backend/sql/init.sql` y ejecútalo
   - Pobla los productos (ejecuta `populate_products.php` o insértalos manualmente)

### Paso 3: Backend en Render (10 min)

1. Ve a: https://render.com
2. Inicia sesión con GitHub
3. Click "New +" → "Web Service"
4. Conecta tu repositorio `Final`
5. Configura:
   - **Name:** `anita-integrales-api`
   - **Root Directory:** `backend`
   - **Start Command:** `php -S 0.0.0.0:$PORT -t .`
6. En "Environment Variables", agrega:
   ```
   DB_HOST = [de remotemysql.com]
   DB_PORT = 3306
   DB_NAME = [nombre de tu BD]
   DB_USER = [tu usuario]
   DB_PASSWORD = [tu contraseña]
   ```
7. Click "Create Web Service"
8. Espera 5-10 minutos
9. **Anota tu URL:** `https://anita-integrales-api.onrender.com`

### Paso 4: Conectar Todo (5 min)

1. **Edita `frontend/js/api/config.js`:**
   - Busca la línea: `return 'https://tu-backend-url.com/api';`
   - Cámbiala por: `return 'https://anita-integrales-api.onrender.com/api';`

2. **Edita `backend/api/config.php`:**
   - Agrega tu URL de Vercel en el array `$allowedOrigins`:
     ```php
     'https://final-xxxx.vercel.app', // Tu URL de Vercel
     ```

3. **Vuelve a desplegar:**
   - En Vercel: Click "Redeploy"
   - En Render: Se actualiza automáticamente

### Paso 5: Dominio Gratis (Opcional)

1. Ve a: https://www.freenom.com
2. Busca un dominio (ej: `anita-integrales`)
3. Selecciónalo en `.tk` o `.ml`
4. Regístralo gratis
5. En Vercel → Settings → Domains → Agrega tu dominio
6. Configura los DNS según las instrucciones

## 🎉 ¡Listo!

Tu sitio estará disponible en:
- **URL de Vercel:** `https://final-xxxx.vercel.app`
- **O tu dominio:** `https://anita-integrales.tk`

**Cualquier persona puede acceder desde:**
- 📱 Celular
- 💻 Computadora  
- 📟 Tablet
- Cualquier dispositivo con internet

## 📚 Documentación Detallada

Si necesitas más detalles, lee:
- **`DEPLOYMENT_SIMPLE.md`** - Guía completa paso a paso
- **`DEPLOYMENT.md`** - Todas las opciones disponibles

---

**¡Tu sitio estará online en 30 minutos!** 🚀

