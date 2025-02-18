#!/bin/sh

# Change to the application directory
cd /var/www/html/


# Clear caches and run migrations
php artisan cache:clear
php artisan config:clear
php artisan route:clear
echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

# Start the Laravel scheduler in the background
php artisan schedule:run >> /dev/null 2>&1 &

php artisan fetch:articles


chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start Apache
apache2-foreground