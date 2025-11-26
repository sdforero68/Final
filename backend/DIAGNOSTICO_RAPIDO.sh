#!/bin/bash

# Script de diagnóstico rápido para Render
echo "🔍 DIAGNÓSTICO DE RENDER"
echo "========================"
echo ""

API_URL="https://final-1-0wvc.onrender.com"

echo "1️⃣ Probando Health Check..."
echo "----------------------------"
RESPONSE=$(curl -s -w "\nHTTP_STATUS:%{http_code}" "$API_URL/health.php")
HTTP_STATUS=$(echo "$RESPONSE" | grep "HTTP_STATUS" | cut -d: -f2)
BODY=$(echo "$RESPONSE" | sed '/HTTP_STATUS/d')

if [ "$HTTP_STATUS" = "200" ]; then
    echo "✅ Health check funciona! (Status: $HTTP_STATUS)"
    echo "Respuesta: $BODY"
else
    echo "❌ Health check falló! (Status: ${HTTP_STATUS:-'No respuesta'})"
    echo "Respuesta: $BODY"
fi
echo ""

echo "2️⃣ Probando Endpoint de Registro..."
echo "------------------------------------"
RANDOM_EMAIL="test$(date +%s)@test.com"
RESPONSE=$(curl -s -w "\nHTTP_STATUS:%{http_code}" -X POST "$API_URL/api/auth/register.php" \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"Test User\",\"email\":\"$RANDOM_EMAIL\",\"password\":\"123456\"}")

HTTP_STATUS=$(echo "$RESPONSE" | grep "HTTP_STATUS" | cut -d: -f2)
BODY=$(echo "$RESPONSE" | sed '/HTTP_STATUS/d')

echo "Status: ${HTTP_STATUS:-'No respuesta'}"
echo "Respuesta:"
echo "$BODY" | head -20
echo ""

if echo "$BODY" | grep -q "success.*true"; then
    echo "✅ Registro funcionó!"
elif echo "$BODY" | grep -q "404"; then
    echo "❌ Error 404: El endpoint no existe. Verifica el router."
elif echo "$BODY" | grep -q "database\|Database\|DB_"; then
    echo "❌ Error de base de datos: Variables de entorno no configuradas o BD no conectada."
elif echo "$BODY" | grep -q "Failed to fetch\|CORS"; then
    echo "❌ Error de CORS o conexión."
else
    echo "⚠️ Respuesta inesperada. Revisa los logs de Render."
fi
echo ""

echo "3️⃣ Verificando rutas alternativas..."
echo "-------------------------------------"
for ROUTE in "/health.php" "/api/health.php" "/auth/register.php"; do
    STATUS=$(curl -s -o /dev/null -w "%{http_code}" "$API_URL$ROUTE")
    if [ "$STATUS" = "200" ]; then
        echo "✅ $ROUTE funciona (Status: $STATUS)"
    else
        echo "❌ $ROUTE no funciona (Status: $STATUS)"
    fi
done
echo ""

echo "📋 RECOMENDACIONES:"
echo "-------------------"
echo "1. Verifica que el servicio esté 'Live' en Render Dashboard"
echo "2. Revisa los logs en Render → Pestaña 'Logs'"
echo "3. Verifica que las variables de entorno estén configuradas:"
echo "   - DB_HOST=sql10.freesqldatabase.com"
echo "   - DB_PORT=3306"
echo "   - DB_NAME=sql10809318"
echo "   - DB_USER=sql10809318"
echo "   - DB_PASSWORD=t3qD3KjUSe"
echo "   - DB_SSL=false"
echo "   - PORT=10000"
echo ""
echo "4. Verifica que las tablas existan en phpMyAdmin"
echo "5. Si ves errores de 'database.php', las variables de entorno no están configuradas"
echo ""




