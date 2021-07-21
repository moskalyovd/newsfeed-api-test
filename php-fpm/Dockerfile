FROM php:8.0-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    build-essential \
    libssl-dev \
    zip \
    vim \
    unzip \
    libxml2-dev \
    libzip-dev \
    libpq-dev

RUN docker-php-ext-install zip pdo_mysql intl sockets pcntl

RUN apt-get install -y librabbitmq-dev libssl-dev
RUN pecl install amqp-1.11.0beta
RUN docker-php-ext-enable amqp

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 9000

CMD ["php-fpm"]

