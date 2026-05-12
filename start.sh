#!/bin/bash

# Clear any cached configurations
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations (force for production)
echo "Running migrations..."
php artisan migrate --force

# Start Apache in the foreground
echo "Starting Apache..."
apache2-foreground
