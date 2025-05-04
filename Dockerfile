FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    git \
    libicu-dev \
    libpq-dev \
    librabbitmq-dev \
    libssl-dev \
    libzip-dev \
    supervisor\
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install intl pdo pdo_mysql zip \
    && pecl install amqp && \
           docker-php-ext-enable amqp

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./supervisor/scheduler.conf /etc/supervisor/conf.d/scheduler.conf

WORKDIR /var/www/html
COPY . .

RUN composer install --no-scripts --no-interaction

CMD ["php-fpm"]

RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor
