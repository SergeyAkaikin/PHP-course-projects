FROM php:8.1-fpm-alpine

# Installing php extensions
RUN docker-php-ext-install sockets opcache

COPY configs/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
RUN echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN mkdir -p /var/www/api-music/storage \
    chown -R www-data:www-data /var/www/api-music/storage

# Installing composer
RUN apk add curl
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer 
