FROM serversideup/php:8.3-fpm-apache

# Switch to root for system installations
USER root


# Install Node.js and other dependencies
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && \
    apt-get update && \
    apt-get install -y nodejs git ghostscript vim --no-install-recommends && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /var/www/html

USER webuser
# Install composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN install-php-extensions imagick gd

COPY composer.* ./
RUN composer install --no-autoloader --no-interaction --no-progress --prefer-dist --no-dev -o

COPY . .
RUN composer dumpautoload -o

# Install and build frontend assets
RUN npm install && npm run build

# Publish assets
RUN php artisan vendor:publish --tag=log-viewer-assets --force

EXPOSE 80

# Switch back to non-root user if needed


ENTRYPOINT ["/init"]
