#!/bin/sh

# Install Composer dependencies if the vendor directory is missing or composer files have changed.
if [ ! -d vendor ] || [ composer.json -nt vendor ] || [ composer.lock -nt vendor ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Install Node dependencies and build assets if node_modules is missing or package files have changed.
if [ ! -d node_modules ] || [ package.json -nt node_modules ] || [ package-lock.json -nt node_modules ]; then
  npm install
  npm run build
fi

# Create and set permissions for Laravel's cache and storage directories.
mkdir -p /var/www/bootstrap/cache \
         /var/www/storage/framework/cache \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/logs

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R ug+rwx /var/www/storage /var/www/bootstrap/cache

exec "$@"