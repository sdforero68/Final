# 🍞 Anita Integrales - E-commerce

Sitio web e-commerce para productos artesanales e integrales, desarrollado con **HTML, CSS, JavaScript vanilla** en el frontend y **PHP + MySQL** en el backend.

## ✨ Características

- ✅ Autenticación de usuarios (registro, login, logout)
- ✅ Catálogo de productos con búsqueda y filtros por categoría
- ✅ Carrito de compras persistente conectado a API
- ✅ Proceso de checkout completo
- ✅ Historial de pedidos
- ✅ Perfil de usuario
- ✅ Productos favoritos
- ✅ Diseño responsive (móvil, tablet, desktop)
- ✅ Backend API RESTful completo

## 🏗️ Arquitectura

### Frontend
- **HTML5** + **CSS3** + **JavaScript (ES6+)**
- Módulos ES6 para organización del código
- API conectada con backend PHP
- LocalStorage como fallback

### Backend
- **PHP 7.4+** con PDO
- **MySQL 5.7+** (base de datos relacional)
- API RESTful completa
- Autenticación con tokens
- CORS configurado

## 📁 Estructura del Proyecto

```
Integrales/
├── frontend/              # Frontend (HTML, CSS, JS)
│   ├── js/
│   │   ├── api/          # Servicios de API (auth, products, cart, orders)
│   │   ├── pages/        # Lógica por página
│   │   └── ...
│   ├── css/              # Estilos
│   ├── assets/           # Imágenes y recursos
│   └── pages/            # Páginas HTML
├── backend/              # Backend (PHP + MySQL)
│   ├── api/              # Endpoints de la API
│   │   ├── auth/        # Autenticación (login, registro, logout)
│   │   ├── products/    # Productos (listar, obtener, categorías)
│   │   ├── cart/        # Carrito (agregar, actualizar, eliminar)
│   │   └── orders/      # Pedidos (crear, listar, obtener)
│   ├── config/          # Configuración (database.php, config.php)
│   └── sql/             # Scripts SQL (init.sql, populate_products.php)
└── README.md            # Este archivo
```

## 🚀 Configuración Local (Desarrollo)

### Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx) o PHP Built-in Server

### Paso 1: Configurar Base de Datos

```bash
# Conectarse a MySQL
mysql -u root -p
# Contraseña: Naniela2928**

# Crear base de datos y tablas
mysql -u root -p < backend/sql/init.sql

# Poblar productos en la base de datos
php backend/sql/populate_products.php
```

### Paso 2: Configurar Credenciales

Edita `backend/config/database.env` si necesitas cambiar las credenciales:

```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=anita_integrales
DB_USER=root
DB_PASSWORD=Naniela2928**
```

### Paso 3: Iniciar Servidores

**Terminal 1 - Frontend:**
```bash
cd frontend
php -S localhost:8000
```

**Terminal 2 - Backend:**
```bash
cd /Users/sdforero/Desktop/web4/Integrales
sudo php -S localhost:80 -t .
```

### Paso 4: Acceder a la Aplicación

- **Frontend:** http://localhost:8000
- **API:** http://localhost/backend/api/products/list.php

## 🌐 Publicar en Internet (GitHub Pages)

### Opción Recomendada: GitHub Pages + Render

**Ventajas:**
- ✅ Completamente gratis
- ✅ HTTPS automático
- ✅ Deploy automático desde GitHub
- ✅ Subdominio incluido

### Pasos Rápidos

#### 1. Activar GitHub Pages (5 minutos)

1. Ve a tu repositorio en GitHub: https://github.com/sdforero68/Final
2. Click en **"Settings"** → **"Pages"**
3. En **"Source"**, selecciona:
   - **Branch:** `main`
   - **Folder:** `/frontend` ⚠️ IMPORTANTE
4. Click **"Save"**
5. Espera 2-3 minutos
6. Tu sitio estará en: `https://sdforero68.github.io/Final/`

#### 2. Desplegar Backend en Render (10 minutos)

1. Ve a: https://render.com
2. Crea cuenta con GitHub
3. Click **"New +"** → **"Web Service"**
4. Conecta tu repositorio `Final`
5. Configuración:
   - **Name:** `anita-integrales-api`
   - **Environment:** `PHP`
   - **Root Directory:** `backend` ⚠️ IMPORTANTE
   - **Build Command:** (dejar vacío)
   - **Start Command:** `php -S 0.0.0.0:$PORT -t .`
6. En **"Environment Variables"**, agrega:
   ```
   DB_HOST = [tu-host-mysql]
   DB_PORT = 3306
   DB_NAME = anita_integrales
   DB_USER = [tu-usuario]
   DB_PASSWORD = [tu-contraseña]
   ```
7. Click **"Create Web Service"**
8. Espera 5-10 minutos
9. Tu API estará en: `https://anita-integrales-api.onrender.com`

#### 3. Base de Datos MySQL en la Nube

**Opción A: Remotemysql.com (Fácil y gratis)**
1. Ve a: https://remotemysql.com
2. Crea cuenta
3. Crea una base de datos
4. Ejecuta los scripts SQL (`init.sql` y `populate_products.php`)

**Opción B: PlanetScale (Profesional y gratis)**
1. Ve a: https://planetscale.com
2. Crea cuenta
3. Crea una base de datos
4. Ejecuta los scripts SQL

