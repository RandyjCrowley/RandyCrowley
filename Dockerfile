###########################
# Install JS Dependencies #
###########################
FROM node:18 as build
RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

COPY package*.json ./
COPY *.config.js ./
COPY resources ./resources
RUN mkdir -p /usr/src/app/public

RUN npm install -D
RUN npm run build

###########################
# Create Production Image #
###########################
FROM serversideup/php:8.3-fpm-apache

USER root

# Install required packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    vim \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR /var/www/html

# Install PHP extensions
RUN install-php-extensions imagick gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Apache configuration
RUN a2enmod rewrite \
    && a2ensite ssl-off.conf

# Copy JS dependencies from build stage
COPY --from=build /usr/src/app/node_modules ./node_modules

# Install composer dependencies
COPY composer.* ./
RUN composer install --no-autoloader --no-interaction --no-progress

# Copy application files
COPY --chown=www-data:www-data . .
COPY --from=build /usr/src/app/public/build ./public/build
RUN composer dump-autoload --optimize

RUN php artisan storage:link

EXPOSE 8080

USER www-data

ENTRYPOINT ["/init"]
