FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar TODOS los archivos manteniendo estructura
COPY api/ /var/www/html/api/
COPY config/ /var/www/html/config/

# FORZAR copia de database.php a api/ para que esté ahí seguro
COPY config/database.php /var/www/html/api/database.php

# Exponer puerto
EXPOSE 10000

# Comando de inicio - servir directamente desde api/
CMD php -S 0.0.0.0:${PORT:-10000} -t api
