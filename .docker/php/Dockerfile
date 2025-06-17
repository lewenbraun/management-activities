FROM php:8.4-fpm-alpine

RUN apk --no-cache add \
    curl \
    g++ \
    icu-dev \
    libzip-dev \
    mysql-client \
    mysql-dev \
    git \
    libjpeg-turbo-dev \
    ffmpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    opcache \
    intl \
    zip \
    exif

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.* ./
RUN composer install --no-dev --no-scripts --no-autoloader

COPY . .
RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R ug+rwx /var/www/storage /var/www/bootstrap/cache

RUN apk add --no-cache nodejs npm

RUN npm install

RUN rm -rf public/build/* public/manifest.json

RUN npm run build

EXPOSE 9000
CMD ["php-fpm"]
