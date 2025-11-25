# 🔄 Guía de Integración Frontend-Backend

Esta guía explica cómo el frontend puede usar la API del backend en lugar de localStorage.

## 📋 Estado Actual

El frontend actualmente usa **localStorage** para:
- Autenticación (usuarios, sesiones)
- Carrito de compras
- Pedidos

El backend proporciona **endpoints API** que replican esta funcionalidad usando **MySQL**.

## 🔌 Servicios API Disponibles

Los servicios API ya están creados en `frontend/js/api/`:

### Autenticación (`api/auth.js`)
```javascript
import { register, login, logout, verifyToken } from './api/auth.js';

// Registrar usuario
await register({ name, email, phone, password });

// Iniciar sesión
await login(email, password);

// Cerrar sesión
await logout();

// Verificar token
const user = await verifyToken();
```

### Productos (`api/products.js`)
```javascript
import { getProducts, getProduct, getCategories } from './api/products.js';

// Obtener todos los productos
const products = await getProducts();

// Obtener un producto
const product = await getProduct('pan-queso-grande');

// Obtener categorías
const categories = await getCategories();
```

### Carrito (`api/cart.js`)
```javascript
import { getCart, addToCart, updateCartItem, removeFromCart } from './api/cart.js';

// Obtener carrito
const cart = await getCart();

// Agregar producto
await addToCart('pan-queso-grande', 2);

// Actualizar cantidad
await updateCartItem('pan-queso-grande', 3);

// Eliminar producto
await removeFromCart('pan-queso-grande');
```

### Pedidos (`api/orders.js`)
```javascript
import { createOrder, getOrders, getOrder } from './api/orders.js';

// Crear pedido
const order = await createOrder({
    deliveryMethod: 'delivery',
    paymentMethod: 'cash',
    customerInfo: { name, email, phone, address }
});

// Listar pedidos del usuario
const orders = await getOrders();

// Obtener un pedido
const order = await getOrder(orderId);
```

## 🔄 Migración Gradual

Para migrar el frontend a usar la API, puedes seguir estos pasos:

### Paso 1: Actualizar Autenticación

**Archivo:** `frontend/js/pages/login/index.js`

Reemplazar las funciones de localStorage por llamadas a la API:

```javascript
// ANTES (localStorage)
function handleLogin(e) {
    const users = getUsers();
    const user = users.find(u => u.email === email);
    // ...
}

// DESPUÉS (API)
import { login } from '../../../api/auth.js';

async function handleLogin(e) {
    try {
        await login(email, password);
        window.location.href = '../../index.html';
    } catch (error) {
        showError('login', error.message);
    }
}
```

### Paso 2: Actualizar Carrito

**Archivo:** `frontend/js/sync.js`

```javascript
// ANTES (localStorage)
export function addToCart(product) {
    const cart = getCart();
    // ... guardar en localStorage
    saveCart(cart);
}

// DESPUÉS (API)
import { addToCart as apiAddToCart } from './api/cart.js';
import { getCart as apiGetCart } from './api/cart.js';

export async function addToCart(product) {
    try {
        await apiAddToCart(product.id, 1);
        await syncCartFromAPI(); // Actualizar estado local
        updateCartBadge();
    } catch (error) {
        // Fallback a localStorage si falla la API
        console.error('Error API, usando localStorage:', error);
        // ... código de localStorage como fallback
    }
}

async function syncCartFromAPI() {
    try {
        const cart = await apiGetCart();
        // Actualizar UI con el carrito de la API
    } catch (error) {
        // Usar localStorage como fallback
    }
}
```

### Paso 3: Actualizar Productos

**Archivo:** `frontend/js/main.js`

```javascript
// ANTES (import estático)
import { products } from './products.js';

// DESPUÉS (cargar desde API)
import { getProducts } from './api/products.js';

let products = [];

async function loadProducts() {
    try {
        products = await getProducts();
        renderGrid(); // Renderizar catálogo
    } catch (error) {
        console.error('Error cargando productos, usando datos locales:', error);
        // Usar productos locales como fallback
        import('./products.js').then(({ products: localProducts }) => {
            products = localProducts;
            renderGrid();
        });
    }
}

// Llamar al cargar
loadProducts();
```

## 🎯 Estrategia Híbrida (Recomendada)

Para mantener el frontend funcionando durante la transición, puedes implementar un **sistema híbrido**:

1. Intentar usar la API primero
2. Si falla, usar localStorage como fallback
3. Sincronizar datos cuando la conexión se restablezca

Ejemplo:

```javascript
async function addToCartHybrid(product) {
    try {
        // Intentar API
        await apiAddToCart(product.id, 1);
        // Si funciona, sincronizar localStorage
        const cart = await apiGetCart();
        localStorage.setItem('app_cart', JSON.stringify(cart));
    } catch (error) {
        // Fallback a localStorage
        console.warn('API no disponible, usando localStorage');
        const cart = getCart();
        // ... código de localStorage
        saveCart(cart);
    }
    updateCartBadge();
}
```

## ✅ Checklist de Integración

Para cada funcionalidad:

- [ ] **Autenticación**
  - [ ] Reemplazar registro con `api/auth.js`
  - [ ] Reemplazar login con `api/auth.js`
  - [ ] Actualizar verificación de sesión

- [ ] **Productos**
  - [ ] Cargar productos desde API
  - [ ] Mantener datos locales como fallback

- [ ] **Carrito**
  - [ ] Obtener carrito desde API
  - [ ] Agregar/actualizar/eliminar usando API
  - [ ] Sincronizar con localStorage

- [ ] **Pedidos**
  - [ ] Crear pedidos usando API
  - [ ] Cargar historial desde API
  - [ ] Actualizar vista de perfil

## 🔧 Configuración Necesaria

### 1. Actualizar URL de la API

Edita `frontend/js/api/config.js`:

```javascript
const API_BASE_URL = 'http://localhost/Integrales/backend/api';
```

Ajusta según tu configuración:
- PHP built-in: `http://localhost/Integrales/backend/api`
- Apache: `http://anita.local/backend/api`
- Otro puerto: `http://localhost:8080/backend/api`

### 2. Verificar CORS

El backend ya tiene CORS configurado en `backend/api/config.php`. Si tienes problemas, verifica que la URL del frontend esté permitida.

## 📝 Notas Importantes

1. **Compatibilidad**: El código actual seguirá funcionando con localStorage si la API no está disponible.

2. **Migración gradual**: Puedes migrar funcionalidad por funcionalidad sin romper nada.

3. **Testing**: Prueba cada funcionalidad después de migrarla.

4. **Errores**: Implementa manejo de errores apropiado y fallbacks a localStorage.

## 🚀 Próximos Pasos

1. Actualizar autenticación para usar la API
2. Migrar carrito a la API
3. Cargar productos desde la base de datos
4. Actualizar creación de pedidos
5. Cargar historial desde la API

---

¿Necesitas ayuda? Revisa los ejemplos en `frontend/js/api/` o consulta la documentación de cada servicio.

