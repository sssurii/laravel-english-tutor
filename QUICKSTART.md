# QUICK START GUIDE

## Automated Setup (Recommended)

```bash
chmod +x setup.sh
./setup.sh
```

## Manual Setup (Step by Step)

If you prefer to run commands manually or the automated script fails:

### 1. Install Laravel

```bash
mkdir -p src
docker run --rm -v $(pwd):/app composer create-project laravel/laravel /app/src
```

### 2. Install NativePHP packages

```bash
cd src
docker run --rm -v $(pwd):/app -w /app composer require nativephp/mobile
docker run --rm -v $(pwd):/app -w /app composer require nativephp/laravel
cd ..
```

### 3. Build and start Docker containers

```bash
docker-compose build
docker-compose up -d
```

### 4. Configure Laravel

```bash
# Copy environment file
docker-compose exec app cp .env.example .env

# Generate application key
docker-compose exec app php artisan key:generate

# Update database configuration
docker-compose exec app bash -c "sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env"
docker-compose exec app bash -c "sed -i 's/DB_USERNAME=root/DB_USERNAME=laravel/' .env"
docker-compose exec app bash -c "sed -i 's/DB_PASSWORD=/DB_PASSWORD=password/' .env"
```

### 5. Install NativePHP

```bash
docker-compose exec app php artisan native:install
```

### 6. Run migrations

```bash
docker-compose exec app php artisan migrate
```

### 7. Access your application

Open your browser and visit: **http://localhost:8000**

## Verify NativePHP Installation

To test NativePHP, you can run:

```bash
docker-compose exec app php artisan native:serve
```

Note: For actual mobile app builds, set up Android Studio (Android) and Xcode (iOS on macOS) on your host machine.

## Next Steps

1. Start developing in the `src/` directory
2. Create routes in `src/routes/web.php`
3. Create controllers with `docker-compose exec app php artisan make:controller YourController`
4. Create models with `docker-compose exec app php artisan make:model YourModel`

## Troubleshooting

### Containers won't start
```bash
docker-compose down
docker-compose up -d
docker-compose logs -f
```

### Permission denied errors
```bash
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

### Database connection errors
Make sure the database container is running:
```bash
docker-compose ps
```

Check database credentials in `src/.env` match the docker-compose.yml settings.
