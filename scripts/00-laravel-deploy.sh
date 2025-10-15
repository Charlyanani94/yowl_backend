#!/usr/bin/env bash

echo "Starting YOWL Backend Deployment..."

echo "Setting up environment..."
cp .env.production .env

# Install dependencies
echo "Installing production dependencies..."
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Create necessary directories
echo "Creating required directories..."
mkdir -p storage/api-docs
mkdir -p storage/logs
mkdir -p storage/framework/{cache,sessions,views}
chmod -R 775 storage bootstrap/cache

# Clear all caches first
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Caching for performance..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running database operations..."
php artisan migrate --force

# Only run seeders if DATABASE_SEED is set to true
if [ "$DATABASE_SEED" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed
fi

echo "Setting up API documentation..."
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider" --tag="l5-swagger-assets" --force
php artisan l5-swagger:generate

# Final optimizations
echo "Final optimizations..."
php artisan storage:link

# Verify critical files
echo "Verifying deployment..."
[ -f storage/api-docs/api-docs.json ] && echo "✓ Swagger docs generated" || echo "⚠️ Swagger docs not found"
[ -d bootstrap/cache ] && echo "✓ Bootstrap cache ready" || echo "⚠️ Bootstrap cache missing"

echo "YOWL Backend deployment completed successfully!"