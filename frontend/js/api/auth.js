/**
 * Servicio de autenticación
 */

import { apiPost, apiGet } from './config.js';

/**
 * Registrar un nuevo usuario
 */
export async function register(userData) {
    const response = await apiPost('auth/register.php', {
        name: userData.name,
        email: userData.email,
        phone: userData.phone,
        password: userData.password
    });
    
    if (response.success && response.data) {
        // Guardar token y usuario en localStorage
        localStorage.setItem('accessToken', response.data.token);
        localStorage.setItem('current_session', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        localStorage.setItem('current_user', JSON.stringify(response.data.user));
        
        return response.data;
    }
    
    throw new Error(response.error || 'Error al registrar usuario');
}

/**
 * Iniciar sesión
 */
export async function login(email, password) {
    const response = await apiPost('auth/login.php', {
        email,
        password
    });
    
    if (response.success && response.data) {
        // Guardar token y usuario en localStorage
        localStorage.setItem('accessToken', response.data.token);
        localStorage.setItem('current_session', response.data.token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
        localStorage.setItem('current_user', JSON.stringify(response.data.user));
        
        return response.data;
    }
    
    throw new Error(response.error || 'Error al iniciar sesión');
}

/**
 * Cerrar sesión
 */
export async function logout() {
    try {
        await apiPost('auth/logout.php', {});
    } catch (error) {
        console.error('Error al cerrar sesión:', error);
    } finally {
        // Limpiar localStorage de todas formas
        localStorage.removeItem('accessToken');
        localStorage.removeItem('current_session');
        localStorage.removeItem('user');
        localStorage.removeItem('current_user');
    }
}

/**
 * Verificar token y obtener información del usuario
 */
export async function verifyToken() {
    try {
        const response = await apiGet('auth/verify.php');
        if (response.success && response.data) {
            return response.data.user;
        }
        return null;
    } catch (error) {
        return null;
    }
}

