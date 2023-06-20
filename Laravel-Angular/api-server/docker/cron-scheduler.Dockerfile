FROM php:8.2-cli

RUN docker-php-ext-install pdo_mysql sockets

RUN apt-get update && apt-get -y install cron

RUN crontab -l | { cat; echo "* * * * * php /var/www/api-music/artisan schedule:run 2>&1"; } | crontab -

CMD cron -f
