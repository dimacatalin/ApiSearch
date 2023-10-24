FROM php:8.2-cli

RUN apt-get update \
  && apt install -y libpq-dev libzip-dev zip

RUN pecl install -f xdebug \
    && docker-php-ext-install zip \
    && docker-php-ext-enable xdebug
