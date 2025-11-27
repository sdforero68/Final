/**
 * sync.js - Sistema de sincronización global
 * Sincroniza carrito, login y estado entre todas las páginas
 */

import { getCart, saveCart, getCartItemsCount, CART_STORAGE_KEY } from './main.js';

// =====================
// Sincronización del Carrito
// =====================

/**
 * Actualiza el badge del carrito en todas las páginas
 * @param {boolean} skipEvent - Si es true, no dispara el evento cartUpdated (para evitar recursión)
 */
export function updateCartBadge(skipEvent = false) {
  const count = getCartItemsCount();
  const badges = Array.from(document.querySelectorAll('.cart-badge, #cart-badge'));
  
  badges.forEach((badge) => {
    if (count > 0) {
      badge.textContent = String(count);
      badge.hidden = false;
    } else {
      badge.hidden = true;
    }
  });
  
  // Disparar evento personalizado solo si no se está saltando (para evitar recursión)
  if (!skipEvent) {
    window.dispatchEvent(new CustomEvent('cartUpdated', { 
      detail: { count, items: getCart() } 
    }));
  }
}

/**
 * Agrega un producto al carrito y actualiza el badge
 */
export async function addToCart(product) {
  try {
    // Verificar si el usuario está autenticado
    const token = localStorage.getItem('accessToken') || localStorage.getItem('current_session');
    
    if (token) {
      // Usar API si está autenticado
      // Priorizar code sobre id, ya que la API usa code como identificador principal
      const productId = product.code || product.id;
      
      if (!productId) {
        console.error('Producto sin ID o código:', product);
        throw new Error('El producto no tiene un identificador válido');
      }
      
      console.log('Agregando producto al carrito (API):', { productId, product });
      
      const { addToCart: apiAddToCart } = await import('./api/cart.js');
      const result = await apiAddToCart(productId, 1);
      
      console.log('Producto agregado exitosamente:', result);
      
      // Actualizar carrito local desde la API
      await syncCartFromAPI();
    } else {
      // Usar localStorage si no está autenticado
      const cart = getCart();
      
      // Buscar si el producto ya está en el carrito
      const existingItem = cart.find(item => item.id === product.id);
      
      if (existingItem) {
        // Incrementar cantidad
        existingItem.quantity += 1;
      } else {
        // Agregar nuevo item
        cart.push({
          id: product.id || product.code,
          name: product.name,
          price: product.price,
          quantity: 1,
          image: product.image,
          category: product.category,
          description: product.description
        });
      }
      
      // Guardar carrito
      saveCart(cart);
    }
    
    // Actualizar badge
    updateCartBadge();
    
    // Mostrar notificación si está disponible
    if (window.toast) {
      window.toast.success(`${product.name} agregado al carrito`);
    }
  } catch (error) {
    console.error('Error agregando al carrito:', error);
    console.error('Detalles del error:', {
      message: error.message,
      stack: error.stack,
      product: product
    });
    
    // Si el error es de autenticación, no hacer fallback a localStorage
    if (error.message && (error.message.includes('401') || error.message.includes('No autenticado'))) {
      if (window.toast) {
        window.toast.error('Por favor inicia sesión para agregar productos al carrito');
      }
      throw error;
    }
    
    // Fallback a localStorage solo si no está autenticado o si hay un error de red
    const token = localStorage.getItem('accessToken') || localStorage.getItem('current_session');
    if (!token) {
      const cart = getCart();
      const productId = product.code || product.id;
      const existingItem = cart.find(item => item.id === productId || item.id === product.id);
      
      if (existingItem) {
        existingItem.quantity += 1;
      } else {
        cart.push({
          id: productId || product.id,
          name: product.name,
          price: product.price,
          quantity: 1,
          image: product.image,
          category: product.category,
          description: product.description
        });
      }
      saveCart(cart);
      updateCartBadge();
      
      if (window.toast) {
        window.toast.success(`${product.name} agregado al carrito`);
      }
    } else {
      // Si está autenticado pero falló la API, mostrar error
      if (window.toast) {
        window.toast.error(`Error al agregar ${product.name} al carrito. Por favor intenta de nuevo.`);
      }
      throw error;
    }
  }
}

