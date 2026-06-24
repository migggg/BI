#!/usr/bin/env bash

php-fpm -D
sleep 2

php artisan config:cache
php artisan route:cache
php artisan storage:link
php artisan migrate --force

nginx -g 'daemon off;'