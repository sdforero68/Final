/**
 * Servicio de productos
 * Usa la API del backend para obtener productos y categorías
 */

import { apiGet } from './config.js';

/**
 * Obtener todos los productos desde la API
 */
export async function getProducts() {
    try {
        const response = await apiGet('products/list.php');
        if (response.success && response.data) {
            return response.data;
        }
        return [];
    } catch (error) {
        console.error('Error obteniendo productos:', error);
        // Fallback a datos estáticos si la API falla
        try {
            const { products } = await import('../products.js');
            return products || [];
        } catch (e) {
            return [];
        }
    }
}

/**
 * Obtener un producto por ID o código desde la API
 */
export async function getProduct(id) {
    try {
        const response = await apiGet(`products/get.php?id=${encodeURIComponent(id)}`);
        if (response.success && response.data) {
            return response.data;
        }
        throw new Error('Producto no encontrado');
    } catch (error) {
        console.error('Error obteniendo producto:', error);
        // Fallback a datos estáticos si la API falla
        try {
            const { products } = await import('../products.js');
            const product = products.find(p => p.id === id || p.code === id);
            if (product) {
                return product;
            }
        } catch (e) {
            // Ignorar error de importación
        }
        throw new Error('Producto no encontrado');
    }
}

/**
 * Obtener todas las categorías desde la API
 */
export async function getCategories() {
    try {
        const response = await apiGet('products/categories.php');
        if (response.success && response.data) {
            return response.data;
        }
        return [];
    } catch (error) {
        console.error('Error obteniendo categorías:', error);
        // Fallback a datos estáticos si la API falla
        try {
            const { categories } = await import('../products.js');
            return categories || [];
        } catch (e) {
            return [];
        }
    }
}

