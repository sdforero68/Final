/**
 * Servicio de pedidos
 * Adaptado para usar los endpoints disponibles en el backend
 */

import { apiGet, apiPost } from './config.js';

/**
 * Crear un nuevo pedido
 * Usa POST /orders/create.php
 */
export async function createOrder(orderData) {
    try {
        const response = await apiPost('orders/create.php', {
            delivery_method: orderData.deliveryMethod || orderData.delivery_method,
            payment_method: orderData.paymentMethod || orderData.payment_method,
            customer_info: orderData.customerInfo || orderData.customer_info
        });
        
        if (response.success && response.data) {
            return response.data.order || response.data;
        }
        throw new Error(response.error || response.message || 'Error al crear pedido');
    } catch (error) {
        console.error('Error creando pedido:', error);
        throw error;
    }
}

/**
 * Obtener todos los pedidos del usuario autenticado
 * Usa GET /orders/list.php
 */
export async function getOrders() {
    try {
        const response = await apiGet('orders/list.php');
        
        if (response.success && response.data) {
            return response.data;
        }
        
        return [];
    } catch (error) {
        console.error('Error obteniendo pedidos:', error);
        // Si no está autenticado o hay error, devolver vacío
        return [];
    }
}

/**
 * Obtener un pedido por ID
 * Usa GET /orders/get.php?id=xxx
 */
export async function getOrder(orderId) {
    try {
        const response = await apiGet(`orders/get.php?id=${encodeURIComponent(orderId)}`);
        
        if (response.success && response.data) {
            return response.data;
        }
        
        throw new Error(response.error || 'Pedido no encontrado');
    } catch (error) {
        console.error('Error obteniendo pedido:', error);
        throw error;
    }
}


