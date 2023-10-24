FROM php:8.2-fpm

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install packages
RUN apt-get update \
    && apt-get install -y libpq-dev libzip-dev zip software-properties-common npm \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Install xdebug
RUN pecl install -f xdebug \
    && docker-php-ext-install zip \
    && docker-php-ext-enable xdebug
