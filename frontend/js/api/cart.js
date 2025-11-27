/**
 * Servicio del carrito
 * Usa la API del backend cuando el usuario está autenticado
 */

import { apiGet, apiPost, apiPut, apiDelete, apiRequest } from './config.js';

/**
 * Obtener el carrito del usuario desde la API
 */
export async function getCart() {
    try {
        const response = await apiGet('cart/get.php');
        if (response.success && response.data) {
            return response.data;
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
    try {
        const response = await apiPost('cart/add.php', {
            product_id: productId,
            quantity: quantity
        });
        return response;
    } catch (error) {
        console.error('Error agregando al carrito:', error);
        throw error;
    }
}

/**
 * Actualizar cantidad de un producto en el carrito
 */
export async function updateCartItem(productId, quantity) {
    try {
        const response = await apiPut('cart/update.php', {
            product_id: productId,
            quantity: quantity
        });
        return response;
    } catch (error) {
        console.error('Error actualizando carrito:', error);
        throw error;
    }
}

/**
 * Eliminar producto del carrito
 */
export async function removeFromCart(productId) {
    try {
        // DELETE con query string
        const response = await apiRequest(`cart/remove.php?product_id=${encodeURIComponent(productId)}`, {
            method: 'DELETE'
        });
        return response;
    } catch (error) {
        console.error('Error eliminando del carrito:', error);
        throw error;
    }
}

/**
 * Vaciar el carrito
 */
export async function clearCart() {
    try {
        const response = await apiPost('cart/clear.php', {});
        return response;
    } catch (error) {
        console.error('Error vaciando carrito:', error);
        throw error;
    }
}

