# ⚡ Inicio Rápido - Anita Integrales

## 🎯 Configuración en 5 Pasos

### 1️⃣ Verificar MySQL

```bash
mysql -u root -p
# Contraseña: Naniela2928**
```

Si puedes conectarte, continúa. Si no, verifica que MySQL esté instalado y corriendo.

### 2️⃣ Crear Base de Datos

```bash
cd /Users/sdforero/Desktop/web4/Integrales
mysql -u root -p < backend/sql/init.sql
# Contraseña: Naniela2928**
```

Esto creará la base de datos `anita_integrales` y todas las tablas necesarias.

### 3️⃣ Poblar Productos

```bash
php backend/sql/populate_products.php
```

Deberías ver: `✅ Productos procesados exitosamente`

### 4️⃣ Iniciar Servidores

**Terminal 1 - Frontend:**
```bash
cd /Users/sdforero/Desktop/web4/Integrales/frontend
php -S localhost:8000
```

**Terminal 2 - Backend:**
```bash
cd /Users/sdforero/Desktop/web4/Integrales
sudo php -S localhost:80 -t .
```

### 5️⃣ Abrir en el Navegador

- **Frontend**: http://localhost:8000
- **API Test**: http://localhost/Integrales/backend/api/products/list.php

## ✅ Verificación

### Verificar Base de Datos en TablePlus

1. Abre TablePlus
2. Nueva conexión MySQL:
   - Host: `localhost`
   - Port: `3306`
   - User: `root`
   - Password: `Naniela2928**`
   - Database: `anita_integrales`
3. Deberías ver las tablas: `users`, `products`, `categories`, `cart_items`, `orders`, etc.

### Verificar desde Terminal

```bash
mysql -u root -p anita_integrales
# Contraseña: Naniela2928**

# Ver tablas
SHOW TABLES;

# Ver productos
SELECT COUNT(*) FROM products;
# Debería mostrar aproximadamente 60+ productos

# Ver categorías
SELECT * FROM categories;
```

### Verificar API

Abre en tu navegador:
- http://localhost/Integrales/backend/api/products/list.php
- Deberías ver un JSON con todos los productos

## 🐛 Problemas Comunes

**Error: "Access denied"**
- Verifica la contraseña de MySQL en `backend/config/database.env`

**Error: "Unknown database"**
- Ejecuta el paso 2 (crear base de datos)

**Error: "Table doesn't exist"**
- Ejecuta nuevamente `mysql -u root -p < backend/sql/init.sql`

**No aparecen productos**
- Ejecuta `php backend/sql/populate_products.php`

**Error de CORS o conexión**
- Verifica que ambos servidores estén corriendo
- Verifica que la URL en `frontend/js/api/config.js` sea correcta

## 📚 Más Información

- Guía completa: [BACKEND_SETUP.md](./BACKEND_SETUP.md)
- README principal: [README.md](./README.md)

¡Listo para empezar! 🚀

