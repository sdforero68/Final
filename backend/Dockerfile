FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar TODOS los archivos del backend manteniendo la estructura
COPY api /var/www/html/api
COPY config /var/www/html/config

# Asegurar que database.php esté en api/ también
RUN cp /var/www/html/config/database.php /var/www/html/api/database.php || true

# Exponer puerto
EXPOSE 10000

# Comando de inicio - servir directamente desde api/
CMD php -S 0.0.0.0:${PORT:-10000} -t api
