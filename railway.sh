#!/bin/bash

# Railway deployment script for Laravel Advanced Inventory

echo "Starting Railway deployment..."

# Install PHP dependencies
echo "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
echo "Installing Node.js dependencies..."
npm ci

echo "Building frontend assets..."
npm run build

# Generate application key if not set
echo "Generating application key..."
php artisan key:generate --force

# Clear and cache configuration
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear application cache
echo "Clearing application cache..."
php artisan cache:clear

# Set proper permissions
echo "Setting permissions..."
chmod -R 755 storage bootstrap/cache

echo "Deployment preparation complete!"
echo "Starting Laravel development server..."

# Start the web server
php artisan serve --host=0.0.0.0 --port=$PORT