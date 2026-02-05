#!/bin/bash
set -e

echo "Starting Thunder Booking..."

# Ensure .env file exists
if [ ! -f /var/www/html/.env ]; then
  echo "Creating .env file from example..."
  cp /var/www/html/.env.example /var/www/html/.env
fi

# Ensure storage directories exist with proper permissions
echo "Setting up storage directories..."
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/bootstrap/cache

# Set ownership to www-data BEFORE setting permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Generate APP_KEY if not set (run as www-data)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "placeholder" ]; then
  echo "Generating APP_KEY..."
  su -s /bin/bash www-data -c "php artisan key:generate --force --no-interaction" || echo "Key generation skipped"
fi

echo "Clearing caches..."
su -s /bin/bash www-data -c "php artisan config:clear" || true
su -s /bin/bash www-data -c "php artisan cache:clear" || true
su -s /bin/bash www-data -c "php artisan view:clear" || true
su -s /bin/bash www-data -c "php artisan route:clear" || true

echo "Running migrations..."
su -s /bin/bash www-data -c "php artisan migrate --force --no-interaction" || echo "Migration warning: Check database connection"

echo "Caching configuration..."
su -s /bin/bash www-data -c "php artisan config:cache" || true
su -s /bin/bash www-data -c "php artisan route:cache" || true
su -s /bin/bash www-data -c "php artisan view:cache" || true

echo "Creating storage link..."
su -s /bin/bash www-data -c "php artisan storage:link" || true

echo "Starting Apache..."
exec apache2-foreground
