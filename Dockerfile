FROM serversideup/php:8.3-fpm-apache
#-v2.2.1
USER root
# RUN commands
USER 1001

RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - && \
    apt-get update -u 0 && \
    apt-get install -u 0 -y nodejs git php8.3-imagick ghostscript --no-install-recommends && \
    apt-get clean -u 0 && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Create app directory
# RUN mkdir -p /var/www/html
WORKDIR /var/www/html

# Install composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.* ./
RUN composer install --no-autoloader --no-interaction --no-progress --prefer-dist --no-dev -o
RUN sed -i 's/<policy domain="coder" rights="none" pattern="PDF" \/>/<policy domain="coder" rights="read|write" pattern="PDF" \/>/g' /etc/ImageMagick-6/policy.xml
RUN echo "extension=imagick.so" >> /etc/php/8.3/fpm/php.ini
RUN echo "extension=imagick.so" >> /etc/php/8.3/cli/php.ini

COPY . .
RUN composer dumpautoload -o

RUN chown -R webuser:webgroup storage/*

RUN php artisan storage:link

RUN npm install && npm run production

WORKDIR /var/www/html/public

WORKDIR /var/www/html

RUN php artisan vendor:publish --tag=log-viewer-assets --force

# Running the app
EXPOSE 80

ENTRYPOINT ["/init"]


