FROM php:8.2-apache

# Instalar extensiones necesarias y herramientas
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd soap curl sockets

# Habilita# Habilitar mod_rewrite y headers de Apache y cambiar DocumentRoot a /public
RUN a2enmod rewrite headers && \
    sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar proyecto al contenedor
COPY . .

# Copiar .env.example a .env si no existe
RUN [ -f .env ] || cp .env.example .env

# Instalar dependencias PHP (sin scripts que ejecuten migraciones)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Configurar permisos de Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto HTTP estándar
EXPOSE 80
