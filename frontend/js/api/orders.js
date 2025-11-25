/**
 * Servicio de pedidos
 * Adaptado para usar los endpoints disponibles en el backend
 */

import { apiGet, apiPost } from './config.js';

/**
 * Crear un nuevo pedido
 * Usa POST /orders.php
 */
export async function createOrder(orderData) {
    try {
        const response = await apiPost('orders.php', {
            delivery_method: orderData.deliveryMethod || orderData.delivery_method,
            payment_method: orderData.paymentMethod || orderData.payment_method,
            customer_info: orderData.customerInfo || orderData.customer_info,
            items: orderData.items || [],
            total: orderData.total || 0,
            subtotal: orderData.subtotal || 0,
            deliveryFee: orderData.deliveryFee || 0
        });
        
        if (response.success) {
            return response.data || response.order;
        }
        throw new Error(response.error || response.message || 'Error al crear pedido');
    } catch (error) {
        // Si falla la API, usar localStorage como fallback
        console.warn('Error creando pedido en API, usando localStorage:', error);
        return createOrderLocal(orderData);
    }
}

/**
 * Obtener todos los pedidos del usuario
 * Usa GET /orders.php?userId=X
 */
export async function getOrders(userId = null) {
    try {
        // Si no hay userId, intentar obtenerlo del usuario logueado
        if (!userId) {
            const userStr = localStorage.getItem('user') || localStorage.getItem('current_user');
            if (userStr) {
                try {
                    const user = JSON.parse(userStr);
                    userId = user.id || user.email;
                } catch (e) {
                    // Ignorar error
                }
            }
        }
        
        if (!userId) {
            // Si no hay userId, devolver vacío
            return [];
        }
        
        const response = await apiGet(`orders.php?userId=${encodeURIComponent(userId)}`);
        
        if (response.success) {
            return response.data || response.orders || [];
        }
        
        throw new Error(response.error || response.message || 'Error al obtener pedidos');
    } catch (error) {
        // Si falla la API, usar localStorage como fallback
        console.warn('Error obteniendo pedidos de API, usando localStorage:', error);
        return getOrdersLocal(userId);
    }
}

/**
 * Obtener un pedido por ID
 * El backend no tiene este endpoint, usar localStorage
 */
export async function getOrder(orderId) {
    // El backend no tiene endpoint para obtener un pedido por ID
    // Usar localStorage
    return getOrderLocal(orderId);
}

/**
 * Funciones de fallback usando localStorage
 */
function createOrderLocal(orderData) {
    const ORDERS_STORAGE_KEY = 'app_orders';
    const ordersStr = localStorage.getItem(ORDERS_STORAGE_KEY);
    const orders = ordersStr ? JSON.parse(ordersStr) : [];
    
    const orderId = 'order_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    const order = {
        ...orderData,
        id: orderId,
        createdAt: new Date().toISOString(),
        status: 'pendiente'
    };
    
    orders.push(order);
    localStorage.setItem(ORDERS_STORAGE_KEY, JSON.stringify(orders));
    
    return order;
}

function getOrdersLocal(userId) {
    const ORDERS_STORAGE_KEY = 'app_orders';
    const ordersStr = localStorage.getItem(ORDERS_STORAGE_KEY);
    const allOrders = ordersStr ? JSON.parse(ordersStr) : [];
    
    if (!userId) {
        return allOrders;
    }
    
    return allOrders.filter(order => 
        order.userId === userId || 
        order.customer_info?.email === userId ||
        order.customerInfo?.email === userId
    );
}

function getOrderLocal(orderId) {
    const ORDERS_STORAGE_KEY = 'app_orders';
    const ordersStr = localStorage.getItem(ORDERS_STORAGE_KEY);
    const allOrders = ordersStr ? JSON.parse(ordersStr) : [];
    
    return allOrders.find(order => order.id === orderId) || null;
}

