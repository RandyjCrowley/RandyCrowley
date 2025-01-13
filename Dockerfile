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

# Install composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install necessary PHP extensions
RUN install-php-extensions imagick gd

# Add and configure www-data user and group
RUN groupadd -g 33 www-data \
    && useradd -u 33 -g 33 -d /var/www -s /usr/sbin/nologin www-data \
    && chown -R www-data:www-data /var/www/html

# Copy composer files and install dependencies
COPY composer.* ./
RUN composer install --no-autoloader --no-interaction --no-progress --prefer-dist --no-dev -o

# Copy application code and generate autoload files
COPY . .
RUN composer dumpautoload -o

# Install and build frontend assets
RUN npm install && npm run build

# Publish assets
RUN php artisan vendor:publish --tag=log-viewer-assets --force

# Expose port 80
EXPOSE 80

# Switch to www-data user for running the application
USER www-data

# Define entrypoint
ENTRYPOINT ["/init"]
