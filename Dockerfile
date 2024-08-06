FROM php:8.3.10-cli

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt update && apt -y install git zip unzip
RUN pecl install xdebug && docker-php-ext-enable xdebug

ADD .docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN adduser -u 1001 app
USER app

WORKDIR /app

CMD ["bash"]
