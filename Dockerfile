FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    git \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install intl pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor
