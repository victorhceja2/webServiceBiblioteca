# Usar la imagen oficial de PHP 8 con Apache
FROM php:8.0-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar el código de la aplicación al contenedor
COPY src/ /var/www/html/

# Exponer el puerto 80
EXPOSE 8002