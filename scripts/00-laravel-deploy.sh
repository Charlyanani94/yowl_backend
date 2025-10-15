#!/usr/bin/env bash
set -e
set -o pipefail

echo "Starting YOWL Backend Deployment..."

# Installer les dépendances composer
echo "Installing production dependencies..."
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Créer les dossiers nécessaires avec permissions correctes
echo "Creating required directories..."
mkdir -p storage/framework/{cache,sessions,views} storage/logs storage/api-docs public/vendor
chmod -R 775 storage bootstrap/cache public/vendor

# Nettoyer tous les caches avant de recacher
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recacher pour la performance
echo "Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Lancer les migrations
echo "Running database migrations..."
php artisan migrate --force

# Lancer les seeders si nécessaire
if [ "$DATABASE_SEED" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed
fi

# Swagger (L5-Swagger)
echo "Setting up API documentation..."
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider" --tag="l5-swagger-assets" --force
php artisan l5-swagger:generate

# Créer le lien symbolique vers storage
echo "Creating storage symlink..."
php artisan storage:link

# Vérifier que Swagger fonctionne
if [ -f storage/api-docs/api-docs.json ]; then
    echo "✓ Swagger docs generated"
else
    echo "⚠️ Swagger docs not found"
fi

# Vérifier bootstrap cache
[ -d bootstrap/cache ] && echo "✓ Bootstrap cache ready" || echo "⚠️ Bootstrap cache missing"

echo "Deployment completed! Your API documentation should be available at /api/documentation"
