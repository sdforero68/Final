FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar TODO desde el directorio actual (backend/)
COPY . .

# GARANTIZAR que database.php esté en api/ 
RUN if [ ! -f /var/www/html/api/database.php ] && [ -f /var/www/html/config/database.php ]; then \
        cp /var/www/html/config/database.php /var/www/html/api/database.php && \
        echo "✅ database.php copiado de config/ a api/"; \
    fi

# Verificar que el archivo existe
RUN test -f /var/www/html/api/database.php || (echo "❌ ERROR CRITICO: database.php NO existe" && exit 1)

# Exponer puerto
EXPOSE 10000

# Comando de inicio - usar router.php para manejar todas las rutas
CMD php -S 0.0.0.0:${PORT:-10000} router.php
