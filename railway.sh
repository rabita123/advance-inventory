#!/bin/bash

# Railway deployment script for Laravel

# Install dependencies
composer install --no-dev --optimize-autoloader

# Generate application key if not set
php artisan key:generate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Start the web server
php artisan serve --host=0.0.0.0 --port=$PORT