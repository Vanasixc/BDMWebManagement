#!/bin/sh
# Tidak pakai set -e agar supervisord tetap jalan meskipun ada command yang gagal

echo "==> [start.sh] Starting container..."

# Set default PORT jika tidak diset Railway
export PORT=${PORT:-8080}
echo "==> [start.sh] PORT=${PORT}"

# Buat temp directories untuk Nginx
mkdir -p /tmp/nginx/client_body /tmp/nginx/proxy /tmp/nginx/fastcgi

# Set permissions storage
chown -R www-data:www-data /app/storage /app/bootstrap/cache 2>/dev/null || true

# Generate nginx config dari template (substitute $PORT)
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf
echo "==> [start.sh] Nginx config generated for port ${PORT}"

# Cache Laravel (gunakan || true agar tidak fatal jika gagal)
echo "==> [start.sh] Caching Laravel config/routes/views..."
php artisan config:cache || echo "WARNING: config:cache failed, continuing..."
php artisan route:cache  || echo "WARNING: route:cache failed, continuing..."
php artisan view:cache   || echo "WARNING: view:cache failed, continuing..."

# Migrate database (non-fatal)
echo "==> [start.sh] Running migrations..."
php artisan migrate --force || echo "WARNING: migrate failed (DB not ready?), continuing..."

# Seed data (non-fatal, semua sudah idempotent)
echo "==> [start.sh] Seeding initial data..."
php artisan db:seed --class=UserSeeder         --force 2>/dev/null || true
php artisan db:seed --class=DropdownConfigSeeder --force 2>/dev/null || true
php artisan db:seed --class=WebsiteSeeder      --force 2>/dev/null || true

echo "==> [start.sh] Starting Nginx + PHP-FPM via supervisord..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
