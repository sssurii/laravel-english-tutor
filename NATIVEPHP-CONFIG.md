# NativePHP Mobile Configuration

This project uses NativePHP Mobile.

## Config File

Primary config:
- `src/config/nativephp.php`

Key environment variables in `src/.env`:
- `NATIVEPHP_APP_ID=com.example.englishtutor`
- `NATIVEPHP_APP_VERSION=1.0.0`
- `NATIVEPHP_APP_VERSION_CODE=1`
- `NATIVEPHP_START_URL=/`

Optional deep linking:
- `NATIVEPHP_DEEPLINK_SCHEME=englishtutor`
- `NATIVEPHP_DEEPLINK_HOST=example.com`

## Dev Commands

Run from project root:

```bash
./dev.sh native install
./dev.sh native serve
```

Direct Docker equivalent:

```bash
docker compose exec app php artisan native:install
docker compose exec app php artisan native:serve
```

## Build Commands

Use host machine toolchains for production builds.

```bash
cd src
php artisan native:build
```

Requirements:
- Android: Android Studio + SDK/NDK
- iOS: Xcode on macOS

## Mobile API Examples

```php
use Native\Mobile\Facades\Camera;
use Native\Mobile\Facades\Dialog;
use Native\Mobile\Facades\Biometrics;

Camera::getPhoto();
Dialog::alert('Time for your English lesson!');
Biometrics::prompt();
```

## Notes

- `src/app/Providers/NativeAppServiceProvider.php` can stay minimal until app-specific mobile hooks are needed.
- `src/bootstrap/providers.php` controls service provider registration in Laravel 12.

Docs: https://nativephp.com/docs/mobile/2
