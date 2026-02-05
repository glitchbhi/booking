#!/bin/bash
set -e

echo "Starting Thunder Booking..."

# Ensure .env file exists and is writable
if [ ! -f /var/www/html/.env ]; then
  echo "Creating .env file..."
  cp /var/www/html/.env.example /var/www/html/.env
fi

# Ensure storage directories exist with proper permissions
echo "Setting up storage directories..."
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "placeholder" ]; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force --no-interaction || echo "Key generation skipped"
fi

echo "Clearing caches..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true

echo "Running migrations..."
php artisan migrate --force --no-interaction || echo "Migration warning: Check database connection"

echo "Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Creating storage link..."
php artisan storage:link || true

echo "Starting Apache..."
exec apache2-foreground
