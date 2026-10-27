#!/bin/sh
set -eu

port="${PORT:-10000}"
app_key="${APP_KEY:-}"

if [ -z "$app_key" ]; then
    echo "APP_KEY must be configured." >&2
    exit 1
fi

case "$app_key" in
    base64:*) ;;
    *) export APP_KEY="base64:${app_key}" ;;
esac

sed -i "s/Listen 80/Listen ${port}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${port}>/" /etc/apache2/sites-available/000-default.conf

database_path="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
if [ ! -f "$database_path" ]; then
    mkdir -p "$(dirname "$database_path")"
    touch "$database_path"
fi

chown -R www-data:www-data storage bootstrap/cache "$(dirname "$database_path")"

runuser -u www-data -- php artisan migrate --force
runuser -u www-data -- php artisan db:seed --class='Database\Seeders\RenderAdminSeeder' --force

exec apache2-foreground
