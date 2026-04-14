#!/bin/bash

cd "$(dirname "$0")"

# Start PHP development server on localhost:3000
if [ ! -f vendor/autoload.php ]; then
    echo "Dependencies missing (no vendor/autoload.php)."
    echo "Install with: composer install"
    echo "Or with Docker:"
    echo "  docker run --rm -v \"$(pwd)\":/app -w /app composer:2 composer install"
    exit 1
fi

echo "Starting BALTACI Artisan Kitchen development server..."

# Check if port 3000 is already in use
if lsof -ti:3000 > /dev/null 2>&1; then
    echo "Port 3000 is already in use. Stopping existing server..."
    kill $(lsof -ti:3000)
    sleep 1
    echo "Previous server stopped."
fi

echo "Server will be available at: http://localhost:3000"
echo "Press Ctrl+C to stop the server"
echo ""

php -S localhost:3000 -t public public/index.php

