FROM php:8-fpm

RUN docker-php-ext-install pdo pdo_mysql pcntl sockets

COPY php.ini $PHP_INI_DIR

WORKDIR /var/www

CMD ["php-fpm"]