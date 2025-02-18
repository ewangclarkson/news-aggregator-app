#!/bin/sh

# Change to the application directory
cd /var/www/html/

echo "Waiting for MySQL to be ready..."
while ! nc -z database 3306; do
  sleep 0.1 # wait for MySQL to start
done
echo "MySQL is ready!"

# Clear caches and run migrations
php artisan cache:clear
php artisan config:clear
php artisan route:clear
echo "Running migrations..."
php artisan migrate

echo "Running seeders..."
php artisan db:seed

# Start the Laravel scheduler in the background
php artisan schedule:run >> /dev/null 2>&1 &

php artisan fetch:articles


chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start Apache
apache2-foreground