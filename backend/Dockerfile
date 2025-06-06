# Stage 1: PHP & Laravel Setup
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
  libpq-dev git unzip curl libicu-dev zlib1g-dev libzip-dev netcat-openbsd \
  && docker-php-ext-install pdo_pgsql intl zip pcntl \
  && pecl install redis \
  && docker-php-ext-enable redis pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

# Copy .env
COPY .env /var/www/html/.env

# Copy application files and set ownership
COPY . /var/www/html
RUN git config --global --add safe.directory /var/www/html \
  && chown -R www-data:www-data /var/www/html

# Install Composer dependencies as www-data
USER www-data
RUN composer install --no-dev --optimize-autoloader

# Final setup as root
USER root
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh \
  && chmod -R 755 /var/www/html/storage \
  && php artisan config:cache \
  && php artisan route:cache

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
