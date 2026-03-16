#!/bin/bash

# Manual setup steps if you prefer to run commands individually

echo "Step 1: Create src directory"
mkdir -p src

echo "Step 2: Install Laravel (this may take a few minutes)"
docker run --rm -v $(pwd):/app composer create-project laravel/laravel /app/src

echo "Step 3: Navigate to src and install NativePHP packages"
cd src

echo "Installing nativephp/mobile..."
docker run --rm -v $(pwd):/app -w /app composer require nativephp/mobile

echo "Installing nativephp/laravel..."
docker run --rm -v $(pwd):/app -w /app composer require nativephp/laravel

echo "Step 4: Return to root directory"
cd ..

echo "Step 5: Build Docker containers"
docker-compose build

echo "Step 6: Start containers"
docker-compose up -d

echo "Step 7: Copy environment file and generate key"
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate

echo "Step 8: Update .env database configuration"
docker-compose exec app bash -c "sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env"
docker-compose exec app bash -c "sed -i 's/DB_DATABASE=laravel/DB_DATABASE=laravel/' .env"
docker-compose exec app bash -c "sed -i 's/DB_USERNAME=root/DB_USERNAME=laravel/' .env"
docker-compose exec app bash -c "sed -i 's/DB_PASSWORD=/DB_PASSWORD=password/' .env"

echo "Step 9: Install NativePHP provider"
docker-compose exec app php artisan native:install

echo "Step 10: Run migrations"
docker-compose exec app php artisan migrate

echo ""
echo "Setup complete! Access your app at http://localhost:8000"
