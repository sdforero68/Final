FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar TODOS los archivos del backend manteniendo la estructura
# Esto asegura que config/ esté al mismo nivel que api/
COPY api /var/www/html/api
COPY config /var/www/html/config
COPY sql /var/www/html/sql
COPY router.php /var/www/html/router.php

# Verificar estructura (para debug)
RUN echo "=== Estructura de directorios ===" && \
    ls -la /var/www/html/ && \
    echo "=== Contenido de config/ ===" && \
    ls -la /var/www/html/config/ || echo "config/ no existe" && \
    echo "=== Contenido de api/ ===" && \
    ls -la /var/www/html/api/ || echo "api/ no existe"

# Exponer puerto
EXPOSE 10000

# Comando de inicio - servir directamente desde api/
# Las URLs serán: /products/list.php, /products/categories.php (sin /api)
CMD php -S 0.0.0.0:${PORT:-10000} -t api
