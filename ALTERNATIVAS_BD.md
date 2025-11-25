# 🌐 Alternativas Gratuitas de Base de Datos MySQL en la Nube

Si **remotemysql.com** no funciona, aquí tienes las mejores alternativas gratuitas:

## ⭐ Opción 1: PlanetScale (Recomendado)

**Ventajas:**
- ✅ Completamente gratis
- ✅ Muy rápido y confiable
- ✅ Fácil de usar
- ✅ Compatible con MySQL
- ✅ Interfaz moderna

**Pasos:**

1. Ve a: https://planetscale.com
2. Crea cuenta (puedes usar GitHub)
3. Click "Create database"
4. Nombre: `anita-integrales`
5. Plan: Free
6. Obtén credenciales en "Connect"

**⚠️ IMPORTANTE:** Requiere SSL. Agrega en `database.env`:
```env
DB_SSL=true
```

**Y actualiza `backend/config/database.php`** (ya está actualizado para soportarlo).

---

## ⭐ Opción 2: Railway

**Ventajas:**
- ✅ Gratis con $5 de crédito mensual
- ✅ Muy fácil de usar
- ✅ Integrado con GitHub
- ✅ Interfaz moderna

**Pasos:**

1. Ve a: https://railway.app
2. Crea cuenta con GitHub
3. Click "New Project"
4. "Add Service" → "Database" → "MySQL"
5. Selecciona plan gratuito
6. Ve a "Variables" para credenciales

**No requiere SSL adicional.**

---

## Opción 3: Aiven

**Ventajas:**
- ✅ Plan gratuito disponible
- ✅ Servicios profesionales
- ✅ Buena documentación

**Pasos:**

1. Ve a: https://aiven.io
2. Click "Start free trial"
3. Crea cuenta
4. "Create service" → "MySQL"
5. Selecciona plan gratuito
6. Obtén credenciales en "Overview"

---

## Opción 4: AWS RDS Free Tier

**Ventajas:**
- ✅ Gratis por 12 meses (750 horas/mes)
- ✅ Muy confiable
- ✅ Escalable

**Desventajas:**
- ⚠️ Requiere tarjeta de crédito (no cobran si usas free tier)
- ⚠️ Configuración más compleja

**Pasos:**

1. Ve a: https://aws.amazon.com/free
2. Crea cuenta AWS
3. Busca "RDS" en la consola
4. Crea instancia MySQL (Free Tier)
5. Obtén credenciales de conexión

---

## ⚙️ Configuración SSL

**Solo para PlanetScale:**

Si usas PlanetScale, necesitas activar SSL:

1. **Edita `backend/config/database.env`:**
   ```env
   DB_SSL=true
   ```

2. **El archivo `backend/config/database.php` ya está actualizado** para soportar SSL automáticamente.

**Para otras opciones (Railway, Aiven, etc.):**
- NO necesitas configurar SSL
- Solo deja `DB_SSL` sin definir o como `false`

---

## 📝 Resumen

| Opción | Gratis | SSL | Facilidad | Recomendado |
|--------|--------|-----|-----------|-------------|
| **PlanetScale** | ✅ Sí | ⚠️ Sí | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| **Railway** | ✅ Sí ($5/mes) | ❌ No | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| **Aiven** | ✅ Sí | ❌ No | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| **AWS RDS** | ✅ 12 meses | ❌ No | ⭐⭐⭐ | ⭐⭐⭐ |

**Mi recomendación:** Empieza con **PlanetScale** o **Railway**. Son las más fáciles.

---

**¿Necesitas ayuda configurando alguna?** Todas funcionan con el mismo código, solo cambian las credenciales.

