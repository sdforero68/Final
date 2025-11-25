#!/bin/bash
# Script para iniciar los servidores de desarrollo

echo "🚀 Iniciando servidores de Anita Integrales..."
echo ""

# Función para verificar si un puerto está en uso
check_port() {
    if lsof -Pi :$1 -sTCP:LISTEN -t >/dev/null ; then
        return 0
    else
        return 1
    fi
}

# Verificar puerto 8000 (frontend)
if check_port 8000; then
    echo "⚠️  El puerto 8000 ya está en uso (frontend)"
else
    echo "✅ Puerto 8000 disponible para frontend"
fi

# Verificar puerto 80 (backend)
if check_port 80; then
    echo "⚠️  El puerto 80 ya está en uso (backend)"
    echo "   Necesitarás usar sudo para el backend"
else
    echo "✅ Puerto 80 disponible para backend"
fi

echo ""
echo "📋 Para iniciar los servidores, abre 2 terminales:"
echo ""
echo "Terminal 1 - Frontend:"
echo "  cd /Users/sdforero/Desktop/web4/Integrales/frontend"
echo "  php -S localhost:8000"
echo ""
echo "Terminal 2 - Backend:"
echo "  cd /Users/sdforero/Desktop/web4/Integrales"
echo "  sudo php -S localhost:80 -t ."
echo ""
echo "🌐 URLs:"
echo "  Frontend: http://localhost:8000"
echo "  API:      http://localhost/Integrales/backend/api/products/list.php"

