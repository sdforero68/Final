# 🚀 Configuración del Backend - Anita Integrales

Este documento contiene las instrucciones para configurar y ejecutar el backend PHP con MySQL.

## 📋 Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior (o MariaDB)
- Servidor web (Apache/Nginx) o PHP Built-in Server
- Extensión PDO MySQL habilitada en PHP

## 🔧 Configuración Paso a Paso

### 1. Configurar la Base de Datos

#### Opción A: Usando MySQL desde la terminal

```bash
# Conectarse a MySQL
mysql -u root -p

# Ingresar la contraseña cuando se solicite: Naniela2928**
```

#### Opción B: Usando TablePlus

1. Abre TablePlus
2. Crea una nueva conexión MySQL:
   - **Host**: localhost
   - **Port**: 3306
   - **User**: root
   - **Password**: Naniela2928**

### 2. Crear la Base de Datos

Una vez conectado a MySQL, ejecuta el script de inicialización:

```bash
# Desde la terminal
cd /Users/sdforero/Desktop/web4/Integrales
mysql -u root -p < backend/sql/init.sql

# Ingresar la contraseña cuando se solicite: Naniela2928**
```

O si estás dentro de MySQL:

```sql
source /Users/sdforero/Desktop/web4/Integrales/backend/sql/init.sql
```

### 3. Poblar los Productos

Ejecuta el script PHP para insertar los productos en la base de datos:

```bash
cd /Users/sdforero/Desktop/web4/Integrales
php backend/sql/populate_products.php
```

Deberías ver un mensaje de confirmación:
```
✅ Productos procesados exitosamente:
   - Insertados: XX
   - Actualizados: XX
   - Total: XX
```

### 4. Verificar la Configuración de la Base de Datos

El archivo `backend/config/database.env` ya está configurado con tus credenciales:

```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=anita_integrales
DB_USER=root
DB_PASSWORD=Naniela2928**
```

Si necesitas cambiar algo, edita este archivo.

### 5. Configurar el Servidor Web

#### Opción A: PHP Built-in Server (Recomendado para desarrollo)

```bash
cd /Users/sdforero/Desktop/web4/Integrales

# Iniciar servidor PHP en el puerto 8000 para el frontend
php -S localhost:8000 -t frontend

# En otra terminal, iniciar servidor PHP para la API (puerto 80)
cd /Users/sdforero/Desktop/web4/Integrales
sudo php -S localhost:80 -t .
```

#### Opción B: Apache con Virtual Host

1. Edita el archivo de hosts:
   ```bash
   sudo nano /etc/hosts
   ```
   Agrega:
   ```
   127.0.0.1 anita.local
   ```

2. Configura un VirtualHost en Apache apuntando a `/Users/sdforero/Desktop/web4/Integrales`

3. Actualiza la URL de la API en `frontend/js/api/config.js`:
   ```javascript
   const API_BASE_URL = 'http://anita.local/backend/api';
   ```

### 6. Verificar que la API Funciona

Prueba acceder a estos endpoints:

- Listar productos: http://localhost/Integrales/backend/api/products/list.php
- Listar categorías: http://localhost/Integrales/backend/api/products/categories.php

Deberías ver respuestas JSON.

## 📁 Estructura del Backend

```
backend/
├── api/                    # Endpoints de la API
│   ├── auth/              # Autenticación (login, registro, logout)
│   ├── products/          # Productos (listar, obtener)
│   ├── cart/              # Carrito (agregar, actualizar, eliminar)
│   └── orders/            # Pedidos (crear, listar, obtener)
├── config/                # Configuración
│   ├── database.php       # Clase de conexión a la BD
│   └── database.env       # Credenciales de la BD
└── sql/                   # Scripts SQL
    ├── init.sql           # Script de inicialización
    └── populate_products.php  # Script para poblar productos
```

## 🔐 Autenticación

La API utiliza tokens Bearer para la autenticación. Cuando un usuario inicia sesión o se registra, se genera un token que se guarda en localStorage del frontend y se envía en cada petición mediante el header `Authorization: Bearer <token>`.

## 🌐 Endpoints Disponibles

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
- `DELETE /api/cart/remove.php?product_id=xxx` - Eliminar producto
- `POST /api/cart/clear.php` - Vaciar carrito

### Pedidos (requiere autenticación)
- `POST /api/orders/create.php` - Crear pedido
- `GET /api/orders/list.php` - Listar pedidos del usuario
- `GET /api/orders/get.php?id=xxx` - Obtener pedido específico

## 🗄️ Estructura de la Base de Datos

### Tablas Principales

- **users**: Usuarios del sistema
- **sessions**: Sesiones de usuarios (tokens)
- **categories**: Categorías de productos
- **products**: Productos
- **cart_items**: Items en el carrito de compras
- **orders**: Pedidos realizados
- **order_items**: Items de cada pedido
- **favorites**: Productos favoritos de los usuarios

## 🔍 Verificar Tablas en TablePlus

1. Abre TablePlus
2. Conéctate a MySQL con las credenciales:
   - Host: localhost
   - Port: 3306
   - User: root
   - Password: Naniela2928**
   - Database: anita_integrales
3. Deberías ver todas las tablas listadas

## 🔍 Verificar desde la Terminal

```bash
# Conectarse a MySQL
mysql -u root -p

# Usar la base de datos
USE anita_integrales;

# Ver todas las tablas
SHOW TABLES;

# Ver estructura de una tabla
DESCRIBE users;

# Ver productos
SELECT * FROM products LIMIT 5;

# Ver categorías
SELECT * FROM categories;
```

## 🐛 Solución de Problemas

### Error: "Access denied for user 'root'@'localhost'"
- Verifica que la contraseña en `backend/config/database.env` sea correcta: `Naniela2928**`

### Error: "Unknown database 'anita_integrales'"
- Ejecuta el script `backend/sql/init.sql` para crear la base de datos

### Error: "Table 'products' doesn't exist"
- Verifica que el script `init.sql` se ejecutó correctamente
- Ejecuta `populate_products.php` para poblar los productos

### Error de CORS en el navegador
- Asegúrate de que el servidor de la API esté corriendo
- Verifica que la URL en `frontend/js/api/config.js` sea correcta

### Los productos no aparecen
- Ejecuta `php backend/sql/populate_products.php` para poblar la base de datos

## 📝 Notas Importantes

1. **Seguridad**: El archivo `database.env` contiene credenciales sensibles. No lo subas a Git. Ya está en `.gitignore`.

2. **Desarrollo vs Producción**: 
   - En desarrollo, usa el servidor PHP built-in
   - En producción, configura Apache/Nginx apropiadamente

3. **URLs de la API**: Asegúrate de actualizar `API_BASE_URL` en `frontend/js/api/config.js` según tu configuración.

## ✅ Checklist de Configuración

- [ ] MySQL instalado y corriendo
- [ ] Base de datos `anita_integrales` creada
- [ ] Tablas creadas (ejecutado `init.sql`)
- [ ] Productos insertados (ejecutado `populate_products.php`)
- [ ] Servidor web configurado y corriendo
- [ ] API respondiendo correctamente
- [ ] Frontend puede conectarse a la API

---

¿Necesitas ayuda? Revisa la sección de "Solución de Problemas" o verifica los logs de PHP y MySQL.

