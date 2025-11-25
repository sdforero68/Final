/**
 * Servicio de productos
 */

import { apiGet } from './config.js';

/**
 * Obtener todos los productos
 */
export async function getProducts() {
    const response = await apiGet('products/list.php');
    if (response.success) {
        return response.data;
    }
    throw new Error(response.error || 'Error al obtener productos');
}

/**
 * Obtener un producto por ID
 */
export async function getProduct(id) {
    const response = await apiGet(`products/get.php?id=${encodeURIComponent(id)}`);
    if (response.success) {
        return response.data;
    }
    throw new Error(response.error || 'Error al obtener producto');
}

/**
 * Obtener todas las categorías
 */
export async function getCategories() {
    const response = await apiGet('products/categories.php');
    if (response.success) {
        return response.data;
    }
    throw new Error(response.error || 'Error al obtener categorías');
}

