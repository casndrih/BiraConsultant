FROM dunglas/frankenphp:1-php8.3-alpine

RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    postgresql-dev \
    git \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql zip bcmath gd mbstring

WORKDIR /app

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --optimize-autoloader --no-scripts

COPY . .

# Laravel resolves real paths for compiled views and package manifests during
# Composer's post-autoload scripts, so create writable runtime directories first.
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Install the reproducible locked frontend tree and audit production
# dependencies only. Laravel Mix/webpack are build-time devDependencies; their
# legacy transitive advisories must not block the production runtime audit.
# Keep the full dev toolchain available until assets have been compiled.
RUN apk add --no-cache nodejs npm \
    && npm ci \
    && npm audit --omit=dev --audit-level=high \
    && npm run production \
    && rm -rf node_modules

RUN composer dump-autoload --no-dev --optimize \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/Caddyfile /etc/caddy/Caddyfile
COPY docker/start.sh /usr/local/bin/start-app
RUN chmod +x /usr/local/bin/start-app

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    PORT=3000

EXPOSE 3000

CMD ["/usr/local/bin/start-app"]
