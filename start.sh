#!/usr/bin/env bash

mkdir -p /var/run/php

php-fpm -D
sleep 3

php artisan config:cache
php artisan route:cache
php artisan storage:link
php artisan migrate --force

nginx -g 'daemon off;'