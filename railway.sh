#!/bin/bash

# Simple Railway startup script for Laravel

echo "Starting Laravel application..."

# Install dependencies (Railway handles this automatically)
# composer install --no-dev --optimize-autoloader

# Start the application
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}