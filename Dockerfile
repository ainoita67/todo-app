FROM php:8.2-apache

# Instalar extensión de PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Habilitar mod_rewrite para .htaccess (si lo usas)
RUN a2enmod rewrite

# Copiar la configuración personalizada de Apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Copiar el código al contenedor
COPY . /var/www/html/

# Cambiar permisos si fuera necesario
RUN chown -R www-data:www-data /var/www/html
