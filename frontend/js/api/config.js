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
    // URL del backend en Render (sin barra final para evitar dobles barras)
    const PRODUCTION_API_URL = 'https://api-mhib.onrender.com';
    
    return PRODUCTION_API_URL;
  };
  
  const API_BASE_URL = getApiBaseUrl();
  
  /**
   * Realiza una petición HTTP a la API
   */
  async function apiRequest(endpoint, options = {}) {
      // Normalizar endpoint: remover barra inicial si existe y agregar una
      const normalizedEndpoint = endpoint.startsWith('/') ? endpoint.slice(1) : endpoint;
      const url = `${API_BASE_URL}/${normalizedEndpoint}`;
      
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
          
          // Obtener el texto de la respuesta primero para poder inspeccionarlo
          const responseText = await response.text();
          
          let data;
          try {
              data = responseText ? JSON.parse(responseText) : {};
          } catch (e) {
              // Si no se puede parsear JSON, el servidor devolvió algo que no es JSON
              // Esto puede pasar con errores 500 que devuelven HTML o texto plano
              if (!response.ok) {
                  // Intentar extraer mensaje de error si es HTML o texto
                  const errorMessage = responseText.includes('error') || responseText.includes('Error')
                      ? 'Error del servidor. Por favor intenta más tarde.'
                      : `Error ${response.status}: ${response.statusText}`;
                  throw new Error(errorMessage);
              }
              throw new Error('El servidor no está respondiendo correctamente. Por favor intenta más tarde.');
          }
          
          if (!response.ok) {
              // Manejar diferentes tipos de errores
              let errorMessage = 'Error desconocido';
              
              if (data.error) {
                  errorMessage = data.error;
              } else if (data.message) {
                  errorMessage = data.message;
              } else if (response.status === 500) {
                  errorMessage = 'Error interno del servidor. Por favor intenta más tarde.';
              } else if (response.status === 404) {
                  errorMessage = 'Recurso no encontrado.';
              } else if (response.status === 401) {
                  errorMessage = 'No autorizado. Por favor inicia sesión.';
              } else if (response.status === 403) {
                  errorMessage = 'Acceso denegado.';
              } else {
                  errorMessage = `Error ${response.status}: ${response.statusText}`;
              }
              
              throw new Error(errorMessage);
          }
          
          return data;
      } catch (error) {
          console.error('API Error:', error);
          // Si es un error de red, dar mensaje más claro
          if (error.message.includes('fetch') || error.message.includes('Failed to fetch') || error.name === 'TypeError') {
              throw new Error('No se pudo conectar con el servidor. Verifica tu conexión o intenta más tarde.');
          }
          // Si ya es un Error con mensaje, lanzarlo tal cual
          if (error instanceof Error) {
              throw error;
          }
          // Si es otro tipo de error, convertirlo a Error
          throw new Error(error.message || 'Error desconocido al comunicarse con el servidor.');
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
  
  