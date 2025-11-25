/**
 * Servicio de pedidos
 */

import { apiGet, apiPost } from './config.js';

/**
 * Crear un nuevo pedido
 */
export async function createOrder(orderData) {
    const response = await apiPost('orders/create.php', {
        delivery_method: orderData.deliveryMethod || orderData.delivery_method,
        payment_method: orderData.paymentMethod || orderData.payment_method,
        customer_info: orderData.customerInfo || orderData.customer_info
    });
    
    if (!response.success) {
        throw new Error(response.error || 'Error al crear pedido');
    }
    
    return response.data.order;
}

/**
 * Obtener todos los pedidos del usuario
 */
export async function getOrders() {
    const response = await apiGet('orders/list.php');
    if (response.success) {
        return response.data || [];
    }
    throw new Error(response.error || 'Error al obtener pedidos');
}

/**
 * Obtener un pedido por ID
 */
export async function getOrder(orderId) {
    const response = await apiGet(`orders/get.php?id=${encodeURIComponent(orderId)}`);
    if (response.success) {
        return response.data;
    }
    throw new Error(response.error || 'Error al obtener pedido');
}

