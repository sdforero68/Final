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

## 🚀 Inicio Rápido

### Desarrollo Local

```bash
# 1. Crear base de datos
mysql -u root -p < backend/sql/init.sql
# Contraseña: Naniela2928**

# 2. Poblar productos
php backend/sql/populate_products.php

# 3. Iniciar servidores
# Terminal 1 - Frontend:
cd frontend && php -S localhost:8000

# Terminal 2 - Backend:
cd .. && sudo php -S localhost:80 -t .
```

**URLs:**
- Frontend: http://localhost:8000
- API: http://localhost/backend/api/products/list.php

### Publicar en Internet (GitHub Pages)

**⚠️ IMPORTANTE: GitHub Pages no permite seleccionar subcarpetas directamente. Usa GitHub Actions:**

1. **Haz commit y push del workflow:**
   ```bash
   git add .github/ frontend/.nojekyll
   git commit -m "Configurar GitHub Actions para Pages"
   git push origin main
   ```

2. **En GitHub, ve a Settings → Pages:**
   - En **"Source"**, selecciona: **"GitHub Actions"** ⚠️ (NO "Deploy from a branch")

3. **Activa GitHub Actions:**
   - Settings → Actions → General
   - **Workflow permissions:** "Read and write permissions"
   - Guarda

4. **Espera 2-3 minutos** (el workflow se ejecutará automáticamente)

5. Tu sitio estará en: `https://sdforero68.github.io/Final/`

**📖 Guía detallada:** Lee [CONFIGURAR_GITHUB_PAGES_SOLUCION.md](./CONFIGURAR_GITHUB_PAGES_SOLUCION.md)

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
├── frontend/              # Frontend (HTML, CSS, JS) - Se sirve en GitHub Pages
│   ├── .nojekyll         # Archivo necesario para GitHub Pages
│   ├── index.html        # Página principal
│   ├── js/
│   │   ├── api/          # Servicios de API
│   │   └── ...
│   ├── css/              # Estilos
│   ├── assets/           # Imágenes y recursos
│   └── pages/            # Páginas HTML
├── backend/              # Backend (PHP + MySQL)
│   ├── api/              # Endpoints de la API
│   ├── config/          # Configuración
│   └── sql/             # Scripts SQL
└── README.md            # Este archivo (solo documentación)
```

## 🌐 Publicar en Internet

**📖 GUÍA COMPLETA PASO A PASO:**
👉 **Lee [PASOS_FINALES.md](./PASOS_FINALES.md)** - Instrucciones detalladas de todo lo que debes hacer

### Resumen Rápido

1. ✅ **Frontend:** GitHub Pages (ya configurado con GitHub Actions)
2. ⏳ **Base de datos:** Crear en remotemysql.com o PlanetScale
3. ⏳ **Backend:** Desplegar en Render.com
4. ⏳ **URLs:** Actualizar solo 2 archivos (cambiar URLs, no código)
5. ⏳ **Dominio:** Opcional - Registrar en Freenom.com

**Ver [PASOS_FINALES.md](./PASOS_FINALES.md) para instrucciones detalladas de cada paso.**

## 🔧 Configuración



### API

La URL se detecta automáticamente:
- **Desarrollo:** `http://localhost/backend/api`
- **Producción:** Configurar en `frontend/js/api/config.js`

## 📊 Base de Datos

### Tablas

- **users** - Usuarios registrados
- **sessions** - Sesiones (tokens)
- **categories** - Categorías de productos
- **products** - 59 productos
- **cart_items** - Carrito de compras
- **orders** - Pedidos
- **order_items** - Items de pedidos
- **favorites** - Favoritos

## 🔍 Verificar Instalación

```bash
# Verificar base de datos
mysql -u root -p -e "USE anita_integrales; SELECT COUNT(*) FROM products;"

# Verificar API
curl http://localhost/backend/api/products/list.php
```

## 🐛 Solución de Problemas

### GitHub Pages muestra el README

**Solución:**
1. Ve a GitHub → Settings → Pages
2. Verifica que **Folder** sea `/frontend` (NO `/root`)
3. Lee: [CONFIGURAR_GITHUB_PAGES.md](./CONFIGURAR_GITHUB_PAGES.md)

### Error de CORS

- Verifica que agregaste tu dominio en `backend/api/config.php`
- Vuelve a desplegar en Render

### La API no responde

- Verifica que el backend en Render esté corriendo
- Revisa los logs en Render

## 📝 Endpoints de la API

### Autenticación
- `POST /api/auth/register.php` - Registrar usuario
- `POST /api/auth/login.php` - Iniciar sesión
- `POST /api/auth/logout.php` - Cerrar sesión

### Productos
- `GET /api/products/list.php` - Listar productos
- `GET /api/products/get.php?id=xxx` - Obtener producto
- `GET /api/products/categories.php` - Categorías

### Carrito (requiere autenticación)
- `GET /api/cart/get.php` - Obtener carrito
- `POST /api/cart/add.php` - Agregar producto
- `PUT /api/cart/update.php` - Actualizar cantidad
- `DELETE /api/cart/remove.php` - Eliminar producto

### Pedidos (requiere autenticación)
- `POST /api/orders/create.php` - Crear pedido
- `GET /api/orders/list.php` - Listar pedidos
- `GET /api/orders/get.php?id=xxx` - Obtener pedido

## 👥 Créditos

Desarrollado para **Anita Integrales** 
