# Base PHP image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libonig-dev libxml2-dev git

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Copy project files
COPY . /var/www
WORKDIR /var/www

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy example env and generate app key (optional if using Render Environment variables)
RUN cp .env.example .env
RUN php artisan key:generate

# Expose port 10000
EXPOSE 10000

# Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=10000
