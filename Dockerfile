FROM php:8.2-apache

# Instalar extensiones necesarias y herramientas
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd soap curl sockets

# Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar proyecto al contenedor
COPY . .

# Copiar .env.example a .env si no existe
RUN cp .env.example .env

# Instalar dependencias PHP (sin scripts que ejecuten migraciones)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Configurar permisos de Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto HTTP estándar
EXPOSE 80

# El contenedor ya arranca Apache por defecto
