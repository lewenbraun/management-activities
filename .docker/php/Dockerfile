FROM php:8.4-fpm-alpine

WORKDIR /var/www

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
RUN apk add --no-cache nodejs npm

COPY . .

EXPOSE 9000

COPY .docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]