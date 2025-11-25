FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del backend
COPY . .

# Exponer puerto
EXPOSE 10000

# Comando de inicio - servir directamente desde api/
# Las URLs serán: /products/list.php, /products/categories.php (sin /api)
CMD php -S 0.0.0.0:${PORT:-10000} -t api
