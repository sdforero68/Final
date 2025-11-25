# 🍞 Anita Integrales - E-commerce

Sitio web e-commerce para productos artesanales e integrales, desarrollado con **HTML, CSS, JavaScript vanilla** en el frontend y **PHP + MySQL** en el backend.

## 🚀 Inicio Rápido

### Desarrollo Local

```bash
# 1. Crear base de datos
mysql -u root -p < backend/sql/init.sql

# 2. Poblar productos
php backend/sql/populate_products.php

# 3. Iniciar servidores
# Terminal 1 - Frontend:
cd frontend && php -S localhost:8000

# Terminal 2 - Backend:
cd .. && sudo php -S localhost:80 -t .
```

### Publicar en Internet (GitHub Pages)

**¡Es fácil! Solo 5 minutos:**
1. Lee: **[GITHUB_PAGES_QUICK.md](./GITHUB_PAGES_QUICK.md)** ⚡ (Guía rápida)
2. O lee: **[DEPLOYMENT_GITHUB_PAGES.md](./DEPLOYMENT_GITHUB_PAGES.md)** (Guía completa)

Tu sitio estará en: `https://sdforero68.github.io/Final/`

## 📚 Documentación Completa

- **[GITHUB_PAGES_QUICK.md](./GITHUB_PAGES_QUICK.md)** ⚡ - GitHub Pages en 5 minutos
- **[DEPLOYMENT_GITHUB_PAGES.md](./DEPLOYMENT_GITHUB_PAGES.md)** - Guía completa GitHub Pages
- **[PUBLICAR_SITIO.md](./PUBLICAR_SITIO.md)** - Otras opciones de hosting
- **[BACKEND_SETUP.md](./BACKEND_SETUP.md)** - Configuración del backend local

## 🏗️ Arquitectura

### Frontend
- **HTML5** + **CSS3** + **JavaScript (ES6+)**
- Módulos ES6 para organización del código
- Responsive design
- LocalStorage como fallback

### Backend
- **PHP 7.4+** con PDO
- **MySQL 5.7+** (base de datos relacional)
- API RESTful
- Autenticación con tokens JWT-like

## 📁 Estructura del Proyecto

```
Integrales/
├── frontend/              # Frontend (HTML, CSS, JS)
│   ├── js/
│   │   ├── api/          # Servicios de API
│   │   ├── pages/        # Lógica por página
│   │   └── ...
│   ├── css/              # Estilos
│   ├── assets/           # Imágenes y recursos
│   └── pages/            # Páginas HTML
├── backend/              # Backend (PHP + MySQL)
│   ├── api/              # Endpoints de la API
│   │   ├── auth/        # Autenticación
│   │   ├── products/    # Productos
│   │   ├── cart/        # Carrito
│   │   └── orders/      # Pedidos
│   ├── config/          # Configuración
│   └── sql/             # Scripts SQL
└── README.md            # Este archivo
```

## 🔑 Funcionalidades

- ✅ Autenticación de usuarios (registro, login, logout)
- ✅ Catálogo de productos con búsqueda y filtros
- ✅ Carrito de compras persistente
- ✅ Proceso de checkout completo
- ✅ Historial de pedidos
- ✅ Perfil de usuario
- ✅ Productos favoritos
- ✅ Responsive design

## 🛠️ Tecnologías

### Frontend
- HTML5 semántico
- CSS3 con variables, Flexbox, Grid
- JavaScript ES6+ (módulos, async/await)
- LocalStorage API

### Backend
- PHP 7.4+ con PDO
- MySQL 5.7+
- RESTful API
- Autenticación por tokens

## 📝 Configuración

### Base de Datos

Las credenciales están en `backend/config/database.env`:
```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=anita_integrales
DB_USER=root
DB_PASSWORD=Naniela2928**
```

### API

La URL base de la API está en `frontend/js/api/config.js`:
```javascript
const API_BASE_URL = 'http://localhost/Integrales/backend/api';
```

Ajusta según tu configuración.

## 🔍 Verificar Instalación

### Verificar Base de Datos

```bash
mysql -u root -p -e "USE anita_integrales; SHOW TABLES;"
```

### Verificar API

```bash
curl http://localhost/Integrales/backend/api/products/list.php
```

## 📖 Más Información

- Ver [BACKEND_SETUP.md](./BACKEND_SETUP.md) para guía detallada
- Ver código fuente para ejemplos de uso de la API

## 👥 Créditos

Desarrollado para **Anita Integrales** - Más de 15 años creando alimentos saludables con amor.

---

**Versión**: 2.0.0 (con Backend PHP + MySQL)  
**Última actualización**: Diciembre 2024
