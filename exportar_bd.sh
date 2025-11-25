#!/bin/bash

# Script para exportar la base de datos local
# Uso: ./exportar_bd.sh

echo "🔄 Exportando base de datos local..."

# Configuración
DB_NAME="anita_integrales"
DB_USER="root"
DB_PASSWORD="Naniela2928**"
BACKUP_FILE="backup_anita_integrales_$(date +%Y%m%d_%H%M%S).sql"

# Exportar
echo "📦 Creando backup: $BACKUP_FILE"
mysqldump -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" > "$BACKUP_FILE"

# Verificar
if [ -f "$BACKUP_FILE" ]; then
    FILE_SIZE=$(ls -lh "$BACKUP_FILE" | awk '{print $5}')
    echo "✅ Backup creado exitosamente: $BACKUP_FILE"
    echo "📊 Tamaño: $FILE_SIZE"
    echo ""
    echo "📝 Próximos pasos:"
    echo "1. Crea una base de datos en PlanetScale o Railway"
    echo "2. Importa este archivo a la base de datos en la nube"
    echo "3. Actualiza las credenciales en Render"
    echo ""
    echo "📖 Ver: MIGRAR_BD_LOCAL.md para instrucciones detalladas"
else
    echo "❌ Error al crear el backup"
    exit 1
fi

