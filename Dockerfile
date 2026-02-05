# Use PHP 8.3 with Apache
FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
RUN chown -R www-data:www-data /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Remove node_modules to save space
RUN rm -rf node_modules

# Set permissions
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Update Apache configuration
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN echo '<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Expose port (Render will set this)
EXPOSE 80

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
echo "Starting Thunder Booking..."\n\
\n\
# Generate APP_KEY if not set\n\
if [ -z "$APP_KEY" ]; then\n\
  echo "Generating APP_KEY..."\n\
  php artisan key:generate --force\n\
fi\n\
\n\
# Clear and cache config\n\
php artisan config:clear\n\
php artisan config:cache\n\
\n\
# Run migrations\n\
echo "Running migrations..."\n\
php artisan migrate --force\n\
\n\
# Cache routes and views\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Create storage link\n\
php artisan storage:link || true\n\
\n\
echo "Starting Apache..."\n\
apache2-foreground' > /start.sh && chmod +x /start.sh

# Start Apache
CMD ["/start.sh"]
