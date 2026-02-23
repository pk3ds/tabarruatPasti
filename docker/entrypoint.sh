#!/bin/sh
set -e

# Ensure storage directories exist and have correct permissions
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

exec "$@"
