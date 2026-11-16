#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force

if [ "$RUN_SEEDERS" = "true" ]; then
    php artisan db:seed --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
