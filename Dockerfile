# Multi-stage build untuk mengoptimalkan ukuran image final
# Stage 1: Build Node.js assets
FROM node:20-alpine AS node-builder

WORKDIR /app
COPY package.json ./
RUN npm install --no-audit --no-fund

COPY . .
RUN npm run build

# Stage 2: PHP production image
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    gettext \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath mbstring xml opcache gd

# Konfigurasi OPcache untuk production (key untuk performa)
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy PHP dependencies manifest dulu (layer caching)
COPY composer.json composer.lock ./
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --no-scripts --no-autoloader --no-interaction --ignore-platform-reqs

# Copy semua file aplikasi
COPY . .

# Copy built assets dari stage 1
COPY --from=node-builder /app/public/build ./public/build

# Optimize autoloader
RUN COMPOSER_MEMORY_LIMIT=-1 composer dump-autoload --optimize --no-interaction --ignore-platform-reqs

# Buat direktori storage yang diperlukan + set permissions
RUN mkdir -p storage/framework/sessions \
              storage/framework/views \
              storage/framework/cache/data \
              storage/logs \
              bootstrap/cache \
    && chown -R www-data:www-data /app \
    && chmod -R 775 storage bootstrap/cache

# Copy konfigurasi server
COPY docker/nginx.conf.template /etc/nginx/nginx.conf.template
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]
