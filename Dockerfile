FROM php:8.2-fpm

# Instalar extensiones y herramientas necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd soap curl sockets

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto al contenedor (en /var/www/html)
COPY . .

# Copiar .env.example a .env para que exista el archivo antes de composer install
RUN cp .env.example .env

# Instalar dependencias PHP con composer
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto para PHP-FPM
EXPOSE 9000

# Comando para correr PHP-FPM
CMD ["php-fpm"]
