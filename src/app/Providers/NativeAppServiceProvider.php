<?php

namespace App\Providers;

use Native\Laravel\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    public function boot(): void
    {
        // Native app lifecycle hook. Keep empty until mobile-specific setup is added.
    }

    public function phpIni(): array
    {
        return [];
    }
}
