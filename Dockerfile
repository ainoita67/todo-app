FROM php:8.2-apache

# Actualizar repositorios e instalar dependencias necesarias para pdo_pgsql
RUN apt-get update && apt-get install -y \
    libpq-dev \
    gcc \
    make \
    autoconf \
    && docker-php-ext-install pdo pdo_pgsql \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiar la configuración personalizada de Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copiar el código al contenedor
COPY . /var/www/html/

# Cambiar permisos si fuera necesario
RUN chown -R www-data:www-data /var/www/html
