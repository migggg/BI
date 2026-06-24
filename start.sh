#!/usr/bin/env bash

php artisan config:cache
php artisan route:cache
php artisan storage:link
php artisan migrate --force

php-fpm -D
nginx -g 'daemon off;'