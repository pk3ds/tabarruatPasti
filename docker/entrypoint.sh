#!/bin/sh
set -e

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

exec "$@"
