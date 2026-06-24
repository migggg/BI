#!/usr/bin/env bash
# Create socket directory
mkdir -p /var/run/php

# Fix storage permissions at runtime
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start PHP-FPM
/usr/local/sbin/php-fpm -D

# Wait for socket instead of blind sleep
timeout 10 bash -c 'until [ -S /var/run/php/php-fpm.sock ]; do sleep 0.5; done'
echo "PHP-FPM socket ready"

# Laravel bootstrap
php artisan config:cache
php artisan route:cache
php artisan storage:link

# Migrate with retry in case DB is slow
for i in {1..5}; do
    php artisan migrate --force && break
    echo "Migration attempt $i failed, retrying in 5s..."
    sleep 5
done

# Start nginx
exec nginx -g 'daemon off;'