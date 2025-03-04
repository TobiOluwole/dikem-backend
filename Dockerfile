# Base image
FROM php:8.4-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip && \
    docker-php-ext-install pdo_mysql zip && \
    a2enmod rewrite

# Set the Apache document root to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application code
COPY . .

# Install Laravel dependencies
RUN composer install 
#--no-dev --optimize-autoloader


# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]