/**
 * Sincronizar carrito desde la API
 */
async function syncCartFromAPI() {
  try {
    const token = localStorage.getItem('accessToken') || localStorage.getItem('current_session');
    if (!token) {
      console.log('No hay token, saltando sincronización de carrito');
      return;
    }
    
    console.log('Sincronizando carrito desde API...');
    const { getCart: apiGetCart } = await import('./api/cart.js');
    const apiCart = await apiGetCart();
    
    console.log('Carrito sincronizado desde API:', apiCart);
    
    // Guardar en localStorage para compatibilidad
    saveCart(apiCart);
    
    // Actualizar badge después de sincronizar
    updateCartBadge();
  } catch (error) {
    console.error('Error sincronizando carrito:', error);
    // No lanzar error, solo loguear para no interrumpir el flujo
  }
}

/**
 * Actualiza la cantidad de un producto en el carrito
 */
export function updateCartQuantity(productId, newQuantity) {
  const cart = getCart();
  
  if (newQuantity <= 0) {
    return removeFromCart(productId);
  }
  
  const updatedCart = cart.map(item =>
    item.id === productId ? { ...item, quantity: newQuantity } : item
  );
  
  saveCart(updatedCart);
  updateCartBadge();
  
  return updatedCart;
}

/**
 * Elimina un producto del carrito
 */
export function removeFromCart(productId) {
  const cart = getCart();
  const updatedCart = cart.filter(item => item.id !== productId);
  
  saveCart(updatedCart);
  updateCartBadge();
  
  // Mostrar notificación si está disponible
  if (window.toast) {
    window.toast.success('Producto eliminado del carrito');
  }
  
  return updatedCart;
}

// =====================
// Sincronización del Login
// =====================

/**
 * Verifica si el usuario está logueado
 */
export function isLoggedIn() {
  const accessToken = localStorage.getItem('accessToken');
  const user = localStorage.getItem('user');
  return !!(accessToken && user);
}

/**
 * Obtiene la información del usuario actual
 */
export function getCurrentUser() {
  const userStr = localStorage.getItem('user');
  if (!userStr) return null;
  
  try {
    return JSON.parse(userStr);
  } catch (e) {
    return null;
  }
}

/**
 * Actualiza el menú de usuario en el navbar
 */
export function updateUserMenu() {
  const userMenu = document.getElementById('user-menu');
  const userMenuName = document.getElementById('user-menu-name');
  const userMenuEmail = document.getElementById('user-menu-email');
  const userMenuBtn = document.getElementById('user-menu-btn');
  const userMenuHeader = userMenu?.querySelector('.user-menu-header');
  const userMenuDivider = userMenu?.querySelector('.user-menu-divider');
  const userMenuItems = userMenu?.querySelectorAll('.user-menu-item');
  const loginMenuItem = document.getElementById('user-menu-login');
  const registerMenuItem = document.getElementById('user-menu-register');
  const profileMenuItem = document.getElementById('user-menu-profile');
  const logoutMenuItem = document.getElementById('logout-btn-navbar');
  
  if (isLoggedIn()) {
    const user = getCurrentUser();
    
    // Mostrar información del usuario
    if (userMenuName && user) {
      userMenuName.textContent = user?.user_metadata?.name || 'Usuario';
    }
    
    if (userMenuEmail && user) {
      userMenuEmail.textContent = user?.email || '-';
    }
    
    // Mostrar elementos de usuario logueado
    if (userMenuHeader) userMenuHeader.hidden = false;
    if (userMenuDivider) userMenuDivider.hidden = false;
    if (profileMenuItem) profileMenuItem.hidden = false;
    if (logoutMenuItem) logoutMenuItem.hidden = false;
    
    // Ocultar opciones de login/registro
    if (loginMenuItem) loginMenuItem.hidden = true;
    if (registerMenuItem) registerMenuItem.hidden = true;
    
    if (userMenu) {
      userMenu.hidden = false;
    }
  } else {
    // Ocultar información del usuario
    if (userMenuHeader) userMenuHeader.hidden = true;
    if (userMenuDivider) userMenuDivider.hidden = true;
    if (profileMenuItem) profileMenuItem.hidden = true;
    if (logoutMenuItem) logoutMenuItem.hidden = true;
    
    // Mostrar opciones de login/registro
    if (loginMenuItem) loginMenuItem.hidden = false;
    if (registerMenuItem) registerMenuItem.hidden = false;
    
    if (userMenu) {
      userMenu.hidden = false;
    }
  }
}

