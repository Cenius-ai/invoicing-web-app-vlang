#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

echo "=== Invoicer Setup ==="

# Ensure .env exists
if [ ! -f .env ]; then
    cp .env.example .env
    echo "  -> Created .env from .env.example"
fi

# Create required directories
mkdir -p storage/framework/{cache/data,sessions,views}
mkdir -p storage/app
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p database

# Install dependencies
echo "  -> Running composer install..."
composer install --no-interaction --prefer-dist --no-progress

# Generate APP_KEY
echo "  -> Generating application key..."
php artisan key:generate --force

# Create SQLite database
touch database/database.sqlite

# Run migrations
echo "  -> Running migrations..."
php artisan migrate --force

# Seed demo data
echo "  -> Seeding database..."
php artisan db:seed --force

echo ""
echo "=== Setup complete ==="
echo "Run: php artisan serve --host=0.0.0.0 --port=\${PORT:-8000}"
echo "Then visit http://localhost:8000"
