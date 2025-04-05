FROM php:8.4.5-fpm-bookworm

RUN apt-get update \
    && apt-get install -y curl wget git unzip libpq-dev libicu-dev libpng-dev libmcrypt-dev libzip-dev libjpeg-dev libfreetype6-dev\
    && docker-php-ext-install bcmath pdo_pgsql \
    && docker-php-ext-install pgsql \
    && docker-php-ext-install zip \
    && docker-php-ext-install gd \
    && docker-php-ext-enable pgsql

#Install xdebug
RUN pecl install xdebug-3.4.2 && docker-php-ext-enable xdebug

RUN wget https://getcomposer.org/installer -O - -q \
    | php -- --install-dir=/bin --filename=composer --quiet

USER 1000

WORKDIR /var/www/html