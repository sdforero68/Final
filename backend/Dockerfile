FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del backend
COPY . .

# Hacer el script de inicio ejecutable (si existe)
RUN chmod +x start.sh 2>/dev/null || true

# Exponer puerto
EXPOSE 10000

# Comando de inicio - usa el puerto de Render
CMD php -S 0.0.0.0:${PORT:-10000} -t api

