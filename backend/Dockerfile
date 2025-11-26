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

# Corregir permisos de archivos y directorios
# Asegurar que PHP pueda leer todos los archivos
RUN chmod -R 755 /var/www/html && \
    chmod -R 644 /var/www/html/**/*.php 2>/dev/null || true && \
    find /var/www/html -type d -exec chmod 755 {} \; && \
    find /var/www/html -type f -exec chmod 644 {} \; && \
    echo "✅ Permisos configurados correctamente"

# GARANTIZAR que database.php esté en api/ (siempre copiar desde config/ a api/)
RUN if [ -f /var/www/html/config/database.php ]; then \
        cp /var/www/html/config/database.php /var/www/html/api/database.php && \
        chmod 644 /var/www/html/api/database.php && \
        echo "✅ database.php copiado de config/ a api/ con permisos correctos"; \
    elif [ -f /var/www/html/api/database.php ]; then \
        chmod 644 /var/www/html/api/database.php && \
        echo "✅ database.php ya existe en api/"; \
    else \
        echo "⚠️ ADVERTENCIA: database.php no encontrado. Se usará configuración de variables de entorno."; \
    fi

# Verificar que database.php existe en api/ y tiene permisos correctos
RUN if [ -f /var/www/html/api/database.php ]; then \
        ls -la /var/www/html/api/database.php && \
        echo "✅ database.php verificado en api/ con permisos correctos"; \
    else \
        echo "⚠️ database.php no está en api/ (usará variables de entorno)"; \
    fi

# Verificar permisos de directorios críticos
RUN ls -ld /var/www/html /var/www/html/api /var/www/html/config 2>/dev/null && \
    echo "✅ Permisos de directorios verificados"

# Asegurar que Composer instale dependencias después de copiar todo
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader --no-interaction || true; fi

# Exponer puerto
EXPOSE 10000

# Comando de inicio - usar router.php para manejar todas las rutas
CMD php -S 0.0.0.0:${PORT:-10000} router.php
