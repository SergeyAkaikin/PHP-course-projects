FROM php:8.1-fpm-alpine

# Installing php extensions
RUN docker-php-ext-install sockets opcache
RUN docker-php-ext-install pdo pdo_mysql

COPY php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN mkdir -p /var/www/api-music/storage

RUN mkdir -p /var/www/api-music/storage/framework \
    && mkdir -p /var/www/api-music/storage/framework/sessions \
    && mkdir -p /var/www/api-music/storage/framework/views \
    && mkdir -p /var/www/api-music/storage/framework/cache \
    && mkdir -p /var/www/api-server/storage/logs


RUN chown -R www-data:www-data /var/www/api-music/storage

