FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar TODA la estructura del backend
COPY . /var/www/html/

# Asegurar que database.php esté en api/ (copiar desde config/)
RUN cp /var/www/html/config/database.php /var/www/html/api/database.php 2>/dev/null || true

# Verificar que los archivos estén presentes
RUN echo "=== Verificando estructura ===" && \
    ls -la /var/www/html/ && \
    echo "=== Verificando api/ ===" && \
    ls -la /var/www/html/api/ | head -10 && \
    echo "=== Verificando database.php ===" && \
    ls -la /var/www/html/api/database.php || echo "ERROR: database.php no encontrado"

# Exponer puerto
EXPOSE 10000

# Comando de inicio - servir directamente desde api/
CMD php -S 0.0.0.0:${PORT:-10000} -t api
