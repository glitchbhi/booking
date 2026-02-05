#!/bin/bash

# Thunder Booking System - Quick Setup Script

echo "=============================================="
echo "  Thunder Booking System - Installation"
echo "=============================================="
echo ""

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install composer first."
    exit 1
fi

echo "✓ Composer found"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.2 or higher."
    exit 1
fi

echo "✓ PHP found ($(php -v | head -n 1))"

# Install composer dependencies
echo ""
echo "📦 Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo ""
    echo "📝 Creating .env file..."
    cp .env.example .env
    
    echo ""
    echo "🔑 Generating application key..."
    php artisan key:generate
else
    echo ""
    echo "✓ .env file already exists"
fi

# Install Laravel Breeze for authentication
echo ""
echo "🔐 Installing Laravel Breeze (Authentication)..."
composer require laravel/breeze --dev --no-interaction
php artisan breeze:install blade --no-interaction

# Install NPM dependencies
if command -v npm &> /dev/null; then
    echo ""
    echo "📦 Installing NPM dependencies..."
    npm install
    npm run build
else
    echo ""
    echo "⚠️  NPM not found. Skipping frontend build."
    echo "   Install Node.js and run: npm install && npm run build"
fi

echo ""
echo "=============================================="
echo "  Configuration Required"
echo "=============================================="
echo ""
echo "Please update your .env file with:"
echo ""
echo "1. Database Configuration:"
echo "   DB_DATABASE=thunder_booking"
echo "   DB_USERNAME=your_username"
echo "   DB_PASSWORD=your_password"
echo ""
echo "2. Mail Configuration (for notifications):"
echo "   MAIL_MAILER=smtp"
echo "   MAIL_HOST=smtp.gmail.com"
echo "   MAIL_PORT=587"
echo "   MAIL_USERNAME=your_email@gmail.com"
echo "   MAIL_PASSWORD=your_app_password"
echo ""
echo "=============================================="
echo ""

read -p "Have you configured the database in .env? (y/n) " -n 1 -r
echo ""

if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo ""
    echo "🗄️  Running migrations and seeding database..."
    php artisan migrate:fresh --seed
    
    echo ""
    echo "🔗 Creating storage link..."
    php artisan storage:link
    
    echo ""
    echo "✅ Installation complete!"
    echo ""
    echo "=============================================="
    echo "  Thunder Booking System - Ready!"
    echo "=============================================="
    echo ""
    echo "Default Credentials:"
    echo ""
    echo "Admin:"
    echo "  Email: admin@thunderbooking.com"
    echo "  Password: password"
    echo ""
    echo "To start the server:"
    echo "  php artisan serve"
    echo ""
    echo "To start the scheduler (in another terminal):"
    echo "  php artisan schedule:work"
    echo ""
    echo "Then visit: http://localhost:8000"
    echo "=============================================="
else
    echo ""
    echo "Please configure your .env file and run:"
    echo "  php artisan migrate:fresh --seed"
    echo "  php artisan storage:link"
    echo ""
fi
