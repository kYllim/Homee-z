FROM php:8.3-cli

# Installer les extensions nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Installer Composer globalement
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/back
