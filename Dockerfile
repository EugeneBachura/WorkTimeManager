FROM php:8.2-fpm

# Installing system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm

# Installing PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installing Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setting the working directory
WORKDIR /var/www

# Copying application files
COPY . /var/www

# Installing application dependencies (PHP and Node.js)
RUN composer install
RUN npm install

# Generating the application key
RUN php artisan key:generate

# Change permissions for the storage and cache directories
# RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
# RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Exposing the port
EXPOSE 9000

# Command to run both php-fpm and npm run dev in parallel
CMD sh -c "php-fpm & npm run dev -- --host"
