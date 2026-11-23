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
RUN composer update symfony/routing --with-all-dependencies --no-dev --no-interaction --prefer-dist --no-progress --optimize-autoloader --no-scripts \
    && composer audit --locked --no-dev

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

# Refresh the lockfile with non-breaking security updates before installing.
# Audit the complete dependency tree (including build tooling), and fail on
# any reported severity rather than masking development dependency findings.
RUN apk add --no-cache nodejs npm \
    && npm install --package-lock-only --ignore-scripts --no-audit \
    && npm audit fix --package-lock-only --ignore-scripts \
    && npm ci \
    && npm audit --audit-level=low \
    && npm run build \
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
