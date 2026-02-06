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

# Debug: Check database environment variables
echo "=== DATABASE CONFIGURATION DEBUG ==="
echo "DB_CONNECTION: ${DB_CONNECTION:-not set}"
if [ -n "$DATABASE_URL" ]; then
  echo "DATABASE_URL: SET (${DATABASE_URL:0:20}...)"
else
  echo "DATABASE_URL: NOT SET"
fi
echo "===================================="

# Debug: Check if Google OAuth vars are set
echo "Checking Google OAuth environment variables..."
if [ -z "$GOOGLE_CLIENT_ID" ]; then
  echo "WARNING: GOOGLE_CLIENT_ID is not set!"
else
  echo "GOOGLE_CLIENT_ID is set"
fi

echo "Testing database connection..."
su -s /bin/bash www-data -c "php artisan db:show" || echo "Database connection test failed"

echo "Running migrations..."
su -s /bin/bash www-data -c "php artisan migrate --force --no-interaction" || echo "Migration warning: Check database connection"

echo "Seeding database..."
su -s /bin/bash www-data -c "php artisan db:seed --force --no-interaction" || echo "Seeding skipped"

echo "Fixing approved owners..."
su -s /bin/bash www-data -c "php artisan fix:approved-owners --no-interaction" || echo "Fix skipped"

# DO NOT cache config in production - it prevents environment variables from being read
echo "Configuration will use environment variables directly..."

echo "Creating storage link..."
su -s /bin/bash www-data -c "php artisan storage:link" || true

echo "Starting Apache..."
exec apache2-foreground
