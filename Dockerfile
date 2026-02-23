# Stage 1: Composer dependencies only
FROM composer:latest AS composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts


# Stage 2: Final image (PHP + Node for build, then Node removed)
FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Install system dependencies + Node (needed for vite build)
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip gd opcache

# Copy app files
COPY . .

# Copy vendor from composer stage
COPY --from=composer /app/vendor ./vendor

# Run post-install scripts now that vendor exists
RUN php artisan package:discover --ansi

# Build frontend (php is available here, so wayfinder works)
RUN npm ci && npm run build && rm -rf node_modules

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy config files
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
