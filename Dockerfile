FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build


FROM php:8.4-fpm-alpine AS backend

WORKDIR /var/www/html

RUN apk add --no-cache \
    git \
    curl \
    bash \
    libpng-dev \
    libjpeg-turbo-dev \
    libzip-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    icu-dev \
    mysql-client \
    postgresql-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    zip \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . .

COPY --from=frontend /app/public/build ./public/build

RUN composer install --optimize-autoloader --no-interaction --no-scripts

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
