FROM node:20-bookworm-slim AS assets
WORKDIR /app
COPY package*.json ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi
COPY . .
RUN npm run build

FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libonig-dev libpng-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN php artisan optimize:clear || true

EXPOSE 8080

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
