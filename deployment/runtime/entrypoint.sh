#!/usr/bin/env bash
set -e

PORT=${PORT:-8000}
APP_ROOT="${ROOT:-/app}"

# Ensure we're in the app directory
cd "$APP_ROOT" || { echo "ERROR: Failed to cd to $APP_ROOT"; exit 1; }

# Verify artisan exists
if [ ! -f "artisan" ]; then
    echo "ERROR: artisan file not found in $(pwd)"
    echo "Contents of $APP_ROOT:"
    ls -la "$APP_ROOT" 2>/dev/null || echo "Directory not accessible"
    exit 1
fi

CONTAINER_MODE="${CONTAINER_MODE:-app}"
case "$CONTAINER_MODE" in
    worker)
        echo "Mode: Worker"
        COMMAND="${COMMAND:-php artisan queue:work --verbose --tries=3 --queue=high,low,default --timeout=90}"
        ;;
    scheduler)
        echo "Mode: Scheduler"
        COMMAND="${COMMAND:-php artisan schedule:work}"
        ;;
    app|*)
        echo "Mode: App"
        COMMAND="${COMMAND:-php artisan octane:start --server=swoole --host=0.0.0.0 --port=${PORT}}"
        ;;
esac

# Cache Laravel
if [ -n "${APP_KEY:-}" ]; then
    echo "Caching Laravel configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan event:cache
    php artisan view:cache
else
    echo "Warning: APP_KEY not defined, skipping cache"
    exit 1
fi

if [ "$CONTAINER_MODE" = "app" ]; then
    if [ "${RUN_MIGRATIONS}" = "true" ]; then
        echo "RUN_MIGRATIONS is set true Running migrations..."
        php artisan migrate --force
    fi

    if [ "${RUN_SEEDERS}" = "true" ]; then
        echo "RUN_SEEDERS is set true Running seeders..."
        php artisan db:seed --force
    fi

    if [ -n "${APP_ADMIN_NAME}" ] && [ -n "${APP_ADMIN_EMAIL}" ] && [ -n "${APP_ADMIN_PASSWORD}" ]; then
        echo "Creating a admin user..."
        php artisan migrate --force
        php artisan make:admin
    fi
else
    echo "Skipping database initializations for mode: $CONTAINER_MODE"
fi

echo "Executing command: $COMMAND"
exec $COMMAND
