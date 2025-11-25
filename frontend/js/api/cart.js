/**
 * Servicio del carrito
 */

import { apiGet, apiPost, apiPut, apiDelete } from './config.js';

/**
 * Obtener el carrito del usuario
 */
export async function getCart() {
    try {
        const response = await apiGet('cart/get.php');
        if (response.success) {
            return response.data || [];
        }
        return [];
    } catch (error) {
        console.error('Error obteniendo carrito:', error);
        return [];
    }
}

/**
 * Agregar producto al carrito
 */
export async function addToCart(productId, quantity = 1) {
    const response = await apiPost('cart/add.php', {
        product_id: productId,
        quantity
    });
    
    if (!response.success) {
        throw new Error(response.error || 'Error al agregar al carrito');
    }
    
    return response;
}

/**
 * Actualizar cantidad de un producto en el carrito
 */
export async function updateCartItem(productId, quantity) {
    const response = await apiPut('cart/update.php', {
        product_id: productId,
        quantity
    });
    
    if (!response.success) {
        throw new Error(response.error || 'Error al actualizar carrito');
    }
    
    return response;
}

/**
 * Eliminar producto del carrito
 */
export async function removeFromCart(productId) {
    const response = await apiDelete(`cart/remove.php?product_id=${encodeURIComponent(productId)}`);
    
    if (!response.success) {
        throw new Error(response.error || 'Error al eliminar del carrito');
    }
    
    return response;
}

/**
 * Vaciar el carrito
 */
export async function clearCart() {
    const response = await apiPost('cart/clear.php', {});
    
    if (!response.success) {
        throw new Error(response.error || 'Error al vaciar carrito');
    }
    
    return response;
}

