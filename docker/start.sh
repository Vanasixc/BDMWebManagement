#!/bin/sh

export PORT=${PORT:-8080}
echo "==> Starting on port ${PORT}"

# Permissions
mkdir -p /tmp/nginx/client_body /tmp/nginx/proxy /tmp/nginx/fastcgi
chown -R www-data:www-data /app/storage /app/bootstrap/cache 2>/dev/null || true

# Generate nginx config
envsubst '${PORT}' < /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf

# Start supervisord (nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisord.conf
