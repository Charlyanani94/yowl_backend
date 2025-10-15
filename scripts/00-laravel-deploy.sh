#!/usr/bin/env bash

set -e
set -o pipefail

echo "Starting YOWL Backend Deployment..."

cp .env.production .env

composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

mkdir -p storage/api-docs
mkdir -p storage/logs
mkdir -p storage/framework/{cache,sessions,views}
chmod -R 775 storage bootstrap/cache public/vendor

php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force

if [ "$DATABASE_SEED" = "true" ]; then
    php artisan db:seed
fi

php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider" --tag="l5-swagger-assets" --force
php artisan l5-swagger:generate

php artisan storage:link

chmod -R 775 storage bootstrap/cache public/vendor

[ -f storage/api-docs/api-docs.json ] && echo "Swagger docs generated" || echo "Swagger docs not found"
[ -d bootstrap/cache ] && echo "Bootstrap cache ready" || echo "Bootstrap cache missing"
[ -d public/vendor/l5-swagger ] && echo "Swagger vendor assets ready" || echo "Swagger vendor assets missing"

echo "YOWL Backend deployment completed successfully!"
