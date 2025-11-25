/**
 * Servicio de productos
 * NOTA: El backend no tiene endpoints de productos, se usan datos estáticos
 */

import { products, categories } from '../products.js';

/**
 * Obtener todos los productos (desde datos estáticos)
 */
export async function getProducts() {
    // Usar productos estáticos ya que el backend no tiene este endpoint
    return products;
}

/**
 * Obtener un producto por ID (desde datos estáticos)
 */
export async function getProduct(id) {
    // Usar productos estáticos ya que el backend no tiene este endpoint
    const product = products.find(p => p.id === id || p.code === id);
    if (!product) {
        throw new Error('Producto no encontrado');
    }
    return product;
}

/**
 * Obtener todas las categorías (desde datos estáticos)
 */
export async function getCategories() {
    // Usar categorías estáticas ya que el backend no tiene este endpoint
    return categories;
}

