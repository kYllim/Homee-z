FROM php:8.3-fpm-alpine AS builder

RUN apk add --no-cache \
    git \
    unzip \
    libpq-dev \
    postgresql-dev \
    icu-dev \
    zip \
    libzip-dev

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    opcache \
    intl \
    zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-progress


COPY . .
RUN composer dump-autoload --optimize --no-dev


FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    libpq \
    icu-libs \
    libzip

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    opcache \
    intl \
    zip

RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "realpath_cache_size=4096K" >> /usr/local/etc/php/conf.d/opcache.ini && \
    echo "realpath_cache_ttl=600" >> /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html


COPY --from=builder --chown=www-data:www-data /var/www/html /var/www/html


RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data var/ && \
    chmod -R 775 var/

EXPOSE 9000

CMD ["php-fpm"]
