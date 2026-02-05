#!/bin/bash
set -e

echo "Starting Thunder Booking..."

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "placeholder" ]; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force --no-interaction
fi

echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "Running migrations..."
php artisan migrate --force --no-interaction

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Creating storage link..."
php artisan storage:link || true

echo "Starting Apache..."
exec apache2-foreground
