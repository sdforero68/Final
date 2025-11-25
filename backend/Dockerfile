FROM php:8.1-cli

# Instalar extensiones PHP necesarias para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del backend
COPY . .

# Hacer el script de inicio ejecutable
RUN chmod +x /var/www/html/start.sh

# Exponer puerto (Render asignará el puerto automáticamente)
EXPOSE 10000

# Usar script de inicio
CMD ["/var/www/html/start.sh"]
