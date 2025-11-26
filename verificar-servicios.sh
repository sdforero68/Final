#!/bin/bash

# Script para verificar que el backend (Render) y frontend (GitHub Pages) están funcionando
# Uso: ./verificar-servicios.sh

# Colores para la salida
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Verificación de Servicios${NC}"
echo -e "${BLUE}========================================${NC}"
echo ""

# URLs
BACKEND_URL="https://final-1-0wvc.onrender.com"
FRONTEND_URL1="https://sdforero68.github.io"
FRONTEND_URL2="https://sdforero68.github.io/Final"

# ==========================================
# VERIFICAR BACKEND (Render)
# ==========================================
echo -e "${YELLOW}🔍 Verificando Backend (Render)...${NC}"
echo "URL: $BACKEND_URL"
echo ""

# 1. Health Check
echo -n "  ✓ Health Check: "
HEALTH_RESPONSE=$(curl -s -w "\n%{http_code}" "${BACKEND_URL}/api/health.php" 2>&1)
HEALTH_CODE=$(echo "$HEALTH_RESPONSE" | tail -n1)
HEALTH_BODY=$(echo "$HEALTH_RESPONSE" | head -n-1)

if [ "$HEALTH_CODE" = "200" ]; then
    echo -e "${GREEN}OK${NC} (HTTP $HEALTH_CODE)"
    echo "  Respuesta: $HEALTH_BODY"
else
    echo -e "${RED}ERROR${NC} (HTTP $HEALTH_CODE)"
    echo "  Respuesta: $HEALTH_BODY"
fi
echo ""

# 2. API Index
echo -n "  ✓ API Index: "
INDEX_RESPONSE=$(curl -s -w "\n%{http_code}" "${BACKEND_URL}/api/index.php" 2>&1)
INDEX_CODE=$(echo "$INDEX_RESPONSE" | tail -n1)
INDEX_BODY=$(echo "$INDEX_RESPONSE" | head -n-1)

if [ "$INDEX_CODE" = "200" ]; then
    echo -e "${GREEN}OK${NC} (HTTP $INDEX_CODE)"
    # Mostrar solo el nombre y status del JSON
    API_NAME=$(echo "$INDEX_BODY" | grep -o '"name":"[^"]*"' | head -1 | cut -d'"' -f4)
    API_STATUS=$(echo "$INDEX_BODY" | grep -o '"status":"[^"]*"' | head -1 | cut -d'"' -f4)
    echo "  API: $API_NAME - Estado: $API_STATUS"
else
    echo -e "${RED}ERROR${NC} (HTTP $INDEX_CODE)"
fi
echo ""

# 3. Products List (endpoint público)
echo -n "  ✓ Products List: "
PRODUCTS_RESPONSE=$(curl -s -w "\n%{http_code}" "${BACKEND_URL}/api/products/list.php" 2>&1)
PRODUCTS_CODE=$(echo "$PRODUCTS_RESPONSE" | tail -n1)
PRODUCTS_BODY=$(echo "$PRODUCTS_RESPONSE" | head -n-1)

if [ "$PRODUCTS_CODE" = "200" ]; then
    echo -e "${GREEN}OK${NC} (HTTP $PRODUCTS_CODE)"
    # Contar productos si la respuesta es JSON válido
    PRODUCT_COUNT=$(echo "$PRODUCTS_BODY" | grep -o '"id"' | wc -l | xargs)
    if [ ! -z "$PRODUCT_COUNT" ] && [ "$PRODUCT_COUNT" -gt 0 ]; then
        echo "  Productos encontrados: $PRODUCT_COUNT"
    fi
else
    echo -e "${RED}ERROR${NC} (HTTP $PRODUCTS_CODE)"
fi
echo ""

# ==========================================
# VERIFICAR FRONTEND (GitHub Pages)
# ==========================================
echo -e "${YELLOW}🔍 Verificando Frontend (GitHub Pages)...${NC}"
echo ""

# Intentar con la primera URL
echo -n "  ✓ Verificando ${FRONTEND_URL1}: "
FRONTEND1_RESPONSE=$(curl -s -w "\n%{http_code}" "${FRONTEND_URL1}" 2>&1)
FRONTEND1_CODE=$(echo "$FRONTEND1_RESPONSE" | tail -n1)

if [ "$FRONTEND1_CODE" = "200" ]; then
    echo -e "${GREEN}OK${NC} (HTTP $FRONTEND1_CODE)"
    # Verificar si contiene contenido HTML
    if echo "$FRONTEND1_RESPONSE" | head -n-1 | grep -q "<html\|<!DOCTYPE"; then
        echo "  ✓ Contenido HTML válido"
    fi
else
    echo -e "${YELLOW}No disponible${NC} (HTTP $FRONTEND1_CODE)"
fi
echo ""

# Intentar con la segunda URL (con subdirectorio)
echo -n "  ✓ Verificando ${FRONTEND_URL2}: "
FRONTEND2_RESPONSE=$(curl -s -w "\n%{http_code}" "${FRONTEND_URL2}" 2>&1)
FRONTEND2_CODE=$(echo "$FRONTEND2_RESPONSE" | tail -n1)

if [ "$FRONTEND2_CODE" = "200" ]; then
    echo -e "${GREEN}OK${NC} (HTTP $FRONTEND2_CODE)"
    # Verificar si contiene contenido HTML
    if echo "$FRONTEND2_RESPONSE" | head -n-1 | grep -q "<html\|<!DOCTYPE"; then
        echo "  ✓ Contenido HTML válido"
        # Verificar si carga el archivo de configuración de la API
        if echo "$FRONTEND2_RESPONSE" | head -n-1 | grep -q "config.js"; then
            echo "  ✓ Archivo config.js encontrado"
        fi
    fi
else
    echo -e "${YELLOW}No disponible${NC} (HTTP $FRONTEND2_CODE)"
fi
echo ""

# ==========================================
# RESUMEN
# ==========================================
echo -e "${BLUE}========================================${NC}"
echo -e "${BLUE}  Resumen${NC}"
echo -e "${BLUE}========================================${NC}"

if [ "$HEALTH_CODE" = "200" ] && [ "$INDEX_CODE" = "200" ]; then
    echo -e "${GREEN}✓ Backend (Render): FUNCIONANDO${NC}"
else
    echo -e "${RED}✗ Backend (Render): ERROR${NC}"
fi

if [ "$FRONTEND1_CODE" = "200" ] || [ "$FRONTEND2_CODE" = "200" ]; then
    echo -e "${GREEN}✓ Frontend (GitHub Pages): FUNCIONANDO${NC}"
    if [ "$FRONTEND2_CODE" = "200" ]; then
        echo -e "  URL activa: ${FRONTEND_URL2}"
    elif [ "$FRONTEND1_CODE" = "200" ]; then
        echo -e "  URL activa: ${FRONTEND_URL1}"
    fi
else
    echo -e "${RED}✗ Frontend (GitHub Pages): NO DISPONIBLE${NC}"
fi

echo ""
echo -e "${BLUE}========================================${NC}"