#### 4. Actualizar URLs (5 minutos)

**Actualizar API en Frontend:**

Edita `frontend/js/api/config.js`:

```javascript
// Cambia esta línea (línea ~18):
const PRODUCTION_API_URL = 'https://anita-integrales-api.onrender.com/api';
// Reemplaza 'anita-integrales-api.onrender.com' con tu URL de Render
```

**Actualizar CORS en Backend:**

Edita `backend/api/config.php`:

```php
$allowedOrigins = [
    'http://localhost:8000',
    'http://localhost',
    'https://sdforero68.github.io', // ← Agrega esta línea
];
```

#### 5. Hacer Commit y Push

```bash
git add .
git commit -m "Configurar para producción"
git push origin main
```

GitHub Pages se actualizará automáticamente en 1-2 minutos.

### ✅ Resultado

- **Frontend:** `https://sdforero68.github.io/Final/`
- **Backend API:** `https://anita-integrales-api.onrender.com/api`
- **Base de datos:** MySQL en la nube

**Cualquier persona puede acceder desde cualquier dispositivo:** 📱💻📟

## 🌍 Dominio Personalizado (Opcional)

### Opción 1: Dominio Gratis con Freenom

1. Ve a: https://www.freenom.com
2. Busca un dominio disponible (ej: `anita-integrales`)
3. Selecciona extensión `.tk`, `.ml`, `.ga` o `.cf` (gratis por 1 año)
4. Completa el registro
5. En GitHub → Settings → Pages → Custom domain
6. Agrega tu dominio y configura los DNS según las instrucciones

### Opción 2: Usar el Subdominio de GitHub Pages

Directamente puedes compartir: `https://sdforero68.github.io/Final/`

## 🔧 Configuración de la API

La URL de la API se detecta automáticamente según el entorno:

- **Desarrollo (localhost):** `http://localhost/backend/api`
- **Producción:** Configura la URL en `frontend/js/api/config.js`

El archivo `frontend/js/api/config.js` detecta automáticamente si estás en desarrollo o producción.

## 📊 Base de Datos

### Tablas

- **users** - Usuarios registrados
- **sessions** - Sesiones de usuario (tokens)
- **categories** - Categorías de productos
- **products** - Productos (59 productos incluidos)
- **cart_items** - Items en el carrito
- **orders** - Pedidos realizados
- **order_items** - Items de cada pedido
- **favorites** - Productos favoritos

### Verificar en TablePlus

1. Abre TablePlus
2. Crea nueva conexión MySQL:
   - **Host:** `localhost`
   - **Port:** `3306`
   - **User:** `root`
   - **Password:** `Naniela2928**`
   - **Database:** `anita_integrales`

## 🔍 Verificar que Todo Funciona

### Local

```bash
# Verificar base de datos
mysql -u root -p -e "USE anita_integrales; SELECT COUNT(*) FROM products;"

# Verificar API
curl http://localhost/backend/api/products/list.php
```

### Producción

1. **Frontend:** Abre `https://sdforero68.github.io/Final/`
2. **API:** Abre `https://tu-api.onrender.com/api/products/list.php`
3. **Consola del navegador (F12):** Verifica que no haya errores

## 🐛 Solución de Problemas

### Error de CORS

- Verifica que agregaste tu dominio en `backend/api/config.php`
- Vuelve a desplegar en Render

### La API no responde

- Verifica que el backend en Render esté corriendo
- Revisa los logs en Render
- Verifica las variables de entorno

### Error 404 en GitHub Pages

- Verifica que configuraste la carpeta `/frontend` en GitHub Pages
- Asegúrate de que los archivos estén en la rama `main`

## 🛠️ Tecnologías Utilizadas

### Frontend
- HTML5 semántico
- CSS3 (Variables, Flexbox, Grid, Animaciones)
- JavaScript ES6+ (Módulos, async/await, fetch API)
- LocalStorage API

### Backend
- PHP 7.4+ con PDO
- MySQL 5.7+
- RESTful API
- Autenticación por tokens

## 📝 Endpoints de la API

### Autenticación
- `POST /api/auth/register.php` - Registrar usuario
- `POST /api/auth/login.php` - Iniciar sesión
- `POST /api/auth/logout.php` - Cerrar sesión
- `GET /api/auth/verify.php` - Verificar token

### Productos
- `GET /api/products/list.php` - Listar todos los productos
- `GET /api/products/get.php?id=xxx` - Obtener un producto
- `GET /api/products/categories.php` - Listar categorías

### Carrito (requiere autenticación)
- `GET /api/cart/get.php` - Obtener carrito
- `POST /api/cart/add.php` - Agregar producto
- `PUT /api/cart/update.php` - Actualizar cantidad
- `DELETE /api/cart/remove.php` - Eliminar producto
- `POST /api/cart/clear.php` - Vaciar carrito

### Pedidos (requiere autenticación)
- `POST /api/orders/create.php` - Crear pedido
- `GET /api/orders/list.php` - Listar pedidos del usuario
- `GET /api/orders/get.php?id=xxx` - Obtener pedido específico

## 👥 Créditos

Desarrollado para **Anita Integrales** - Más de 15 años creando alimentos saludables con amor.

---

**Versión**: 2.0.0  
**Última actualización**: Diciembre 2024
