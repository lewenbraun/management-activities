FROM php:8.4-fpm-alpine

RUN apk --no-cache add \
    curl \
    g++ \
    icu-dev \
    libzip-dev \
    postgresql-dev \
    git \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    opcache \
    intl \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.* ./
RUN composer install --no-dev --no-scripts --no-autoloader

COPY . .
RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R ug+rwx /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
