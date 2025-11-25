FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar TODO desde el directorio backend/ (asumiendo que el contexto es backend/)
# Esto copia api/, config/, sql/, Dockerfile, router.php, etc.
COPY . .

# GARANTIZAR que database.php esté en api/ (copiar desde config/ si no está)
RUN if [ ! -f /var/www/html/api/database.php ] && [ -f /var/www/html/config/database.php ]; then \
        cp /var/www/html/config/database.php /var/www/html/api/database.php && \
        echo "database.php copiado a api/"; \
    fi

# Verificar que database.php existe en api/
RUN test -f /var/www/html/api/database.php || (echo "ERROR CRITICO: database.php NO existe en api/" && exit 1)

# Mostrar estructura final para debug
RUN echo "=== Estructura final ===" && \
    echo "api/database.php:" && \
    ls -la /var/www/html/api/database.php && \
    echo "config/database.php:" && \
    ls -la /var/www/html/config/database.php || true

# Exponer puerto
EXPOSE 10000

# Comando de inicio - servir directamente desde api/
# Las rutas serán: /products/list.php, /products/categories.php (sin /api)
CMD php -S 0.0.0.0:${PORT:-10000} -t api
