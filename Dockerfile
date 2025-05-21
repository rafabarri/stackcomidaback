# Dockerfile para Laravel + PHP 8 + Composer

FROM php:8.1-fpm

# Instalar extensiones y herramientas necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd soap curl sockets

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos del proyecto al contenedor
COPY . .

# Instalar dependencias PHP con composer
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto para PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
