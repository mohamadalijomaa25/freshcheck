FROM php:8.2-apache

# Enable PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Apache config: allow .htaccess (not required, but safe)
RUN a2enmod rewrite

# Copy backend folder into Apache web root
COPY . /var/www/html/

# Expose default Apache port
EXPOSE 80
