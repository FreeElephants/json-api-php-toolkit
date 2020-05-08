FROM php:7.4-cli

RUN apt-get update \
    && apt-get install -y \
    libzip-dev \
    unzip \
    libicu-dev

RUN docker-php-ext-install \
    zip \
    intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /app
