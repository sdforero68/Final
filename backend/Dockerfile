FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del backend
COPY . .

# Exponer puerto
EXPOSE 10000

# Comando de inicio - servir desde la raíz y usar api como punto de entrada
CMD php -S 0.0.0.0:${PORT:-10000} -t . router.php
