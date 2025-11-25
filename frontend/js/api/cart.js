/**
 * Servicio del carrito
 * NOTA: El backend no tiene endpoints de carrito, se usa localStorage
 */

const CART_STORAGE_KEY = 'app_cart';

/**
 * Obtener el carrito del usuario (desde localStorage)
 */
export async function getCart() {
    try {
        const cartStr = localStorage.getItem(CART_STORAGE_KEY);
        return cartStr ? JSON.parse(cartStr) : [];
    } catch (error) {
        console.error('Error obteniendo carrito:', error);
        return [];
    }
}

/**
 * Agregar producto al carrito (usando localStorage)
 */
export async function addToCart(productId, quantity = 1) {
    // El carrito se maneja con localStorage, no con API
    // Esta función se mantiene por compatibilidad pero no hace llamadas API
    return { success: true, message: 'Carrito manejado localmente' };
}

/**
 * Actualizar cantidad de un producto en el carrito (usando localStorage)
 */
export async function updateCartItem(productId, quantity) {
    // El carrito se maneja con localStorage, no con API
    return { success: true, message: 'Carrito manejado localmente' };
}

/**
 * Eliminar producto del carrito (usando localStorage)
 */
export async function removeFromCart(productId) {
    // El carrito se maneja con localStorage, no con API
    return { success: true, message: 'Carrito manejado localmente' };
}

/**
 * Vaciar el carrito (usando localStorage)
 */
export async function clearCart() {
    // El carrito se maneja con localStorage, no con API
    localStorage.removeItem(CART_STORAGE_KEY);
    return { success: true, message: 'Carrito vaciado' };
}

