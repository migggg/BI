FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader

COPY conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

RUN echo '[www]\nlisten = /var/run/php/php-fpm.sock\nlisten.owner = www-data\nlisten.group = www-data\nlisten.mode = 0660\nuser = www-data\ngroup = www-data\npm = dynamic\npm.max_children = 5\npm.start_servers = 2\npm.min_spare_servers = 1\npm.max_spare_servers = 3' > /usr/local/etc/php-fpm.d/www.conf

EXPOSE 80

COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]