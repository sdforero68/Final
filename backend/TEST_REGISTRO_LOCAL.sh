#!/bin/bash

# Script para probar registro e inicio de sesión localmente
# Esto te permite verificar que los datos se guarden en la base de datos

echo "🧪 Probando Registro e Inicio de Sesión"
echo "========================================"
echo ""

# Configuración
API_URL="http://localhost:8080"

# Colores
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Función para probar registro
test_registro() {
    echo -e "${YELLOW}1. Probando registro de usuario...${NC}"
    
    RESPONSE=$(curl -s -X POST "${API_URL}/auth/register.php" \
        -H "Content-Type: application/json" \
        -d '{
            "name": "Usuario Prueba",
            "email": "prueba@ejemplo.com",
            "password": "123456",
            "phone": "+573001234567"
        }')
    
    echo "Respuesta: $RESPONSE"
    echo ""
    
    # Extraer token si existe
    TOKEN=$(echo $RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    
    if [ ! -z "$TOKEN" ]; then
        echo -e "${GREEN}✅ Registro exitoso!${NC}"
        echo "Token: $TOKEN"
        echo ""
        echo "$TOKEN" > /tmp/test_token.txt
        return 0
    else
        echo -e "${RED}❌ Error en el registro${NC}"
        return 1
    fi
}

# Función para probar login
test_login() {
    echo -e "${YELLOW}2. Probando inicio de sesión...${NC}"
    
    RESPONSE=$(curl -s -X POST "${API_URL}/auth/login.php" \
        -H "Content-Type: application/json" \
        -d '{
            "email": "prueba@ejemplo.com",
            "password": "123456"
        }')
    
    echo "Respuesta: $RESPONSE"
    echo ""
    
    TOKEN=$(echo $RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)
    
    if [ ! -z "$TOKEN" ]; then
        echo -e "${GREEN}✅ Login exitoso!${NC}"
        echo "Token: $TOKEN"
        echo "$TOKEN" > /tmp/test_token.txt
        return 0
    else
        echo -e "${RED}❌ Error en el login${NC}"
        return 1
    fi
}

# Ejecutar pruebas
echo -e "${YELLOW}Asegúrate de que el servidor esté corriendo:${NC}"
echo "cd backend && php -S localhost:8080 router.php"
echo ""
read -p "¿El servidor está corriendo? (s/n): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Ss]$ ]]; then
    echo "Inicia el servidor primero y vuelve a ejecutar este script"
    exit 1
fi

echo ""
test_registro
echo ""
test_login

echo ""
echo -e "${YELLOW}📋 Para verificar en phpMyAdmin:${NC}"
echo "Ejecuta estas consultas SQL:"
echo ""
echo "-- Ver usuarios registrados"
echo "SELECT id, email, name, phone, created_at FROM users;"
echo ""
echo "-- Ver sesiones activas"
echo "SELECT s.id, u.email, s.token, s.expires_at FROM sessions s JOIN users u ON s.user_id = u.id;"
echo ""

