# 🔧 Configurar Base de Datos en Render

## 📋 Credenciales de la Base de Datos

Base de datos: **FreeSQLDatabase.com**

```
Host: sql10.freesqldatabase.com
Database name: sql10809318
Database user: sql10809318
Database password: t3qD3KjUSe
Port: 3306
```

---

## 🚀 Pasos para Configurar en Render

### 1️⃣ Configurar Variables de Entorno en Render

1. Ve a tu servicio en Render: https://dashboard.render.com/
2. Selecciona tu servicio backend que usa la URL: `https://final-1-0wvc.onrender.com`
3. Ve a la sección **"Environment"** (o **"Variables de Entorno"**)
4. Haz clic en **"Add Environment Variable"** y agrega estas 6 variables:

#### Variable 1:
```
Key: DB_HOST
Value: sql10.freesqldatabase.com
```

#### Variable 2:
```
Key: DB_PORT
Value: 3306
```

#### Variable 3:
```
Key: DB_NAME
Value: sql10809318
```

#### Variable 4:
```
Key: DB_USER
Value: sql10809318
```

#### Variable 5:
```
Key: DB_PASSWORD
Value: t3qD3KjUSe
```

#### Variable 6:
```
Key: DB_SSL
Value: false
```

5. **Guarda los cambios** - Render reiniciará automáticamente el servicio
6. **Espera 1-2 minutos** para que Render reinicie y aplique los cambios

---

### 2️⃣ Crear las Tablas en la Base de Datos

Antes de que el backend funcione, necesitas crear las tablas en tu base de datos.

#### Opción A: Usando phpMyAdmin (Recomendado - Más fácil)

1. Ve a https://www.freesqldatabase.com/
2. Inicia sesión en tu cuenta
3. Busca la opción **"phpMyAdmin"** o **"Manage Database"**
4. Haz clic en **phpMyAdmin**
5. Selecciona tu base de datos: **sql10809318**
6. Ve a la pestaña **"SQL"**
7. Abre el archivo `backend/sql/init.sql` en tu computadora
8. Copia TODO su contenido
9. Pégalo en el cuadro de SQL de phpMyAdmin
10. Haz clic en **"Go"** o **"Ejecutar"**

#### Opción B: Usando MySQL desde Terminal

```bash
mysql -h sql10.freesqldatabase.com -u sql10809318 -p sql10809318 < backend/sql/init.sql
# Cuando pida la contraseña, ingresa: t3qD3KjUSe
```

---

### 3️⃣ Verificar que las Tablas se Crearon

En phpMyAdmin, ejecuta:

```sql
SHOW TABLES;
```

Deberías ver estas 8 tablas:
- ✅ `users`
- ✅ `sessions`
- ✅ `categories`
- ✅ `products`
- ✅ `cart_items`
- ✅ `orders`
- ✅ `order_items`
- ✅ `favorites`

---

### 4️⃣ Verificar que el Backend se Conecta

Espera 1-2 minutos después de configurar las variables de entorno, luego prueba:

```bash
curl https://final-1-0wvc.onrender.com/auth/login.php \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"test"}'
```

**Si funciona correctamente**, deberías ver:
- ✅ Un JSON con `success: false` y mensaje "Correo o contraseña incorrectos" (porque el usuario no existe aún)
- ❌ **NO** deberías ver "Error de conexión a la base de datos"

---

### 5️⃣ Crear un Usuario de Prueba

Puedes crear un usuario directamente desde el sitio web:

1. Ve a: `https://sdforero68.github.io/Final/pages/login/index.html`
2. Haz clic en "Registrarse"
3. Completa el formulario
4. Si funciona, ¡tu base de datos está configurada correctamente! ✅

---

## ✅ Verificación Final

Una vez configurado, prueba en tu navegador:

1. Ve a: `https://sdforero68.github.io/Final/pages/login/index.html`
2. Haz clic en "Registrarse"
3. Completa el formulario y regístrate
4. Si funciona, ¡tu base de datos está configurada correctamente! ✅

---

## ⚠️ Problemas Comunes

### Error: "Error de conexión a la base de datos"

**Soluciones:**
1. Verifica que todas las variables de entorno estén escritas exactamente igual (sin espacios extra)
2. Verifica que hayas esperado 1-2 minutos después de guardar las variables
3. Verifica que las credenciales sean correctas
4. Revisa los logs en Render: Dashboard → Tu servicio → Logs

### Error: "Table doesn't exist"

**Solución:** Ejecuta el script SQL `backend/sql/init.sql` en phpMyAdmin

### Error: "Access denied"

**Solución:** Verifica que el usuario y contraseña sean correctos

---

## 📝 Notas Importantes

- ⚠️ **Nunca subas estas credenciales al repositorio**
- ✅ El archivo `database.env` está en `.gitignore` (no se subirá)
- ✅ Render reinicia automáticamente cuando cambias variables de entorno
- ✅ Espera 1-2 minutos después de cada cambio

---

## 🎯 Una Vez Configurado

Una vez que la base de datos esté configurada y las tablas creadas:
- ✅ Los usuarios podrán registrarse
- ✅ Los usuarios podrán iniciar sesión
- ✅ Los pedidos se guardarán en la base de datos
- ✅ Todo funcionará completamente

¡Tu aplicación estará 100% funcional! 🚀

