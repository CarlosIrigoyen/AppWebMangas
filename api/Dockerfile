# Utilizamos una imagen oficial de PHP con Apache
FROM php:8.3-apache

# Habilitamos mod_rewrite
RUN a2enmod rewrite

# Establecemos el directorio de trabajo
WORKDIR /var/www/html

# Instalamos las dependencias del sistema
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev git unzip

# Instalamos las extensiones de PHP necesarias
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiamos los archivos del proyecto desde la carpeta 'api'
COPY api/ /var/www/html

# Establecemos el directorio de trabajo en 'public'
WORKDIR /var/www/html/public

# Instalamos las dependencias de PHP
RUN composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Exponemos el puerto 80
EXPOSE 80

# Comando para iniciar Apache en el contenedor
CMD ["apache2-foreground"]
