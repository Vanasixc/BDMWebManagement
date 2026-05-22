#!/bin/sh
set -e

echo "==> [start.sh] Preparing Laravel..."

# Set default PORT jika tidak diset oleh Railway
export PORT=${PORT:-8080}

# Buat temp directories untuk Nginx
mkdir -p /tmp/nginx/client_body /tmp/nginx/proxy /tmp/nginx/fastcgi

# Set ownership (pastikan PHP-FPM bisa write ke storage)
chown -R www-data:www-data /app/storage /app/bootstrap/cache 2>/dev/null || true

# Generate nginx config dari template (substitute $PORT)
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
echo "==> [start.sh] Nginx configured on port ${PORT}"

# Cache Laravel config, routes, dan views
echo "==> [start.sh] Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrate database
echo "==> [start.sh] Running migrations..."
php artisan migrate --force

# Seed data (semua seeder idempotent)
echo "==> [start.sh] Seeding initial data..."
php artisan db:seed --class=UserSeeder --force 2>/dev/null || true
php artisan db:seed --class=DropdownConfigSeeder --force 2>/dev/null || true
php artisan db:seed --class=WebsiteSeeder --force 2>/dev/null || true

echo "==> [start.sh] Starting Nginx + PHP-FPM via supervisord..."
exec supervisord -c /etc/supervisord.conf
