FROM serversideup/php:8.3-fpm-apache

# Create app directory
# RUN mkdir -p /var/www/html
WORKDIR /var/www/html

# Install composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.* ./

RUN composer install --no-autoloader  --no-progress --prefer-dist --no-dev -o

COPY . .

# Running the app
EXPOSE 80

ENTRYPOINT ["/init"]

