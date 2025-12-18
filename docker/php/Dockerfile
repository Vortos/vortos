FROM php:8.4-fpm-alpine

WORKDIR /var/www/html 

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache git unzip linux-headers $PHPIZE_DEPS postgresql-dev

RUN pecl install redis mongodb && docker-php-ext-enable redis mongodb

RUN docker-php-ext-install pdo pdo_pgsql

