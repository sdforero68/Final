#!/bin/bash

# Script para instalar Swagger en el backend

echo "🍞 Instalando Swagger para Anita Integrales API..."
echo ""

# Verificar si Composer está instalado
if ! command -v composer &> /dev/null; then
    echo "❌ Composer no está instalado."
    echo ""
    echo "Por favor instala Composer primero:"
    echo ""
    echo "En macOS (con Homebrew):"
    echo "  brew install composer"
    echo ""
    echo "O descarga desde: https://getcomposer.org/download/"
    echo ""
    exit 1
fi

echo "✅ Composer encontrado: $(composer --version)"
echo ""

# Navegar al directorio backend
cd "$(dirname "$0")"

echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Swagger instalado exitosamente!"
    echo ""
    echo "Para ver la documentación:"
    echo "  1. Inicia el servidor: php -S localhost:8080 router.php"
    echo "  2. Abre en el navegador: http://localhost:8080/swagger-ui.php"
    echo ""
else
    echo ""
    echo "❌ Error instalando dependencias"
    exit 1
fi

