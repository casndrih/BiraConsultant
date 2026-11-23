#!/bin/sh
set -eu

cd /app
php artisan migrate --force --no-interaction
php artisan db:seed --force --no-interaction

exec frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
