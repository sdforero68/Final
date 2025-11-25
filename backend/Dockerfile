FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar TODO desde el directorio actual (backend/)
COPY . .

# GARANTIZAR que database.php esté en api/ 
# (copiar desde config/ si no está ya ahí)
RUN if [ ! -f /var/www/html/api/database.php ] && [ -f /var/www/html/config/database.php ]; then \
        cp /var/www/html/config/database.php /var/www/html/api/database.php && \
        echo "✅ database.php copiado de config/ a api/"; \
    elif [ -f /var/www/html/api/database.php ]; then \
        echo "✅ database.php ya existe en api/"; \
    else \
        echo "❌ ERROR: database.php NO encontrado ni en config/ ni en api/"; \
        exit 1; \
    fi

# Verificar que el archivo existe
RUN test -f /var/www/html/api/database.php || (echo "❌ ERROR CRITICO: database.php NO existe en api/ después de copiar" && exit 1)

# Mostrar estructura para debug
RUN echo "=== ESTRUCTURA FINAL ===" && \
    echo "Archivos en api/:" && \
    ls -la /var/www/html/api/*.php | head -5 && \
    echo "database.php existe:" && \
    test -f /var/www/html/api/database.php && echo "✅ SÍ" || echo "❌ NO"

# Exponer puerto
EXPOSE 10000

# Comando de inicio - servir directamente desde api/
CMD php -S 0.0.0.0:${PORT:-10000} -t api
