FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de dependencias primero (para cache de Docker)
COPY composer.json composer.lock* ./

# Instalar dependencias de Composer si existen
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader --no-interaction || true; fi

# Copiar TODO desde el directorio actual (backend/)
COPY . .

# GARANTIZAR que database.php esté en api/ (siempre copiar desde config/ a api/)
RUN if [ -f /var/www/html/config/database.php ]; then \
        cp /var/www/html/config/database.php /var/www/html/api/database.php && \
        echo "✅ database.php copiado de config/ a api/"; \
    elif [ -f /var/www/html/api/database.php ]; then \
        echo "✅ database.php ya existe en api/"; \
    else \
        echo "⚠️ ADVERTENCIA: database.php no encontrado. Se usará configuración de variables de entorno."; \
    fi

# Verificar que database.php existe en api/
RUN ls -la /var/www/html/api/database.php 2>/dev/null && echo "✅ database.php verificado en api/" || echo "⚠️ database.php no está en api/ (usará variables de entorno)"

# Asegurar que Composer instale dependencias después de copiar todo
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader --no-interaction || true; fi

# Exponer puerto
EXPOSE 10000

# Comando de inicio - usar router.php para manejar todas las rutas
CMD php -S 0.0.0.0:${PORT:-10000} router.php