/**
 * Maneja el logout
 */
export function handleLogout() {
  // Limpiar localStorage
  localStorage.removeItem('accessToken');
  localStorage.removeItem('user');
  localStorage.removeItem('current_user');
  localStorage.removeItem('current_session');
  
  // Actualizar menú
  updateUserMenu();
  
  // Disparar evento personalizado
  window.dispatchEvent(new CustomEvent('userLoggedOut'));
  
  // Redirigir según la página actual
  const currentPage = window.location.pathname.split('/').pop();
  
  if (currentPage === 'profile.html' || currentPage === 'checkout.html') {
    window.location.href = './pages/login/index.html';
  } else {
    window.location.reload();
  }
}

// =====================
// Inicialización
// =====================

/**
 * Inicializa la sincronización en todas las páginas
 */
export function initSync() {
  // Actualizar badge del carrito al cargar
  updateCartBadge();
  
  // Actualizar menú de usuario al cargar
  updateUserMenu();
  
  // Escuchar cambios en localStorage (para sincronización entre pestañas)
  window.addEventListener('storage', (e) => {
    if (e.key === CART_STORAGE_KEY) {
      updateCartBadge();
    } else if (e.key === 'accessToken' || e.key === 'user') {
      updateUserMenu();
    }
  });
  
  // Escuchar eventos personalizados de actualización de carrito
  // Usar skipEvent=true para evitar recursión infinita
  window.addEventListener('cartUpdated', () => {
    updateCartBadge(true);
  });
  
  // Configurar botón de usuario
  const userMenuBtn = document.getElementById('user-menu-btn');
  const userMenu = document.getElementById('user-menu');
  const userMenuWrapper = document.querySelector('.user-menu-wrapper');
  
  if (userMenuBtn) {
    userMenuBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      
      // Toggle del menú desplegable
      if (userMenu) {
        userMenu.hidden = !userMenu.hidden;
      }
    });
  }
  
  // Cerrar el menú al hacer clic fuera de él
  document.addEventListener('click', (e) => {
    if (userMenu && userMenuWrapper && !userMenuWrapper.contains(e.target)) {
      userMenu.hidden = true;
    }
  });
  
  // Mantener el menú desplegable oculto por defecto
  if (userMenu) {
    userMenu.hidden = true;
  }
  
  // Configurar botón de logout
  const logoutBtn = document.getElementById('logout-btn-navbar');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', handleLogout);
  }
  
  // Configurar botón de carrito para abrir el drawer
  // Esperar un momento para que cart-drawer.js se cargue primero
  setTimeout(async () => {
    try {
      const cartDrawerModule = await import('./cart-drawer.js');
      const openCartDrawer = cartDrawerModule.openCartDrawer;
      
      if (openCartDrawer) {
        document.querySelectorAll('[data-action="cart"]').forEach((btn) => {
          btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            openCartDrawer();
          });
        });
      }
    } catch (error) {
      console.error('Error loading cart drawer:', error);
      // Fallback: redirigir a la página del carrito
      document.querySelectorAll('[data-action="cart"]').forEach((btn) => {
        btn.addEventListener('click', () => {
          const getRelativePath = (targetPage) => {
            const currentPath = window.location.pathname;
            if (currentPath.includes('/pages/')) {
              return `../${targetPage}/index.html`;
            } else {
              return `./pages/${targetPage}/index.html`;
            }
          };
          window.location.href = getRelativePath('cart');
        });
      });
    }
  }, 100);
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initSync);
} else {
  initSync();
}

