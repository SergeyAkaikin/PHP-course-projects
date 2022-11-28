FROM php:8.1-fpm-alpine

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \ 
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del pcre-dev ${PHPIZE_DEPS}

RUN printf "zend_extension=xdebug.so\n\
xdebug.discover_client_host = false\n\
xdebug.max_nesting_level = 512\n\
xdebug.start_with_request = yes \n\
xdebug.idekey = PHPSTORM_API\n\
xdebug.mode = debug\n\
xdebug.client_host = host.docker.internal\n\
xdebug.client_port = 9003\n" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini