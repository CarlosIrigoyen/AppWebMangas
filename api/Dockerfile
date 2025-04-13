# Utilizamos una imagen oficial de PHP con Apache
FROM php:8.3-apache

# Habilitamos mod_rewrite
RUN a2enmod rewrite

# Instalamos las dependencias del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Copiamos los archivos del proyecto Laravel
COPY api/ /var/www/html

# Instalamos las dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Exponemos el puerto 80
EXPOSE 80
# Establecer permisos y propietarios para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Comando para iniciar Apache en el contenedor
CMD ["apache2-foreground"]
