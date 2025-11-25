FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar TODOS los archivos del backend
COPY . /var/www/html/

# Asegurar que database.php esté en api/ (ya debería estar, pero por si acaso)
RUN if [ -f /var/www/html/config/database.php ]; then cp /var/www/html/config/database.php /var/www/html/api/database.php; fi

# Verificar estructura
RUN echo "=== Verificando archivos ===" && \
    ls -la /var/www/html/api/database.php && \
    ls -la /var/www/html/config/database.php || echo "Algunos archivos no encontrados"

# Exponer puerto
EXPOSE 10000

# Comando de inicio - servir directamente desde api/
CMD php -S 0.0.0.0:${PORT:-10000} -t api
