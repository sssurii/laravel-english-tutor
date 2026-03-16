#!/bin/bash

echo "Setting up NativePHP Laravel application..."

# Create src directory if it doesn't exist
mkdir -p src

# Check if Laravel is already installed
if [ ! -f "src/artisan" ]; then
    echo "Installing Laravel..."
    docker run --rm -v $(pwd):/app composer create-project laravel/laravel /app/src

    echo "Installing NativePHP Mobile..."
    cd src
    docker run --rm -v $(pwd):/app -w /app composer require nativephp/mobile
    docker run --rm -v $(pwd):/app -w /app composer require nativephp/laravel
    cd ..
fi

echo "Building Docker containers..."
docker compose build

echo "Starting containers..."
docker compose up -d

echo "Waiting for containers to be ready..."
sleep 5

echo "Setting up Laravel environment..."
docker compose exec -T app cp .env.example .env 2>/dev/null || true
docker compose exec -T app php artisan key:generate
docker compose exec -T app php artisan config:cache

echo "Installing NativePHP provider..."
docker compose exec -T app php artisan native:install

echo ""
echo "=========================================="
echo "Setup complete!"
echo "=========================================="
echo "Application is running at: http://localhost:8000"
echo ""
echo "Useful commands:"
echo "  Start containers:    docker compose up -d"
echo "  Stop containers:     docker compose down"
echo "  View logs:           docker compose logs -f"
echo "  Run artisan:         docker compose exec app php artisan [command]"
echo "  Run NativePHP app:   docker compose exec app php artisan native:run"
echo ""
