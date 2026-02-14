#!/bin/bash

# Laravel Deployment Script
# Run this on your server after uploading files

echo "========================================="
echo "Laravel Deployment Script"
echo "========================================="
echo ""

# Check PHP version
echo "Checking PHP version..."
PHP_VERSION=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
echo "PHP Version: $PHP_VERSION"

if (( $(echo "$PHP_VERSION < 8.1" | bc -l) )); then
    echo "❌ ERROR: PHP version must be 8.1 or higher!"
    echo "Current version: $PHP_VERSION"
    echo "Please upgrade PHP on your server."
    exit 1
fi

echo "✓ PHP version is compatible"
echo ""

# Install Composer dependencies
echo "Installing Composer dependencies..."
if [ -f "composer.lock" ]; then
    composer install --no-dev --optimize-autoloader --no-interaction
else
    composer update --no-dev --optimize-autoloader --no-interaction
fi

if [ $? -ne 0 ]; then
    echo "❌ Composer install failed!"
    echo "Trying with --ignore-platform-reqs..."
    composer install --ignore-platform-reqs --no-dev --optimize-autoloader --no-interaction
fi

echo "✓ Dependencies installed"
echo ""

# Set permissions
echo "Setting file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
echo "✓ Permissions set"
echo ""

# Clear all caches
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "✓ Caches cleared"
echo ""

# Optimize for production
echo "Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✓ Optimization complete"
echo ""

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
    echo "✓ App key generated"
else
    echo "✓ App key already set"
fi
echo ""

# Run migrations (commented out for safety)
# echo "Running migrations..."
# php artisan migrate --force
# echo "✓ Migrations complete"
# echo ""

echo "========================================="
echo "✅ Deployment Complete!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Update .env file with production settings"
echo "2. Set APP_DEBUG=false in .env"
echo "3. Run migrations if needed: php artisan migrate --force"
echo "4. Test your application"
echo ""
