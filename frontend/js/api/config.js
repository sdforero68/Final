/**
 * Configuración de la API
 */

// URL base de la API - Detecta automáticamente el entorno
// En desarrollo: localhost
// En producción: GitHub Pages, Vercel, etc.
const getApiBaseUrl = () => {
    // Si estamos en localhost (desarrollo)
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
      return 'http://localhost/backend/api';
    }
    
    // Si estamos en producción (GitHub Pages, Vercel, etc.)
    // URL del backend en Render
    const PRODUCTION_API_URL = 'https://api-mhib.onrender.com';
    
    return PRODUCTION_API_URL;
  };
  
  const API_BASE_URL = getApiBaseUrl();
  
  /**
   * Realiza una petición HTTP a la API
   */
  async function apiRequest(endpoint, options = {}) {
      const url = `${API_BASE_URL}/${endpoint}`;
      
      // Obtener token de autenticación
      const token = localStorage.getItem('accessToken') || localStorage.getItem('current_session');
      
      // Configurar headers
      const headers = {
          'Content-Type': 'application/json',
          ...options.headers
      };
      
      if (token) {
          headers['Authorization'] = `Bearer ${token}`;
      }
      
      // Configurar opciones de la petición
      const config = {
          ...options,
          headers,
          credentials: 'include'
      };
      
      try {
          const response = await fetch(url, config);
          
          // Si no hay contenido, el backend no está respondiendo
          if (!response.ok && response.status === 0) {
              throw new Error('No se pudo conectar con el servidor. Por favor intenta más tarde.');
          }
          
          let data;
          try {
              data = await response.json();
          } catch (e) {
              // Si no se puede parsear JSON, el backend no está respondiendo correctamente
              throw new Error('El servidor no está respondiendo correctamente. Por favor intenta más tarde.');
          }
          
          if (!response.ok) {
              throw new Error(data.error || `Error ${response.status}: ${response.statusText}`);
          }
          
          return data;
      } catch (error) {
          console.error('API Error:', error);
          // Si es un error de red, dar mensaje más claro
          if (error.message.includes('fetch') || error.message.includes('Failed to fetch')) {
              throw new Error('No se pudo conectar con el servidor. Verifica tu conexión o intenta más tarde.');
          }
          throw error;
      }
  }
  
  /**
   * GET request
   */
  async function apiGet(endpoint) {
      return apiRequest(endpoint, { method: 'GET' });
  }
  
  /**
   * POST request
   */
  async function apiPost(endpoint, body) {
      return apiRequest(endpoint, {
          method: 'POST',
          body: JSON.stringify(body)
      });
  }
  
  /**
   * PUT request
   */
  async function apiPut(endpoint, body) {
      return apiRequest(endpoint, {
          method: 'PUT',
          body: JSON.stringify(body)
      });
  }
  
  /**
   * DELETE request
   */
  async function apiDelete(endpoint) {
      return apiRequest(endpoint, { method: 'DELETE' });
  }
  
  export { apiGet, apiPost, apiPut, apiDelete, API_BASE_URL };
  
  